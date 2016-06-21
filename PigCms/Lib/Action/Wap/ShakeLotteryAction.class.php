<?php

class ShakeLotteryAction extends LotteryPrizeBaseAction
{
	public function index()
	{
		if (($this->action_id == "") || ($this->token == "")) {
			$this->error("非法操作");
		}

		if (S($this->token . "_shakelottery_" . $this->action_id) != "") {
			$actioninfo = S($this->token . "_shakelottery_" . $this->action_id);
		}
		else {
			$actioninfo = M("shakelottery")->where(array("id" => $this->action_id))->find();

			if (!empty($actioninfo)) {
				S($this->token . "_shakelottery_" . $this->action_id, $actioninfo);
			}
		}

		if (empty($actioninfo)) {
			$this->error("活动不存在");
		}
		else if ($actioninfo["status"] == 0) {
			$this->error("活动已经关闭");
		}

		$userinfo = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->find();
		$prize = M("shakelottery_prize")->where(array("aid" => $this->action_id, "token" => $this->token))->select();
		$stat = $this->public_notice($actioninfo, $userinfo["tel"]);
		$user_id = $this->AddPlayer($stat, $userinfo);
		$this->clear_shake_day(array("token" => $this->token, "action_id" => $this->action_id, "wecha_id" => $this->wecha_id));
		$this->assign("prize", $prize);
		$this->assign("user_id", $user_id);
		if ((strpos($actioninfo["remind_link"], "{siteUrl}") !== false) || (strpos($actioninfo["remind_link"], "{wechat_id}") !== false)) {
			$actioninfo["remind_link"] = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $actioninfo["remind_link"]);
		}

