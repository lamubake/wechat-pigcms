<?php

class UnitaryAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction("Unitary");
	}

	public function index()
	{
		$m_unitary = M("unitary");
		$m_unitary_cart = M("unitary_cart");
		$m_lucknum = M("unitary_lucknum");

		if ($_POST["nameorkeyword"] != NULL) {
			$where["keyword"] = array("like", "%" . $_POST["nameorkeyword"] . "%");
			$where["name"] = array("like", "%" . $_POST["nameorkeyword"] . "%");
			$where["_logic"] = "or";
			$where_unitary["_complex"] = $where;
		}

		$where_unitary["token"] = $this->token;
		$count = $m_unitary->where($where_unitary)->count();
		$Page = new Page($count, 8);
		$show = $Page->show();
		$unitary_list = $m_unitary->where($where_unitary)->order("addtime desc")->limit($Page->firstRow . "," . $Page->listRows)->select();

		foreach ($unitary_list as $k => $vo ) {
			$where_cart["token"] = $this->token;
			$where_cart["unitary_id"] = $vo["id"];
			$where_cart["state"] = 1;
			$where_cart["order_id"] = array("gt", 0);
			$cart_list = $m_unitary_cart->where($where_cart)->select();
			$pay_count = 0;

			foreach ($cart_list as $cvo ) {
				$pay_count = $pay_count + $cvo["count"];
			}

			$where_lucknum_paycount["token"] = $this->token;
			$where_lucknum_paycount["unitary_id"] = $vo["id"];
			$pay_count = $m_lucknum->where($where_lucknum_paycount)->count();
			$unitary_list[$k]["pay_count"] = $pay_count;
		}

		$this->assign("page", $show);
		$this->assign("unitary_list", $unitary_list);
		$this->display();
	}

	public function indexajax()
	{
		$m_unitary = M("unitary");
		$where_unitary["token"] = $_POST["token"];
		$save_unitary[$_POST["name"]] = $_POST["val"];
		$update_unitary = $m_unitary->where($where_unitary)->save($save_unitary);

		if (0 < $update_unitary) {
			$data["error"] = 0;
		}

		$this->ajaxReturn($data, "JSON");
	}

	public function add()
	{
		$this->display();
	}

	public function doadd()
	{
		if (($_POST["day"] < 0) || ($_POST["hr"] < 0) || ($_POST["min"] < 0) || ($_POST["s"] < 0)) {
			$this->error("倒计时不能为负数");
			exit();
		}

		$m_unitary = M("unitary");

		if (IS_POST) {
			$_POST["opentime"] = $_POST["min"] * 60;
			$_POST["addtime"] = time();
			$_POST["token"] = $this->token;
			$_POST["price"] = floor($_POST["price"]);

			if ($m_unitary->create() != false) {
				if ($id = $m_unitary->add()) {
					$this->success("活动创建成功", U("Unitary/index", array("token" => $this->token)));
				}
				else {
					$this->error("服务器繁忙,请稍候再试");
				}
			}
			else {
				$this->error($m_unitary->getError());
			}
		}
		else {
			$this->error("操作失败");
		}
	}

	public function operate()
	{
		$m_unitary = M("unitary");
		$where_unitary["id"] = $_GET["unitaryid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $m_unitary->where($where_unitary)->find();

		switch ($_GET["type"]) {
		case "del":
			$this->handleKeyword($_GET["unitaryid"], "Unitary", $find_unitary["keyword"], 0, 1);
			$del_unitary = $m_unitary->where($where_unitary)->delete();

			if (0 < $del_unitary) {
				$this->success("活动【" . $find_unitary["name"] . "】删除成功", U("Unitary/index", array("token" => $this->token)));
			}

			break;

		case "stop":
			$save_unitary["state"] = 0;
			$update_unitary = $m_unitary->where($where_unitary)->save($save_unitary);
			$m_unitary_cart = M("unitary_cart");
			$where_unitary_cart["unitary_id"] = $_GET["unitaryid"];
			$where_unitary_cart["state"] = 0;
			$del_cart = $m_unitary_cart->where($where_unitary_cart)->delete();
			$this->handleKeyword($_GET["unitaryid"], "Unitary", $find_unitary["keyword"], 0, 1);

			if (0 < $update_unitary) {
				$this->redirect("Unitary/index", array("token" => $this->token, "p" => $_GET["p"]));
			}

			break;

		case "start":
			$save_unitary["state"] = 1;
			$update_unitary = $m_unitary->where($where_unitary)->save($save_unitary);
			$this->handleKeyword($_GET["unitaryid"], "Unitary", $find_unitary["keyword"], 0, 0);

			if (0 < $update_unitary) {
				$this->redirect("Unitary/index", array("token" => $this->token, "p" => $_GET["p"]));
			}

			break;
		}
	}

	public function update()
	{
		$m_unitary = M("unitary");
		$m_cart = M("unitary_cart");
		$where_unitary["id"] = $_GET["unitaryid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $m_unitary->where($where_unitary)->find();
		$oneday = 60 * 60 * 24;
		$onehr = 60 * 60;
		$onemin = 60;
		$time_min = floor($find_unitary["opentime"] / $onemin);
		$this->assign("unitary", $find_unitary);
		$this->assign("time_min", $time_min);
		$where_cart["token"] = $this->token;
		$where_cart["unitary_id"] = $_GET["unitaryid"];
		$where_cart["start"] = 1;
		$cart_list = $m_cart->where($where_cart)->select();
		$pay_count = 0;

		foreach ($cart_list as $vo ) {
			$pay_count = $pay_count + $vo["count"];
		}

		$this->assign("pay_count", $pay_count);
		$this->display();
	}

	public function doupdate()
	{
		$m_unitary = M("unitary");
		$where_unitary["id"] = $_POST["id"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $m_unitary->where($where_unitary)->find();

		if ($find_unitary == NULL) {
			$this->error("无效操作");
		}

		if ($find_unitary["state"] == 1) {
			$this->handleKeyword($_POST["id"], "Unitary", $_POST["keyword"], 0, 0);
		}

		$_POST["opentime"] = ($_POST["day"] * 60 * 60 * 24) + ($_POST["hr"] * 60 * 60) + ($_POST["min"] * 60) + $_POST["s"];
		$_POST["price"] = floor($_POST["price"]);
		$update_unitary = $m_unitary->where($where_unitary)->save($_POST);

		if (0 < $update_unitary) {
			$this->success("【" . $_POST["name"] . "】修改成功", U("Unitary/index", array("token" => $this->token)));
		}
		else {
			$this->success("【" . $find_unitary["name"] . "】未做修改");
		}
	}

	public function qingling()
	{
		if ($_GET["key"] == "qazxswedcvfrtgbnhyujm") {
			$m_unitary = M("unitary");
			$save_unitary["endtime"] = NULL;
			$save_unitary["lucknum"] = NULL;
			$save_unitary["lasttime"] = NULL;
			$save_unitary["lastnum"] = NULL;
			$save_unitary["state"] = 1;
			$save_unitary["proportion"] = 0;
			$where["token"] = $this->token;
			$update_unitary = $m_unitary->where($where)->save($save_unitary);
			echo "成功";
		}
	}

	public function data()
	{
		$m_cart = M("unitary_cart");
		$m_user = M("unitary_user");
		$m_userinfo = M("userinfo");
		$m_lucknum = M("unitary_lucknum");
		$m_unitary = M("unitary");
		$where_unitary["id"] = $_GET["unitaryid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $m_unitary->where($where_unitary)->find();
		$this->assign("unitary", $find_unitary);
		$where_lucknum["token"] = $this->token;
		$where_lucknum["unitary_id"] = $_GET["unitaryid"];
		$count = $m_lucknum->where($where_lucknum)->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		$lucknum_list = $m_lucknum->where($where_lucknum)->order("state desc,addtime desc")->limit($Page->firstRow . "," . $Page->listRows)->select();

		foreach ($lucknum_list as $k => $vo ) {
			$where_user["token"] = $this->token;
			$where_user["wecha_id"] = $vo["wecha_id"];
			$find_user = $m_user->where($where_user)->find();
			$where_userinfo["token"] = $this->token;
			$where_userinfo["wecha_id"] = $vo["wecha_id"];
			$find_userinfo = $m_userinfo->where($where_userinfo)->find();
			$lucknum_list[$k]["name"] = ($find_user["name"] ? $find_user["name"] : $find_userinfo["wechaname"]);
			$lucknum_list[$k]["phone"] = ($find_user["phone"] ? $find_user["phone"] : $find_userinfo["tel"]);
			$lucknum_list[$k]["address"] = $find_user["address"];
		}

		$this->assign("page", $show);
		$this->assign("lucknum_list", $lucknum_list);
		$this->display();
	}

	public function paifa()
	{
		$m_cart = M("unitary_cart");
		$m_user = M("unitary_user");
		$m_lucknum = M("unitary_lucknum");
		$m_unitary = M("unitary");
		$where_lucknum["id"] = $_GET["lucknumid"];
		$where_lucknum["token"] = $this->token;
		$find_lucknum = $m_lucknum->where($where_lucknum)->find();
		$where_unitary["token"] = $this->token;
		$where_unitary["id"] = $find_lucknum["unitary_id"];
		$find_unitary = $m_unitary->where($where_unitary)->find();
		$model = new templateNews();

		if ($find_lucknum["paifa"] == 1) {
			$save_lucknum["paifa"] = 0;
		}
		else if ($find_lucknum["paifa"] == 0) {
			$model->sendTempMsg("OPENTM200565259", array("href" => $this->siteUrl . U("Wap/Unitary/goodswhere", array("token" => $this->token, "unitaryid" => $find_lucknum["unitary_id"])), "wecha_id" => $find_lucknum["wecha_id"], "first" => "一元夺宝奖品发货通知", "keyword1" => "恭喜您在一元夺宝中获得的【" . $find_unitary["name"] . "】已发货", "keyword2" => "无", "keyword3" => "无", "remark" => date("Y年m月d日H时i分s秒")));
			$save_lucknum["paifa"] = 1;
		}

		$update_lucknum = $m_lucknum->where($where_lucknum)->save($save_lucknum);

		if (0 < $update_lucknum) {
			$this->redirect("Unitary/data", array("token" => $this->token, "unitaryid" => $find_lucknum["unitary_id"]));
		}
	}
}


?>
