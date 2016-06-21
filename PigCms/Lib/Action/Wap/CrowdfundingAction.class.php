<?php

class CrowdfundingAction extends WapAction
{
	public $is_wechat;

	public function _initialize()
	{
		parent::_initialize();
		
		$is_wechat = array("order", "my_support", "my_focus", "cancel_focus", "pay", "payReturn", "focus");

		if (in_array(ACTION_NAME, $is_wechat)) {
			if (empty($this->wecha_id)) {
				$this->error("没有个人信息，无法操作");
			}
		}

		$copyright = M("Home")->where(array("token" => $this->token))->getField("copyright");
		$this->assign("copyright", $copyright);
	}

	public function home()
	{
		$where = array(
			"token"  => $this->token,
			"status" => array("eq", 3)
			);
		$list_type = ($_GET["list_type"] ? $this->_get("list_type", "trim") : "zhtj");
		$limit = "";
		$page = (isset($_GET["page"]) ? intval($_GET["page"]) : 1);
		$pageSize = 5;
		$count = M("Crowdfunding")->where($where)->count();
		$first = ($page - 1) * $pageSize;
		$last = $first + $pageSize;
		$limit = $first . "," . $last;

		switch ($list_type) {
		case "zhtj":
			$order = "praise desc,focus desc,supports desc";
			break;

		case "zxsx":
			$order = "start desc,id desc";
			break;

		case "gzzg":
			$order = "focus desc,id desc";
			break;

		case "zczd":
			$order = "supports desc,id desc";
			break;

		default:
			$order = "id desc";
			break;
		}

		$list = M("Crowdfunding")->where($where)->order($order)->limit($limit)->field("id")->select();

		foreach ($list as $key => $val ) {
			$list[$key] = $this->getCrowdfunding($val["id"]);
		}

		if ($this->_get("ajax", "intval") == "") {
			$solid = M("Crowdfunding")->where($where)->limit(5)->field("id,pic")->select();
			$this->assign("list_type", $list_type);
			$this->assign("solid", $solid);
			$this->assign("list", $list);
			$this->display();
		}
		else if (!empty($list)) {
			echo json_encode($list);
		}
		else {
			echo "undefined";
		}
	}

	public function index()
	{
		$id = $this->_get("id", "intval");
		$info = $this->getCrowdfunding($id);

		if (empty($info["id"])) {
			$this->error("参数错误");
		}

		if ($this->checkStatus($info)) {
			$this->assign("is_over", 1);
		}

		if (($info["is_attention"] == 2) && !$this->isSubscribe()) {
			$this->memberNotice("", 1);
		}
		else {
			if ((($info["is_reg"] == 1) && empty($this->fans)) || (($info["is_reg"] == 1) && empty($this->fans["tel"]))) {
				$this->memberNotice();
			}
		}

		$reward = M("Crowdfunding_reward")->where(array("token" => $this->token, "pid" => $info["id"]))->order("money asc")->select();

		foreach ($reward as $key => $val ) {
			$count = $this->getOrderCount($val["pid"], $val["id"]);
			$reward[$key]["count"] = $count;
			$reward[$key]["surplus"] = $val["people"] - $count;
		}

		$this->assign("selfless", $this->getOrderCount($info["id"], -1));
		$this->assign("reward", $reward);
		$this->assign("info", $info);
		$this->display();
	}

	public function detail()
	{
		$id = $this->_get("id", "intval");
		$info = $this->getCrowdfunding($id);
		$company = M("Company")->where(array("token" => $this->token, "isbranch" => 0))->find();
		$this->assign("originate", M("Crowdfunding")->where(array("token" => $this->token))->count());
		$this->assign("support", $this->getOrderCount($id));
		$this->assign("company", $company);
		$this->assign("info", $info);
		$this->display();
	}

	public function order()
	{
		$id = $this->_get("id", "intval");
		$reward_id = $this->_get("reward_id", "intval");
		$info = $this->getCrowdfunding($id);
		$reward = $this->getReward($reward_id, $id);

		if ($this->checkStatus($info)) {
			$this->assign("is_over", 1);
		}

		if (($this->wxuser["winxintype"] == 3) && ($this->wxuser["oauth"] == 1)) {
			$addr = new WechatAddr($this->wxuser);
			$this->assign("addrSign", $addr->addrSign());
		}

		$db = M("Userinfo");
		$where["token"] = $this->token;
		$where["wecha_id"] = $this->wecha_id;
		$list = $db->where($where)->find();
		$this->assign("list", $list);
		$this->assign("reward", $reward);
		$this->assign("info", $info);
		$this->display();
	}

	public function my_support()
	{
		$where = array("token" => $this->token, "wecha_id" => $this->wecha_id, "is_delete" => 0);
		$count = M("Crowdfunding_order")->where($where)->count();
		$Page = new Page($count, 5);
		$Page->setConfig("theme", " %upPage% %downPage%");
		$order = M("Crowdfunding_order")->where($where)->order("add_time desc")->limit($Page->firstRow . "," . $Page->listRows)->select();

		foreach ($order as $key => $val ) {
			$cinfo = $this->getCrowdfunding($val["pid"]);

			if ($this->checkStatus($info)) {
				$cinfo["is_over"] = 1;
			}

			$order[$key] = array_merge($val, $cinfo);
		}

		$this->assign("page", $Page->show());
		$this->assign("order", $order);
		$this->display();
	}

