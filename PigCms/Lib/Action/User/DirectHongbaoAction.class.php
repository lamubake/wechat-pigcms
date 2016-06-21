<?php

class DirectHongbaoAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction("DirectHongbao");
	}

	public function index()
	{
		$hongbao = M("directhongbao");
		$search_word = $this->_request("search_word", trim);
		$where = array("token" => $this->token);

		if (!empty($search_word)) {
			$where["act_name|send_name"] = array("like", "%" . $search_word . "%");
		}

		$total = $hongbao->where($where)->count();
		$page = new Page($total, 15);
		$list = $hongbao->where($where)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();
		$this->assign("list", $list);
		$this->assign("token", $this->token);
		$this->assign("search_word", $search_word);
		$this->assign("page", $page->show());
		$this->display();
	}

	public function hongbao_add()
	{
		if (IS_POST) {
			$data = array();
			$data["send_name"] = trim($_POST["send_name"]);
			$data["act_name"] = trim($_POST["act_name"]);
			$data["wishing"] = trim($_POST["wishing"]);
			$data["total_money"] = $_POST["total_money"];
			$data["min_money"] = $_POST["min_money"];
			$data["max_money"] = $_POST["max_money"];
			$data["hb_type"] = (int) $_POST["hb_type"];
			$data["send_type"] = (int) $_POST["send_type"];
			$data["gid"] = (int) $_POST["wechatgroupid"];
			$data["remark"] = trim($_POST["remark"]);
			$data["id"] = (int) $_POST["id"];

			if ($data["send_name"] == "") {
				$this->error("红包发送者名称不能为空");
			}

			if ($data["send_name"] == "") {
				$this->error("红包发送者名称不能为空");
			}

			if ($data["act_name"] == "") {
				$this->error("红包发送者名称不能为空");
			}

			if ($data["wishing"] == "") {
				$this->error("红包祝福语不能为空");
			}

			if (!is_numeric($data["total_money"])) {
				$this->error("总金额请输入数字");
			}

			if (!is_numeric($data["min_money"]) || !is_numeric($data["max_money"])) {
				$this->error("随机金额请输入数字");
			}

			if ($data["max_money"] < $data["min_money"]) {
				$this->error("随机最小金额请输入小于随机最大金额的值");
			}

			if ($data["total_money"] < $data["max_money"]) {
				$this->error("总金额不足");
			}

			if ($data["hb_type"] == 2) {
				$data["group_nums"] = (int) $_POST["group_nums"];

				if ($data["min_money"] < $data["group_nums"]) {
					$this->error("最小金额数必须大于裂变人数");
				}
			}
			else {
				if ($data["min_money"] < 1) {
					$this->error("普通红包,最小金额数必须大于等于1");
				}

				$data["group_nums"] = 0;
			}

			if ($data["send_type"] == 1) {
				$numbers = M("wechat_group_list")->where(array("g_id" => $data["gid"], "token" => $this->token))->count();

				if ($numbers < 1) {
					$this->error("该分组下没有找到粉丝");
				}

				$data["totalnums"] = $numbers;
			}
			else if ($data["send_type"] == 2) {
				$data["fans_id"] = $_POST["openid"];
				$data["fans_name"] = $_POST["fans_name"];

				if ($data["fans_id"] == "") {
					$this->error("请选择粉丝");
				}

				$data["totalnums"] = substr_count($data["fans_id"], ",");
			}
			else {
				$numbers = M("wechat_group_list")->where(array("token" => $this->token))->count();

				if (1000 < $numbers) {
					$this->error("粉丝过多，请选择分组或指定粉丝的方式发送红包");
				}
				else if ($numbers == 0) {
					$this->error("未获取到粉丝");
				}

				$data["totalnums"] = $numbers;
			}

			$totalmoney = $data["min_money"] * $data["totalnums"];

			if ($data["total_money"] < $totalmoney) {
				$this->error("您输入的总金额将不足，可以减少发送的粉丝或增加总金额");
			}

			if ($data["id"] == "") {
				$data["token"] = $this->token;
				$data["send_status"] = 0;
				$add = M("directhongbao")->add($data);

				if ($add) {
					F($this->token . "_directhongbao", NULL);
					$this->success("红包添加成功", U("DirectHongbao/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("添加红包失败");
				}
			}
			else {
				$update = M("directhongbao")->where(array("id" => $data["id"]))->save($data);

				if ($update) {
					$this->success("红包修改成功", U("DirectHongbao/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("修改红包失败");
				}
			}
		}
		else {
			if ($_GET["id"] != "") {
				$set = M("directhongbao")->where(array("id" => (int) $_GET["id"]))->find();
				$this->assign("set", $set);
			}

			$groups = M("Wechat_group")->where(array("token" => $this->token))->order("id ASC")->select();
			$this->assign("groups", $groups);
			$this->assign("token", $this->token);
			$this->display();
		}
	}

	public function send_hongbao()
	{
		$successcount = 0;
		$requestcount = 0;
		$temp = true;
		$token = $this->_get("token", "trim");
		$id = $this->_get("id", "intval");
		if (($id == "") || ($token == "")) {
			$this->error("参数错误");
		}

		$hongbao = M("directhongbao")->where(array("id" => $id))->find();
		$allnums = M("directhongbao")->sum("totalnums");
		$lastsendtime = M("directhongbao")->order("lastsendtime desc")->getField("lastsendtime");

		if (empty($hongbao)) {
			$this->error("红包不存在");
		}

		$openid = array();

		if ($hongbao["send_type"] == 1) {
			$total_count = M("wechat_group_list")->where(array("g_id" => $hongbao["gid"], "token" => $token))->count();
			$openid = M("wechat_group_list")->where(array("g_id" => $hongbao["gid"], "token" => $token))->field("openid,nickname")->select();
		}
		else if ($hongbao["send_type"] == 2) {
			$fans_id = explode(",", trim($hongbao["fans_id"], ","));
			$nickname = explode(",", trim($hongbao["fans_name"], ","));

			foreach ($fans_id as $k => $v ) {
				$openid[$k]["openid"] = $v;
				$openid[$k]["nickname"] = $nickname[$k];
			}

			$total_count = count($openid);
		}
		else {
			$total_count = M("wechat_group_list")->where(array("token" => $token))->count();
			$openid = M("wechat_group_list")->where(array("token" => $token))->field("openid,nickname")->select();
		}

		$totalmoney = $hongbao["min_money"] * $total_count;

		if ($hongbao["total_money"] < $totalmoney) {
			$this->error("总金额不足，请减少粉丝发送量");
		}

		if (1000 < $total_count) {
			$this->error("粉丝数量大于1000,请分批次发送");
		}

		if ((time() - $lastsendtime) < 60) {
			if (1799 <= $allnums) {
				$this->error("频率受限制");
			}
		}
		else {
			M("directhongbao")->where(array("id" => $id))->save(array("totalnums" => 0));
		}

		if (!empty($openid)) {
			foreach ($openid as $key => $val ) {
				$status = $this->request($val, $hongbao);
				if (($status["status"] == "SUCCESS") && $status) {
					$successcount++;
					$requestcount++;
				}
				else {
					$temp = false;
					$requestcount++;
					break;
				}
			}

			if (0 < $requestcount) {
				M("directhongbao")->where(array("id" => $id))->save(array(
	"totalnums"    => array("exp", "totalnums+" . $requestcount),
	"lastsendtime" => time(),
	"send_status"  => 1
	));
			}

			if (!$temp) {
				if ($successcount == 0) {
					$this->error($status["msg"]);
				}
				else {
					$this->error("发送成功了" . $successcount . "个,但余额不足剩余粉丝不能发送");
				}
			}
			else {
				$this->success("红包发送成功,请到领取记录中查看粉丝领取情况", U("DirectHongbao/index", array("token" => $this->token)));
				exit();
			}
		}
		else {
			$this->error("没有要发送的粉丝");
		}
	}

	public function receive_recorde()
	{
		$hid = $this->_get("hid", "intval");

		if ($hid == "") {
			$this->error("参数错误");
		}

		$directhongbao_record = M("directhongbao_record");
		$search_word = $this->_request("search_word", trim);
		$where = array("token" => $this->token, "hid" => $hid);

		if (!empty($search_word)) {
			$where["mch_billno|fans_nickname"] = array("like", "%" . $search_word . "%");
		}

		$total = $directhongbao_record->where($where)->count();
		$page = new Page($total, 15);
		$list = $directhongbao_record->where($where)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();
		$this->assign("list", $list);
		$this->assign("token", $this->token);
		$this->assign("hid", $hid);
		$this->assign("search_word", $search_word);
		$this->assign("page", $page->show());
		$this->display();
	}

	public function record_details()
	{
		$id = $this->_get("id", "intval");
		$hid = $this->_get("hid", "intval");
		$token = $this->_get("token", "trim");
		$page = ($_GET["page"] ? $this->_get("page", "intval") : 1);
		$type = $this->_get("type", "intval");
		$directhongbao_record = M("directhongbao_record")->where(array("id" => $id))->find();
		if (($directhongbao_record["mch_billno"] == "") || empty($directhongbao_record)) {
			$this->error("操作失败");
		}

		if (($type == 1) && (S($directhongbao_record["mch_billno"] . "_record") != "")) {
			$record_details = S($directhongbao_record["mch_billno"] . "_record");
		}
		else {
			$config["mch_billno"] = $directhongbao_record["mch_billno"];
			$config["token"] = $this->token;
			$hb = new Hongbao($config);
			$record_details = json_decode($hb->hongbao_record(), true);

			if ($record_details["status"] == "SUCCESS") {
				S($directhongbao_record["mch_billno"] . "_record", $record_details);
			}
		}

		if (($record_details["status"] == "SUCCESS") && ($record_details["msg"]["hb_type"] == "GROUP")) {
			if (!empty($record_details["msg"]["hblist"]["hbinfo"])) {
				if (!empty($record_details["msg"]["hblist"]["hbinfo"][0])) {
					$hblist = $record_details["msg"]["hblist"]["hbinfo"];
					$total = count($hblist);
					$slice_hblist = $this->page_array(10, $page, $hblist);

					foreach ($slice_hblist as $key => $val ) {
						$nickname = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $val["openid"]))->getField("wechaname");

						if ($nickname != "") {
							$name = $nickname;
						}
						else {
							$get_nickname = $this->get_nickname($val["openid"]);
							$name = ($get_nickname ? $get_nickname : "匿名用户");
						}

						$slice_hblist[$key]["nickname"] = $name;
					}

					$page = $this->show_array(ceil($total / 10), U("DirectHongbao/record_details", array("id" => $id, "token" => $token)));
					$this->assign("page", $page);
					$this->assign("slice_hblist", $slice_hblist);
				}
				else {
					$hblist = $record_details["msg"]["hblist"]["hbinfo"];
					$slice_hblist[0]["nickname"] = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $hblist["openid"]))->getField("wechaname");
					$slice_hblist[0]["openid"] = $hblist["openid"];
					$slice_hblist[0]["amount"] = $hblist["amount"];
					$slice_hblist[0]["rcv_time"] = $hblist["rcv_time"];
					$slice_hblist[0]["status"] = $hblist["status"];
					$this->assign("slice_hblist", $slice_hblist);
				}
			}

			$this->assign("record_details", $record_details["msg"]);
			$this->assign("directhongbao_record", $directhongbao_record);
			$this->assign("hid", $hid);
			$this->display();
		}
		else {
			if (($record_details["status"] == "SUCCESS") && ($record_details["msg"]["hb_type"] == "NORMAL")) {
				$nickname = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $record_details["msg"]["openid"]))->getField("wechaname");

				if ($nickname != "") {
					$name = $nickname;
				}
				else {
					$get_nickname = $this->get_nickname($record_details["msg"]["openid"]);
					$name = ($get_nickname ? $get_nickname : "匿名用户");
				}

				$record_details["msg"]["nickname"] = $name;
				$this->assign("record_details", $record_details["msg"]);
				$this->assign("directhongbao_record", $directhongbao_record);
				$this->assign("hid", $hid);
				$this->display();
			}
			else {
				$this->error("获取详情失败", $record_details["return_msg"]);
			}
		}
	}

	public function del()
	{
		$id = (int) $this->_get("id");
		$where = array("id" => $id);
		$directhongbao = M("directhongbao")->where($where)->find();

		if ($directhongbao) {
			M("directhongbao")->where(array("id" => $id))->delete();
			$this->success("删除成功", U("DirectHongbao/index", array("token" => $this->token)));
			exit();
		}
		else {
			$this->error("非法操作");
		}
	}

	public function del_record()
	{
		$id = (int) $this->_get("id");
		$hid = (int) $this->_get("hid");
		$token = $this->_get("token", "trim");
		$where = array("id" => $id);
		$directhongbao_record = M("directhongbao_record")->where($where)->find();

		if ($directhongbao_record) {
			M("directhongbao_record")->where(array("id" => $id))->delete();
			$this->success("删除成功", U("DirectHongbao/receive_recorde", array("hid" => $hid, "token" => $token)));
			exit();
		}
		else {
			$this->error("非法操作");
			exit();
		}
	}

	private function request($fansinfo, $params)
	{
		if (empty($params) || ($fansinfo["openid"] == "") || ($fansinfo["nickname"] == "")) {
			return false;
		}

		$config = array();
		$config["send_name"] = $params["send_name"];
		$config["wishing"] = $params["wishing"];
		$config["act_name"] = $params["act_name"];
		$config["remark"] = $params["remark"];
		$config["token"] = $this->token;
		$config["openid"] = $fansinfo["openid"];
		$config["money"] = $this->rand_money($params["min_money"], $params["max_money"]);

		if ($params["hb_type"] == 1) {
			$config["nick_name"] = $params["send_name"];
			$hb = new Hongbao($config);
			$res = json_decode($hb->send(), true);
		}
		else if ($params["hb_type"] == 2) {
			$config["total_num"] = $params["group_nums"];
			$hb = new Hongbao($config);
			$res = json_decode($hb->FissionSend(), true);
		}
		else {
			return false;
		}

		if ($res["status"] == "SUCCESS") {
			$record = array();
			$record["hid"] = $params["id"];
			$record["mch_billno"] = $res["mch_billno"];
			$record["fans_id"] = $fansinfo["openid"];
			$record["fans_nickname"] = $fansinfo["nickname"];
			$record["money"] = $config["money"];
			$record["hb_type"] = $params["hb_type"];
			$record["token"] = $config["token"];
			M("directhongbao_record")->add($record);
		}

		return $res;
	}

	public function select_fans()
	{
		$name = $this->_post("name", "trim");
		$where = array("token" => $this->token);

		if ($name) {
			$where["nickname"] = array("like", "%" . $name . "%");
		}

		$count = M("Wechat_group_list")->where($where)->count();
		$page = new Page($count, 10);
		$list = M("Wechat_group_list")->where($where)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();
		$group_name = M("Wechat_group")->where(array("token" => $this->token))->field("name,wechatgroupid")->select();

		foreach ($group_name as $v ) {
			$group[$v["wechatgroupid"]] = $v["name"];
		}

		foreach ($list as $key => $val ) {
			if ($val["g_id"]) {
				$list[$key]["group_name"] = $group[$val["g_id"]];
			}
			else {
				$list[$key]["group_name"] = "未分组";
			}
		}

		$this->assign("list", $list);
		$this->assign("name", $name);
		$this->assign("page", $page->show());
		$this->display();
	}

	private function rand_money($min, $max)
	{
		$rand = $min + ((mt_rand() / mt_getrandmax()) * ($max - $min));
		return round($rand, 2);
	}

	public function get_nickname($openid)
	{
		if (empty($this->token) && ($openid == "")) {
			return false;
		}

		if (F($openid . "_dhbnickname") != "") {
			return F($openid . "_dhbnickname");
		}

		if (F($this->token . "_directhongbao") != "") {
			$access_token = F($this->token . "_directhongbao");
		}
		else {
			$wxUser = M("Wxuser")->where(array("token" => $this->token))->field("appid,appsecret")->find();

			if (empty($wxUser["appid"])) {
				return false;
			}

			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($wxUser["appid"]);
			F($this->token . "_directhongbao", $access_token);
		}

		if ($access_token) {
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?openid=" . $openid . "&access_token=" . $access_token;
			$classData = json_decode($this->curlGet($url), true);
			F($openid . "_dhbnickname", $classData["nickname"]);
			return $classData["nickname"];
		}
		else {
			return false;
		}
	}

	public function curlGet($url)
	{
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}

	public function page_array($count, $page, $array, $order)
	{
		$page = (empty($page) ? "1" : $page);
		$start = ($page - 1) * $count;

		if ($order == 1) {
			$array = array_reverse($array);
		}

		$totals = count($array);
		$countpage = ceil($totals / $count);
		$pagedata = array();
		$pagedata = array_slice($array, $start, $count);
		return $pagedata;
	}

	public function show_array($countpage, $url, $page)
	{
		$page = (empty($page) ? 1 : $page);

		if (1 < $page) {
			$uppage = $page - 1;
		}
		else {
			$uppage = 1;
		}

		if ($page < $countpage) {
			$nextpage = $page + 1;
		}
		else {
			$nextpage = $countpage;
		}

		$str = "<div style=\"border:1px; width:300px; height:30px; color:#9999CC\">";
		$str .= "<span>共  $countpage  页 / 第 $page 页</span>";
		$str .= "<span><a href='$url&page=1&type=1'>   首页  </a></span>";
		$str .= "<span><a href='$url&page=$uppage&type=1'> 上一页  </a></span>";
		$str .= "<span><a href='$url&page=$nextpage&type=1'>下一页  </a></span>";
		$str .= "<span><a href='$url&page=$countpage&type=1'>尾页  </a></span>";
		$str .= "</div>";
		return $str;
	}
}


?>