		$actioninfo["custom_sharetitle"] = ($actioninfo["custom_sharetitle"] != "" ? str_replace("{{活动名称}}", $actioninfo["action_name"], $actioninfo["custom_sharetitle"]) : "我正在参加“" . $actioninfo["action_name"] . "”活动，快来参加轻松赢取丰厚奖品吧！");
		$actioninfo["custom_sharedsc"] = ($actioninfo["custom_sharedsc"] != "" ? str_replace("{{活动名称}}", $actioninfo["action_name"], $actioninfo["custom_sharedsc"]) : $actioninfo["reply_content"]);
		$this->assign("actioninfo", $actioninfo);
		$this->display();
	}

	private function AddPlayer($stat, $userinfo)
	{
		if ($this->wecha_id != "") {
			$player = M("shakelottery_users")->where(array("aid" => $this->action_id, "token" => $this->token, "wecha_id" => $this->wecha_id))->find();
			if (empty($player) && ($stat == true)) {
				$data = array();
				$data["aid"] = $this->action_id;
				$data["total_shakes"] = 0;
				$data["today_shakes"] = 0;
				$data["wecha_id"] = $this->wecha_id;
				$data["wecha_name"] = (!empty($userinfo["wechaname"]) ? $userinfo["wechaname"] : "匿名用户");
				$data["wecha_pic"] = (!empty($userinfo["portrait"]) ? $userinfo["portrait"] : $this->siteUrl . "/tpl/User/default/common/images/portrait.jpg");
				$data["phone"] = (!empty($userinfo["tel"]) ? $userinfo["tel"] : "no");
				$data["addtime"] = $_SERVER["REQUEST_TIME"];
				$data["token"] = $this->token;
				$addid = M("shakelottery_users")->add($data);
				return $addid;
			}
			else {
				if (!empty($player) && ($stat == true)) {
					$savedata = array("phone" => $userinfo["tel"], "wecha_pic" => $userinfo["portrait"], "wecha_name" => $userinfo["wechaname"]);
					M("shakelottery_users")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "aid" => $this->action_id))->save($savedata);
					return $player["id"];
				}
			}
		}
	}

	public function AjaxReturnPrize()
	{
		$id = (int) $_GET["id"];
		$token = trim($_GET["token"]);
		if (($id != "") && ($token != "")) {
			$actioninfo = M("shakelottery")->where(array("id" => $id))->find();

			if (empty($actioninfo)) {
				echo "{\"status\":\"errormsg\",\"msg\":\"抽奖活动不存在\"}";
				exit();
			}
			else if ($actioninfo["status"] == 0) {
				echo "{\"status\":\"errormsg\",\"msg\":\"抽奖活动未开启\"}";
				exit();
			}

			if ($_SERVER["REQUEST_TIME"] < $actioninfo["starttime"]) {
				echo "{\"status\":\"errormsg\",\"msg\":\"抽奖活动未开始,请注意页面的倒计时\"}";
				exit();
			}
			else if ($actioninfo["endtime"] < $_SERVER["REQUEST_TIME"]) {
				echo "{\"status\":\"errormsg\",\"msg\":\"抽奖活动已结束\"}";
				exit();
			}

			$player = M("shakelottery_users")->where(array("aid" => $id, "wecha_id" => $this->wecha_id))->find();

			if (empty($player)) {
				echo "{\"status\":\"errormsg\",\"msg\":\"抽奖失败\"}";
				exit();
			}

			$todaytime = strtotime(date("Y-m-d 00:00:00", $_SERVER["REQUEST_TIME"]));
			$lottery_record = M("shakelottery_record")->where(array(
	"aid"       => $id,
	"user_id"   => $player["id"],
	"iswin"     => 1,
	"shaketime" => array("gt", $todaytime)
	))->order("shaketime desc")->find();
			$totay_lottery_count = M("shakelottery_record")->where(array(
	"aid"       => $id,
	"user_id"   => $player["id"],
	"iswin"     => 1,
	"shaketime" => array("gt", $todaytime)
	))->count();
			$lastshaketime = M("shakelottery_record")->where(array("aid" => $id, "user_id" => $player["id"]))->order("shaketime desc")->getField("shaketime");

			if (($_SERVER["REQUEST_TIME"] - $lastshaketime) < 1) {
				echo "{\"status\":\"timelimit\",\"msg\":\"\"}";
				exit();
			}

			if ((0 < $actioninfo["is_limitwin"]) && ($actioninfo["is_limitwin"] <= $totay_lottery_count)) {
				echo "{\"status\":\"errormsg\",\"msg\":\"您今天的中奖次数超限，请明天再来吧\"}";
				exit();
			}

			if ((0 < $actioninfo["everydaytimes"]) && ($actioninfo["everydaytimes"] <= $player["today_shakes"])) {
				echo "{\"status\":\"errormsg\",\"msg\":\"您今天的摇奖次数已经超限,请明天再来吧\"}";
				exit();
			}

			if ($actioninfo["totaltimes"] <= $player["total_shakes"]) {
				echo "{\"status\":\"errormsg\",\"msg\":\"您的摇奖次数已经用完\"}";
				exit();
			}

			if ((0 < $actioninfo["timespan"]) && ((time() - $lottery_record["shaketime"]) < ($actioninfo["timespan"] * 60))) {
				$prize = $this->LotteryPrize(true);
			}
			else {
				$prize = $this->LotteryPrize(false);
			}

			$iswin = ($prize["status"] == "success" ? 1 : 0);
			$data = array();

			if ($iswin == 1) {
				$data["prizeid"] = $prize["msg"]["id"];
				$data["prize_type"] = $prize["msg"]["prize_type"];
				$data["prizename"] = $prize["msg"]["prizename"];
			}

			$data["iswin"] = $iswin;
			$data["aid"] = $id;
			$data["user_id"] = $player["id"];
			$data["shaketime"] = $_SERVER["REQUEST_TIME"];
			$data["isaccept"] = 0;
			$data["accepttime"] = 0;
			$data["phone"] = $player["phone"];
			$data["wecha_name"] = $player["wecha_name"];
			$data["token"] = $token;
			$record_add = M("shakelottery_record")->add($data);
			$player_update = M("shakelottery_users")->where(array("aid" => $id, "wecha_id" => $this->wecha_id))->save(array(
	"total_shakes" => array("exp", "total_shakes+1"),
	"today_shakes" => array("exp", "today_shakes+1")
	));
			M("shakelottery")->where(array("id" => $id))->setInc("actual_join_number");
			if ($record_add && $player_update) {
				if ($iswin == 1) {
					if ($prize["msg"]["prize_type"] == 2) {
						$status = $this->SendHongbao($prize["msg"], $record_add, $player["wecha_name"]);
						if (($status["status"] == "SUCCESS") && $status) {
							echo "{\"status\":\"success\",\"prizename\":\"" . $prize["msg"]["prizename"] . "\",\"prizeimg\":\"" . $prize["msg"]["prizeimg"] . "\"}";
							exit();
						}
						else {
							M("shakelottery_record")->where(array("id" => $record_add))->save(array("iswin" => 0, "prize_type" => 0, "prizename" => "", "prizeid" => 0));
							echo "{\"status\":\"error\",\"msg\":\"摇奖失败\"}";
							exit();
						}
					}
					else {
						echo "{\"status\":\"success\",\"prizename\":\"" . $prize["msg"]["prizename"] . "\",\"prizeimg\":\"" . $prize["msg"]["prizeimg"] . "\"}";
						exit();
					}
				}
				else {
					echo "{\"status\":\"error\",\"msg\":\"" . $prize["msg"] . "\"}";
					exit();
				}
			}
			else {
				echo "{\"status\":\"errormsg\",\"msg\":\"摇奖失败\"}";
				exit();
			}
		}
		else {
			$msg = "抽奖失败";
			echo "{\"status\":\"errormsg\",\"msg\":\"" . $msg . "\"}";
			exit();
		}
	}

	public function LotteryPrize($setting)
	{
		if ($setting) {
			return array("status" => "fail", "msg" => "继续努力哦");
		}

		$shakePrize = array();
		$shakePrize = M("shakelottery")->where(array("id" => $this->action_id))->find();
		$prize = M("shakelottery_prize")->where(array("aid" => $this->action_id, "token" => $this->token))->select();

		foreach ($prize as $k => $v ) {
			$prizenum[$k] = $v["prizenum"];
			$prizeArr[$v["id"]] = $v;
		}

		array_multisort($prizenum, SORT_ASC, $prize);
		$shakePrize["prizelist"] = $prize;
		$prizeid = $this->shakePrize($shakePrize);

		if ($prizeid == 0) {
			$res = array("status" => "fail", "msg" => "继续努力哦");
		}
		else {
			M("shakelottery_prize")->where(array("id" => $prizeid))->setInc("expendnum");
			$res = array("status" => "success", "msg" => $prizeArr[$prizeid]);
		}

		return $res;
	}

	public function LotteryMyRecord()
	{
		$aid = (int) $_POST["aid"];
		$user_id = (int) $_POST["user_id"];
		$token = trim($_POST["token"]);
		$myRecord = M("shakelottery_record")->where(array("aid" => $aid, "user_id" => $user_id, "iswin" => 1))->order("shaketime desc")->select();

		if (!empty($myRecord)) {
			$html = "";

			foreach ($myRecord as $key => $value ) {
				$html .= "<li>&nbsp;&nbsp;&nbsp;摇中" . mb_substr($value["prizename"], 0, 10, "UTF-8") . "<span class=\"font-c63535\">" . date("Y-m-d H:i:s", $value["shaketime"]) . "</span></li>";
			}

			echo $html;
			exit();
		}
		else {
			echo "fail";
			exit();
		}
	}

	public function LotteryRecord()
	{
		$aid = (int) $_POST["aid"];
		$user_id = (int) $_POST["user_id"];
		$token = trim($_POST["token"]);
		$record_nums = ($_GET["record_nums"] ? (int) $_GET["record_nums"] : 20);
		$otherRecord = M("shakelottery_record")->where(array(
	"aid"     => $aid,
	"user_id" => array("neq", $user_id),
	"iswin"   => 1
	))->limit(0, $record_nums)->order("shaketime desc")->select();

		if (!empty($otherRecord)) {
			$html = "";

			foreach ($otherRecord as $key => $value ) {
				$str = "";

				if ($value["phone"] == "") {
					$str = "<li>恭喜<label style=\"color:#c63535;\">" . $value["wecha_name"] . "</label>&nbsp;&nbsp;&nbsp;摇中" . mb_substr($value["prizename"], 0, 10, "UTF-8") . "</li>";
				}
				else {
					$str = "<li>恭喜<label style=\"color:#c63535;\">" . substr_replace($value["phone"], "****", 3, 4) . "</label>&nbsp;&nbsp;&nbsp;摇中" . mb_substr($value["prizename"], 0, 10, "UTF-8") . "</li>";
				}

				$html .= $str;
			}

			echo $html;
			exit();
		}
		else {
			echo "fail";
			exit();
		}
	}

	public function public_notice($action_info, $tel)
	{
		$stat = true;
		if (($action_info["is_follow"] == 0) && ($this->isSubscribe() == false)) {
			$follow_msg = (!empty($action_info["follow_msg"]) ? $action_info["follow_msg"] : "");
			$custom_url = (!empty($action_info["custom_follow_url"]) ? $action_info["custom_follow_url"] : "");
			$custom_btn_msg = (!empty($action_info["follow_btn_msg"]) ? $action_info["follow_btn_msg"] : "");
			$this->assign("notice_content", "no_follow");
			$this->memberNotice($follow_msg, 1, $custom_url, $custom_btn_msg);
			$stat = false;
		}
		else {
			if (($action_info["is_register"] == 1) && ($tel == "")) {
				$custom_register_msg = (!empty($action_info["register_msg"]) ? $action_info["register_msg"] : "");
				$this->assign("notice_content", "no_register");
				$this->memberNotice($custom_register_msg);
				$stat = false;
			}
			else {
				$this->assign("notice_content", "");
			}
		}

		return $stat;
	}

	private function clear_shake_day($cache_parameter)
	{
		$token = $cache_parameter["token"];
		$action_id = $cache_parameter["action_id"];
		$wecha_id = $cache_parameter["wecha_id"];
		if (($token != "") && ($action_id != "") && ($wecha_id != "")) {
			if (S($token . "_" . $action_id . "_" . $wecha_id . "_shakelottery_day") == "") {
				$today_time = strtotime(date("Y-m-d 00:00:00", $_SERVER["REQUEST_TIME"]));
				$evening_time = strtotime(date("Y-m-d 23:59:59", $_SERVER["REQUEST_TIME"]));
				$cache_time = $evening_time - $_SERVER["REQUEST_TIME"];
				$where = "aid = $action_id and token = '$token' and wecha_id = '$wecha_id'";
				$shakelottery_users = M("shakelottery_users")->where($where)->find();

				if (!empty($shakelottery_users)) {
					M("shakelottery_users")->where($where)->save(array("today_shakes" => 0));
				}

				S($token . "_" . $action_id . "_" . $wecha_id . "_shakelottery_day", 1, $cache_time);
			}
		}
	}

	private function SendHongbao($prize, $aid, $wecha_name)
	{
		$hongbao_configure = unserialize($prize["hongbao_configure"]);
		$config = array();
		$config["send_name"] = $prize["provider"];
		$config["wishing"] = $hongbao_configure["wishing"];
		$config["act_name"] = $prize["prizename"];
		$config["remark"] = $prize["prizename"];
		$config["token"] = $this->token;
		$config["openid"] = $this->wecha_id;
		$config["money"] = $hongbao_configure["money"];

		if ($hongbao_configure["hb_type"] == 1) {
			$config["nick_name"] = $prize["provider"];
			$hb = new Hongbao($config);
			$res = json_decode($hb->send(), true);
		}
		else if ($hongbao_configure["hb_type"] == 2) {
			$config["total_num"] = $hongbao_configure["group_nums"];
			$hb = new Hongbao($config);
			$res = json_decode($hb->FissionSend(), true);
		}
		else {
			return false;
		}

		if ($res["status"] == "SUCCESS") {
			$record = array();
			$record["aid"] = $aid;
			$record["mch_billno"] = $res["mch_billno"];
			$record["fans_id"] = $this->wecha_id;
			$record["fans_nickname"] = $wecha_name;
			$record["money"] = $hongbao_configure["money"];
			$record["hb_type"] = $hongbao_configure["hb_type"];
			$record["token"] = $config["token"];
			M("shakelottery_hbrecord")->add($record);
		}

		return $res;
	}
}


?>