	public function order_del()
	{
		$where = array("token" => $this->token, "wecha_id" => $this->wecha_id, "orderid" => $this->_get("orderid", "trim"));
		$order = M("Crowdfunding_order")->where($where)->find();

		if (empty($order)) {
			$result["success"] = false;
		}

		if (M("Crowdfunding_order")->where($where)->setField("is_delete", 1)) {
			$result["success"] = true;
		}

		echo json_encode($result);
	}

	public function my_focus()
	{
		$where = array("token" => $this->token, "wecha_id" => $this->wecha_id);
		$count = M("Crowdfunding_focus")->where($where)->count();
		$Page = new Page($count, 5);
		$Page->setConfig("theme", " %upPage% %downPage% %prePage% %nextPage% ");
		$focus = M("Crowdfunding_focus")->where($where)->order("id desc")->limit($Page->firstRow . "," . $Page->listRows)->select();

		foreach ($focus as $key => $val ) {
			$focus[$key] = array_merge($val, $this->getCrowdfunding($val["cid"]));
		}

		$this->assign("page", $Page->show());
		$this->assign("focus", $focus);
		$this->display();
	}

	public function cancel_focus()
	{
		$where = array("token" => $this->token, "wecha_id" => $this->wecha_id, "cid" => $this->_get("cid", "intval"));

		if (M("Crowdfunding_focus")->where($where)->delete()) {
			$result["success"] = true;
		}
		else {
			$result["success"] = false;
		}

		echo json_encode($result);
	}

	public function pay()
	{
		$pid = $this->_request("pid", "intval");
		$reward_id = $this->_post("reward_id", "intval");
		$orderid = $this->_get("orderid", "trim");
		$info = $this->getCrowdfunding($pid);
		$reward = $this->getReward($reward_id, $pid);

		if ($this->checkStatus($info)) {
			$this->error("项目已经结束");
		}

		if (IS_POST) {
			$db = M("Userinfo");
			$where["token"] = $this->token;
			$where["wecha_id"] = $this->wecha_id;
			$data["tel"] = $this->_POST("tel");
			$data["wechaname"] = $this->_POST("username");
			$data["address"] = $this->_POST("address");
			$infos = $db->where($where)->find();

			if ($infos == NULL) {
				$data["token"] = $this->token;
				$data["wecha_id"] = $this->wecha_id;
				$db->add($data);
			}
			else {
				$db->where($where)->save($data);
			}
		}

		if (empty($orderid)) {
			$_POST["wecha_id"] = $this->wecha_id;
			$_POST["token"] = $this->token;
			$_POST["add_time"] = time();
			$_POST["orderid"] = date("YmdHis", time()) . mt_rand(1000, 9999);
			$_POST["order_name"] = $info["name"];
			$_POST["reward_id"] = $reward_id;
			$_POST["status"] = 0;
			$_POST["pid"] = $pid;

			if ($reward_id == "-1") {
				$_POST["price"] = $this->_post("price", "floatval");
			}
			else {
				$_POST["price"] = $reward["money"] + $reward["carriage"];
			}

			if (M("Crowdfunding_order")->add($_POST)) {
				$this->success("提交成功，正在跳转支付页面..", U("Alipay/pay", array("from" => "Crowdfunding", "orderName" => $_POST["order_name"], "single_orderid" => $_POST["orderid"], "token" => $_POST["token"], "wecha_id" => $_POST["wecha_id"], "price" => $_POST["price"], "notOffline" => 1)));
			}
			else {
				$this->error("未知错误，请稍后再试");
			}
		}
		else {
			$order = M("Crowdfunding_order")->where("token = '$this->token' AND wecha_id = '$this->wecha_id' AND orderid = $orderid AND paid = 0")->find();

			if ($order) {
				$this->success("提交成功，正在跳转支付页面..", U("Alipay/pay", array("from" => "Crowdfunding", "orderName" => $order["order_name"], "single_orderid" => $order["orderid"], "token" => $order["token"], "wecha_id" => $order["wecha_id"], "price" => $order["price"], "notOffline" => 1)));
			}
			else {
				$this->error("无效的订单");
			}
		}
	}

	public function payReturn()
	{
		$orderid = trim($_GET["orderid"]);

		if (isset($_GET["nohandle"])) {
			$where = array("token" => $this->token, "wecha_id" => $this->wecha_id, "orderid" => $orderid);
			$order = M("Crowdfunding_order")->where($where)->find();
			$this->redirect(U("Crowdfunding/index", array("token" => $this->token, "wecha_id" => $this->wecha_id, "id" => $order["pid"])));
		}
		else {
			ThirdPayCrowdfunding::index($orderid);
		}
	}

