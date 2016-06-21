<?php

class ShakeLotteryAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction("ShakeLottery");
	}

	public function index()
	{
		$where = array("token" => $this->token);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["action_name|keyword"] = array("like", "%" . $search_word . "%");
		}

		$total = M("shakelottery")->where($where)->count();
		$Page = new Page($total, 10);
		$list = M("shakelottery")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("id desc")->select();
		$this->assign("list", $list);
		$this->assign("page", $Page->show());
		$this->assign("search_word", $search_word);
		$this->display();
	}

	public function AddShakeLottery()
	{
		if (IS_POST) {
			if ($_POST["action_name"] == "") {
				$this->error("活动名称不能为空");
			}

			if ($_POST["keyword"] == "") {
				$this->error("活动关键词不能为空");
			}

			if ($_POST["reply_title"] == "") {
				$this->error("活动回复标题不能为空");
			}

			if ($_POST["reply_content"] == "") {
				$this->error("活动回复内容不能为空");
			}

			if ($_POST["reply_pic"] == "") {
				$this->error("活动回复图片不能为空");
			}

			if ($_POST["remind_word"] == "") {
				$this->error("广告语不能为空");
			}

			if ($_POST["remind_link"] == "") {
				$this->error("广告跳转地址不能为空");
			}

			if (!is_numeric($_POST["totaltimes"]) || ((int) $_POST["totaltimes"] < 0)) {
				$this->error("总共摇奖次数请输入大于0的整数");
			}

			if (!is_numeric($_POST["everydaytimes"])) {
				$this->error("每人每天摇奖次数请输入整数");
			}

			if (!is_numeric($_POST["join_number"])) {
				$this->error("预计参与人数请输入整数");
			}
			else if ($_POST["join_number"] == "") {
				$this->error("预计参与人数不能为空");
			}

			if ((int) $_POST["totaltimes"] < (int) $_POST["everydaytimes"]) {
				$this->error("每人每天摇奖次数不能大于总共摇奖次数");
			}

			if ($_POST["starttime"] == "") {
				$this->error("活动开始时间不能为空");
			}

			if ($_POST["endtime"] == "") {
				$this->error("活动结束时间不能为空");
			}

			if ($_POST["endtime"] < $_POST["starttime"]) {
				$this->error("活动开始时间不能大于活动结束时间");
			}

			if (strpos($_POST["reply_pic"], "http") === false) {
				$_POST["reply_pic"] = $this->siteUrl . $_POST["reply_pic"];
			}

			$data = array();
			$data["action_name"] = trim($_POST["action_name"]);
			$data["reply_title"] = trim($_POST["reply_title"]);
			$data["reply_content"] = trim($_POST["reply_content"]);
			$data["reply_pic"] = trim($_POST["reply_pic"]);
			$data["keyword"] = trim($_POST["keyword"]);
			$data["action_desc"] = trim($_POST["action_desc"]);
			$data["remind_word"] = trim($_POST["remind_word"]);
			$data["remind_link"] = trim($_POST["remind_link"]);
			$data["totaltimes"] = (int) $_POST["totaltimes"];
			$data["everydaytimes"] = (int) $_POST["everydaytimes"];
			$data["starttime"] = strtotime($_POST["starttime"]);
			$data["endtime"] = strtotime($_POST["endtime"]);
			$data["timespan"] = (int) $_POST["timespan"];
			$data["join_number"] = (int) $_POST["join_number"];
			$data["record_nums"] = (int) $_POST["record_nums"];
			$data["is_limitwin"] = (int) $_POST["is_limitwin"];
			$data["is_follow"] = (int) $_POST["is_follow"];
			$data["is_register"] = (int) $_POST["is_register"];
			$data["status"] = (int) $_POST["status"];
			$data["custom_sharetitle"] = trim($_POST["custom_sharetitle"]);
			$data["custom_sharedsc"] = trim($_POST["custom_sharedsc"]);
			$data["follow_msg"] = trim($_POST["follow_msg"]);
			$data["follow_btn_msg"] = trim($_POST["follow_btn_msg"]);
			$data["register_msg"] = trim($_POST["register_msg"]);
			$data["custom_follow_url"] = trim($_POST["custom_follow_url"]);
			$id = $this->_post("id", "intval");

			if (!$id) {
				$data["token"] = $this->token;
				$addid = M("shakelottery")->add($data);

				if ($addid) {
					$this->handleKeyword($addid, "ShakeLottery", $data["keyword"]);
					$this->success("活动添加成功", U("ShakeLottery/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("活动添加失败");
				}
			}
			else {
				$edit = M("shakelottery")->where(array("id" => $id))->save($data);

				if ($edit) {
					$this->handleKeyword($id, "ShakeLottery", $data["keyword"]);
					S($this->token . "_shakelottery_" . $id, NULL);
					$this->success("活动修改成功", U("ShakeLottery/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("活动修改失败");
				}
			}
		}
		else {
			if ($_GET["id"]) {
				$ShakeLottery = M("shakelottery")->where(array("id" => (int) $_GET["id"]))->find();
				$this->assign("set", $ShakeLottery);
			}

			$this->display();
		}
	}

	public function DelShakeLottery()
	{
		$id = (int) $_GET["id"];
		$del = M("shakelottery")->where(array("id" => $id))->delete();

		if ($del) {
			$this->handleKeyword($id, "ShakeLottery", "", "", 1);
			M("shakelottery_record")->where(array("aid" => $id))->delete();
			M("shakelottery_prize")->where(array("aid" => $id))->delete();
			S($this->token . "_shakelottery_" . $id, NULL);
			$this->success("删除成功", U("ShakeLottery/index", array("token" => $this->token)));
			exit();
		}
		else {
			$this->error("删除失败");
		}
	}

	public function PrizeList()
	{
		$aid = $this->_get("aid", "intval");
		$token = $this->_get("token", "trim");
		$where = array("aid" => $aid, "token" => $this->token);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["prizename"] = array("like", "%" . $search_word . "%");
		}

		$total = M("shakelottery_prize")->where($where)->count();
		$Page = new Page($total, 10);
		$list = M("shakelottery_prize")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("prizenum asc")->select();
		$this->assign("list", $list);
		$this->assign("page", $Page->show());
		$this->assign("aid", $aid);
		$this->assign("search_word", $search_word);
		$this->assign("winxintype", $this->wxuser["winxintype"]);
		$this->display();
	}

	public function AddEditPrize()
	{
		if (IS_POST) {
			$type = ($_POST["type"] ? (int) $_POST["type"] : 1);

			if ($_POST["aid"] == "") {
				$this->error("活动ID不能为空");
			}

			if ($_POST["prizename"] == "") {
				$this->error("奖品名称不能为空");
			}

			if (($_POST["prizeimg"] == "") && ($type == 1)) {
				$this->error("请上传奖品图片");
			}

			if ($_POST["provider"] == "") {
				$this->error("奖品提供商不能为空");
			}

			if (!is_numeric($_POST["prizenum"]) || ((int) $_POST["prizenum"] < 0)) {
				$this->error("奖品数量请输入大于0的整数");
			}

			$data = array();
			$data["prizename"] = trim($_POST["prizename"]);
			$data["provider"] = trim($_POST["provider"]);
			$data["prizenum"] = intval($_POST["prizenum"]);

			if ($type == 2) {
				if ($_POST["wishing"] == "") {
					$this->error("红包祝福语音不能为空");
				}

				if (($_POST["money"] == "") || !is_numeric($_POST["money"])) {
					$this->error("红包金额请输入数字");
				}

				if (($_POST["hb_type"] == 2) && ((int) $_POST["group_nums"] < 0)) {
					$this->error("裂变人数请输入大于0的整数");
				}

				$hongbao_configure = serialize(array("wishing" => trim($_POST["wishing"]), "money" => $_POST["money"], "hb_type" => (int) $_POST["hb_type"], "group_nums" => (int) $_POST["group_nums"]));
				$data["hongbao_configure"] = $hongbao_configure;
				$data["prizeimg"] = $this->siteUrl . "/tpl/static/shakelottery/images/registerbg.jpg";
			}
			else {
				$data["prizeimg"] = trim($_POST["prizeimg"]);
			}

			$data["prize_type"] = $type;
			$id = intval($_POST["id"]);

			if (!$id) {
				$data["aid"] = intval($_POST["aid"]);
				$data["token"] = $this->token;
				$prizeid = M("shakelottery_prize")->add($data);

				if ($prizeid) {
					$this->success("奖品添加成功", U("ShakeLottery/PrizeList", array("aid" => $data["aid"], "token" => $this->token)));
					exit();
				}
				else {
					$this->error("奖品删除失败");
				}
			}
			else {
				$editprize = M("shakelottery_prize")->where(array("id" => $id))->save($data);

				if ($editprize) {
					$this->success("奖品修改成功", U("ShakeLottery/PrizeList", array("aid" => intval($_POST["aid"]), "token" => $this->token)));
					exit();
				}
				else {
					$this->error("奖品修改失败");
				}
			}
		}
		else {
			if ($_GET["id"]) {
				$prizeinfo = M("shakelottery_prize")->where(array("id" => (int) $_GET["id"]))->find();

				if ($prizeinfo["prize_type"] == 2) {
					$hongbao = unserialize($prizeinfo["hongbao_configure"]);
					$this->assign("hongbao", $hongbao);
				}

				$this->assign("set", $prizeinfo);
			}

			$type = ($_GET["type"] ? (int) $_GET["type"] : 1);
			$this->assign("type", $type);
			$this->assign("aid", (int) $_GET["aid"]);
			$this->display();
		}
	}

	public function DelPrize()
	{
		$id = (int) $_GET["id"];
		$aid = (int) $_GET["aid"];
		$del = M("shakelottery_prize")->where(array("id" => $id))->delete();

		if ($del) {
			$this->success("删除奖品成功", U("ShakeLottery/PrizeList", array("aid" => $aid, "token" => $this->token)));
			exit();
		}
		else {
			$this->error("删除奖品失败");
		}
	}

	public function ShakeLotteryRecord()
	{
		$token = $this->_get("token", "trim");
		$action_id = $this->_get("aid", "intval");
		$prize_type = ($_GET["prize_type"] ? (int) $_GET["prize_type"] : 1);
		$where = array("token" => $token, "aid" => $action_id);
		$search_word = $this->_post("search_word", "trim");
		if (($this->_get("type") == "win") || ($this->_get("type") == "hongbao") || ($this->_get("type") == "")) {
			$where["iswin"] = 1;
			$where["prize_type"] = $prize_type;
		}
		else {
			$where["iswin"] = array("neq", 1);
		}

		if (!empty($search_word)) {
			$where["wecha_name"] = array("like", "%" . $search_word . "%");
		}

		$total = M("shakelottery_record")->where($where)->count();
		$Page = new Page($total, 10);
		$list = M("shakelottery_record")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("shaketime desc")->select();
		$this->assign("list", $list);
		$this->assign("token", $token);
		$this->assign("id", $action_id);
		$this->assign("page", $Page->show());
		$this->assign("firstRow", $Page->firstRow);
		$this->assign("search_word", $search_word);
		$this->assign("type", $this->_get("type"));
		$this->assign("prize_type", $prize_type);
		$this->assign("winxintype", $this->wxuser["winxintype"]);

		if ($prize_type == 2) {
			$this->display("ShakeLotteryHbRecord");
		}
		else {
			$this->display();
		}
	}

	public function EditRecord()
	{
		if (IS_POST) {
			$isaccept = $this->_post("isaccept", "intval");
			$id = $this->_post("id", "intval");
			$aid = $this->_post("aid", "intval");
			$editresult = M("shakelottery_record")->where(array("id" => $id))->save(array("isaccept" => $isaccept, "accepttime" => time()));

			if ($editresult) {
				$this->success("修改领取状态成功", U("ShakeLottery/ShakeLotteryRecord", array("aid" => $aid, "token" => $this->token)));
			}
			else {
				$this->error("修改状态失败");
			}
		}
		else {
			if (($_GET["aid"] == "") || ($_GET["id"] == "")) {
				$this->error("参数错误");
			}

			$shakelottery_record = M("shakelottery_record")->where(array("id" => (int) $_GET["id"]))->find();
			$this->assign("set", $shakelottery_record);
			$this->assign("aid", (int) $_GET["aid"]);
			$this->display();
		}
	}

	public function DelRecord()
	{
		$id = (int) $_GET["id"];
		$aid = (int) $_GET["aid"];
		$type = ($_GET["type"] ? trim($_GET["type"]) : "win");
		$del = M("shakelottery_record")->where(array("id" => $id))->delete();

		if ($del) {
			$this->success("领奖记录删除成功", U("ShakeLottery/ShakeLotteryRecord", array("aid" => $aid, "token" => $this->token, "type" => $type)));
			exit();
		}
		else {
			$this->error("领奖记录删除失败");
		}
	}

	public function record_details()
	{
		$cid = $this->_get("id", "intval");
		$aid = $this->_get("aid", "intval");
		$token = $this->_get("token", "trim");
		$page = ($_GET["page"] ? $this->_get("page", "intval") : 1);
		$type = $this->_get("type", "intval");
		$shakelottery_hbrecord = M("shakelottery_hbrecord")->where(array("aid" => $aid))->find();
		if (($shakelottery_hbrecord["mch_billno"] == "") || empty($shakelottery_hbrecord)) {
			$this->error("操作失败");
		}

		if (($type == 1) && (S($shakelottery_hbrecord["mch_billno"] . "_shakerecord") != "")) {
			$record_details = S($shakelottery_hbrecord["mch_billno"] . "_shakerecord");
		}
		else {
			$config["mch_billno"] = $shakelottery_hbrecord["mch_billno"];
			$config["token"] = $this->token;
			$hb = new Hongbao($config);
			$record_details = json_decode($hb->hongbao_record(), true);

			if ($record_details["status"] == "SUCCESS") {
				S($shakelottery_hbrecord["mch_billno"] . "_shakerecord", $record_details);
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

					$page = $this->show_array(ceil($total / 10), U("ShakeLottery/record_details", array("id" => $id, "token" => $token)));
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
			$this->assign("shakelottery_hbrecord", $shakelottery_hbrecord);
			$this->assign("cid", $cid);
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
				$this->assign("shakelottery_hbrecord", $shakelottery_hbrecord);
				$this->assign("cid", $cid);
				$this->display();
			}
			else {
				$this->error("获取详情失败", $record_details["return_msg"]);
			}
		}
	}

	public function del_record()
	{
		$id = (int) $this->_get("id");
		$aid = (int) $this->_get("aid");
		$token = $this->_get("token", "trim");
		$where = array("id" => $id);
		$shakelottery_hbrecord = M("shakelottery_hbrecord")->where($where)->find();

		if ($shakelottery_hbrecord) {
			M("shakelottery_hbrecord")->where(array("id" => $id))->delete();
			$this->success("删除成功", U("ShakeLottery/HongbaoRecord", array("aid" => $aid, "token" => $token)));
			exit();
		}
		else {
			$this->error("非法操作");
			exit();
		}
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