	public function initFocus()
	{
		$id = $this->_get("id", "intval");
		$name = $this->_get("name", "trim");
		$this->getCounter($id, $name);
	}

	public function praise()
	{
		$id = $this->_get("id", "intval");
		$where = array("token" => $this->token, "id" => $id);

		if (empty($_COOKIE["Crowdfunding_praise_" . $id])) {
			if (M("Crowdfunding")->where($where)->setInc("praise", 1)) {
				cookie("Crowdfunding_praise_" . $id, "true", time() + (3600 * 24 * 365));
				$this->getCounter($id);
			}
		}
		else {
			echo json_encode(array("error" => "已点赞"));
		}
	}

	public function focus()
	{
		$id = $this->_get("id", "intval");
		$name = $this->_get("name", "trim");

		if (empty($name)) {
			echo json_encode(array("error" => "你还没有个人信息"));
			exit();
		}

		$where = array("cid" => $id, "wecha_id" => $name, "token" => $this->token);

		if (M("Crowdfunding_focus")->where($where)->find()) {
			echo json_encode(array("error" => "已关注"));
		}
		else {
			M("Crowdfunding_focus")->add($where);
			M("Crowdfunding")->where(array("token" => $this->token, "id" => $id))->setInc("focus", 1);
			$this->getCounter($id);
		}
	}

	public function checkStatus($info)
	{
		$end = strtotime("+" . $info["day"] . " day", $info["start"]);
		$is_max = false;

		if ($info["max"] != 0) {
			$price = $this->getOrderCount($info["id"], "", 1);
			$max = $info["fund"] * ($info["max"] / 100);

			if ($max < $price) {
				$is_max = true;
			}
		}

		if (($end <= time()) || ($info["status"] == 4) || $is_max) {
			M("Crowdfunding")->where(array(
	"token"  => $this->token,
	"id"     => $info["id"],
	"status" => array("lt", 4)
	))->save(array("status" => 4));
			return true;
		}
		else {
			return false;
		}
	}

	public function getCounter($id, $name)
	{
		$where = array("token" => $this->token, "id" => $id);
		$arr = array(
			"flag"         => $this->isFocus($id, $name = ""),
			"result"       => array("isSuccess" => true),
			"focusCounter" => array("praise" => M("Crowdfunding")->where($where)->getField("praise"), "focus" => M("Crowdfunding")->where($where)->getField("focus"))
			);
		echo json_encode($arr);
	}

	public function isFocus($id, $name)
	{
		$where = array("cid" => $id, "wecha_id" => $name, "token" => $this->token);

		if (M("Crowdfunding_focus")->where($where)->find()) {
			return "yes";
		}
		else {
			return "no";
		}
	}

	public function getCrowdfunding($id)
	{
		$where = array(
			"token"  => $this->token,
			"id"     => $id,
			"status" => array("gt", 2)
			);
		$info = M("Crowdfunding")->where($where)->find();

		if (empty($info)) {
			$this->error("没找到项目");
		}

		$info["end"] = strtotime("+" . $info["day"] . " days", $info["start"]);
		$info["price_count"] = $this->getOrderCount($id, "", 1);
		$info["people_count"] = $this->getOrderCount($id);
		$remain_day = intval(($info["end"] - time()) / (60 * 60 * 24));
		$info["remain_day"] = ($remain_day < 0 ? 0 : $remain_day);

		if ($info["max"] != 0) {
			$info["fund"] = $info["fund"] * ($info["max"] / 100);
		}

		$progress = $this->percent($info["price_count"], $info["fund"]);
		$info["progress"] = $progress;
		$info["percent"] = (1 < ($info["price_count"] / $info["fund"]) ? "100%" : $progress);
		return $info;
	}

	public function getReward($id, $pid)
	{
		if ($id == "-1") {
			$info["id"] = -1;
		}
		else {
			$where = array("token" => $this->token, "id" => $id, "pid" => $pid);
			$info = M("Crowdfunding_reward")->where($where)->find();
			$info["payPrice"] = ($info["money"] + $info["carriage"]) * 1;
		}

		return $info;
	}

	public function getOrderCount($pid, $reward_id, $type)
	{
		$where = array("token" => $this->token, "paid" => "1");

		if ($pid != "") {
			$where["pid"] = $pid;
		}

		if ($reward_id != "") {
			$where["reward_id"] = $reward_id;
		}

		$count = 0;

		if (empty($type)) {
			$people = M("Crowdfunding_order")->where($where)->count();

			if ($people) {
				$count = $people;
			}
		}
		else {
			$price = M("Crowdfunding_order")->where($where)->sum("price");

			if ($price) {
				$count = $price;
			}
		}

		return $count;
	}

	public function percent($p, $t)
	{
		if ($t == 0) {
			$val = 1;
		}
		else {
			$val = $p / $t;
		}

		$num = sprintf("%.2f%%", $val * 100);
		return $num;
	}

	public function footer()
	{
		$this->display();
	}
}


?>
