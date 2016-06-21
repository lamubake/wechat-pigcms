<?php

class HongbaoAction extends WapAction
{
	public $action_id;
	public $my_packets;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->action_id = $this->_get("id", "intval");
		$this->my_packets = M("hongbao_grabber")->where("hongbao_id = $this->action_id and token = '$this->token' and grabber_wechaid = '$this->wecha_id'")->find();
	}

	public function index()
	{
		if ($this->action_id == "") {
			$this->error("参数错误");
		}

		$cache_action = S($this->token . "_" . $this->action_id . "_hongbao");

		if ($cache_action != "") {
			$action = $cache_action;
		}
		else {
			$action = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token))->find();
			S($this->token . "_" . $this->action_id . "_hongbao", $action);
		}

		if (empty($action)) {
			$this->error("活动不存在！");
		}
		else if ($action["status"] != 1) {
			$this->error("活动未开启！");
		}

		$share_times = M("hongbao_share")->where(array("hongbao_id" => $this->action_id, "share_key" => $this->my_packets["grabber_shareid"], "is_opened" => 2))->count();
		if (($action["sharetimes"] <= $share_times) && ($this->my_packets["isgrabbed"] == 1)) {
			header("Location:/index.php?g=Wap&m=Hongbao&a=my_wallet&id=" . $this->action_id . "&token=" . $this->token . "&share_key=" . $this->my_packets["grabber_shareid"] . "&wecha_id=" . $this->wecha_id);
			exit();
		}

		if (!empty($this->my_packets) && ($this->my_packets["isgrabbed"] == 1)) {
			header("Location:/index.php?g=Wap&m=Hongbao&a=packets&id=" . $this->action_id . "&token=" . $this->token . "&wecha_id=" . $this->wecha_id);
			exit();
		}

		if (!empty($this->my_packets) && ($this->my_packets["isgrabbed"] == 2)) {
			header("Location:/index.php?g=Wap&m=Hongbao&a=my_wallet&id=" . $this->action_id . "&token=" . $this->token);
			exit();
		}

		$this->notice($action);

		if ($this->wecha_id) {
			$data = array("hongbao_id" => (int) $this->action_id, "money" => 0, "grabber_nickname" => !empty($this->fans["wechaname"]) ? $this->fans["wechaname"] : "匿名", "grabber_headimgurl" => !empty($this->fans["portrait"]) ? $this->fans["portrait"] : $this->siteUrl . "tpl/User/default/common/images/portrait.jpg", "grabber_shareid" => $this->get_key(32), "grabber_wechaid" => $this->wecha_id, "grabber_sex" => !empty($this->fans["sex"]) ? $this->fans["sex"] : 0, "grabber_tel" => !empty($this->fans["tel"]) ? $this->fans["tel"] : "111111", "grabber_qq" => !empty($this->fans["qq"]) ? $this->fans["qq"] : "111111", "grabber_address" => !empty($this->fans["address"]) ? $this->fans["address"] : "qwert", "grabber_province" => !empty($this->fans["province"]) ? $this->fans["province"] : "ah", "grabber_city" => !empty($this->fans["city"]) ? $this->fans["city"] : "hf", "share_money" => 0, "grabber_time" => 0, "isgrabbed" => 0, "token" => $this->token);

			if (!empty($this->my_packets)) {
				unset($data["grabber_shareid"]);
				unset($data["money"]);
				unset($data["share_money"]);
				unset($data["grabber_time"]);
				unset($data["isgrabbed"]);
				unset($data["token"]);
				M("hongbao_grabber")->where(array("hongbao_id" => $this->action_id, "grabber_wechaid" => $this->wecha_id))->save($data);
			}
			else {
				$grabber_id = M("hongbao_grabber")->add($data);
				$_SESSION["grabber_id"] = $grabber_id;
			}
		}

		$this->assign($action);
		$this->assign("action_id", $this->action_id);
		$this->assign("token", $this->token);
		$total_grabber = M("hongbao_grabber")->where("isgrabbed != 0 and hongbao_id = $this->action_id")->count();
		$this->assign("total", $total_grabber);
		$this->assign($this->my_packets);
		$this->display();
	}

	public function grab_packet()
	{
		if ($this->action_id == "") {
			$this->error("参数错误");
		}

		$action = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token, "status" => "1"))->find();

		if (empty($action)) {
			$this->error("红包不存在！");
		}

		if (($action["need_follow"] == 1) && ($this->isSubscribe() == false)) {
			$this->error("请先关注然后再抢红包");
		}

		if (($action["need_phone"] == 1) && empty($this->fans["tel"])) {
			$this->error("请先注册然后抢红包");
		}

		if (time() < $action["start_time"]) {
			$this->error("抢红包活动还未开始!");
		}

		if ($action["end_time"] < time()) {
			$this->error("抢红包活动已经结束!");
		}

		$rand_money = $this->rand_money($action["min_money"], $action["max_money"]);

		if ($action["total_money"] <= $rand_money) {
			$this->error("红包已经被抢完了!");
		}

		if (!empty($this->my_packets) && ($this->my_packets["isgrabbed"] == 1)) {
			header("Location:/index.php?g=Wap&m=Hongbao&a=packets&id=" . $this->action_id . "&token=" . $this->token);
			exit();
		}

		if (!empty($this->my_packets) && ($this->my_packets["isgrabbed"] == 2)) {
			header("Location:/index.php?g=Wap&m=Hongbao&a=my_wallet&id=" . $this->action_id . "&token=" . $this->token);
			exit();
		}

		$d = array();
		$d["isgrabbed"] = 1;
		$d["money"] = array("exp", "money+" . $rand_money);
		$d["grabber_time"] = time();
		$where = array("hongbao_id" => $this->action_id, "token" => $this->token, "grabber_wechaid" => $this->wecha_id);
		$update_hongbao_grabber = M("hongbao_grabber")->where($where)->save($d);
		$hb_d["total_money"] = array("exp", "total_money-" . $rand_money);
		$update_hongbao = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token))->save($hb_d);
		if ($update_hongbao_grabber && $update_hongbao) {
			header("Location:/index.php?g=Wap&m=Hongbao&a=packets&id=" . $this->action_id . "&token=" . $this->token);
			exit();
		}
		else {
			$this->error("抢红包失败！");
		}
	}

	public function packets()
	{
		if (!$this->action_id || !$this->token) {
			$this->error("参数错误");
		}

		$action = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token, "status" => "1"))->find();
		$this->notice($action);
		$scode1 = M("hongbao_grabber")->where(array("grabber_id" => $_SESSION["grabber_id"]))->getField("grabber_shareid");
		$where_share = array("hongbao_id" => $this->action_id, "share_key" => $this->my_packets["grabber_shareid"], "is_opened" => 2);
		$my_share_times = M("hongbao_share")->where($where_share)->count();
		$this->assign("action_id", $this->action_id);
		$this->assign("my_share_times", $my_share_times);
		$this->assign("token", $this->token);
		$total_grabber = M("hongbao_grabber")->where("isgrabbed != 0 and hongbao_id = $this->action_id")->count();
		$this->assign("total", $total_grabber);
		$this->assign("grabber_shareid", $scode1);
		$this->assign($action);
		$this->assign($this->my_packets);
		$this->display();
	}

	public function find_helper()
	{
		if (!$this->action_id || !$this->token) {
			$this->error("参数错误");
		}

		$share_key = $this->_get("share_key", "trim");
		$action = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token, "status" => "1"))->find();

		if (empty($action)) {
			$this->error("没有该活动！");
		}
		else if (time() < $action["start_time"]) {
			$this->error("活动未开始！");
		}
		else if ($action["end_time"] < time()) {
			$this->error("活动已结束！");
		}

		$this->notice($action);
		$grabber = M("hongbao_grabber")->where(array("grabber_shareid" => $share_key))->find();
		$share = M("hongbao_share")->where(array("hongbao_id" => $this->action_id, "share_key" => $share_key, "share_wechaid" => $this->wecha_id))->find();
		if ($share_key && $grabber && ($grabber["grabber_wechaid"] != $this->wecha_id) && $this->wecha_id) {
			if (empty($share) || ($share["is_opened"] == 0)) {
				$share = array("hongbao_id" => $this->action_id, "share_key" => $share_key, "rand_money" => 0, "share_nickname" => !empty($this->fans["wechaname"]) ? $this->fans["wechaname"] : "as", "share_pic" => !empty($this->fans["portrait"]) ? $this->fans["portrait"] : $this->siteUrl . "tpl/User/default/common/images/portrait.jpg", "share_time" => time(), "is_opened" => 1, "share_wechaid" => $this->wecha_id);
				M("hongbao_share")->add($share);
			}
		}
		else {
			header("Location:/index.php?g=Wap&m=Hongbao&a=index&id=" . $this->action_id . "&token=" . $this->token . "&wecha_id=" . $this->wecha_id);
			exit();
		}

		$this->assign("action_id", $this->action_id);
		$this->assign("token", $this->token);
		$this->assign("share_key", $share_key);
		$this->assign("grabber", $grabber);
		$total_grabber = M("hongbao_grabber")->where("isgrabbed != 0 and hongbao_id = $this->action_id")->count();
		$this->assign("total", $total_grabber);
		$this->assign($action);
		$this->display();
	}

	public function do_helper()
	{
		if (!$this->action_id || !$this->token) {
			$this->error("参数错误");
		}

		$share_key = $this->_get("share_key", "trim");
		$action = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token, "status" => "1"))->find();

		if (empty($action)) {
			exit("没有该活动！");
		}
		else if (time() < $action["start_time"]) {
			exit("活动未开始！");
		}
		else if ($action["end_time"] < time()) {
			exit("活动已结束！");
		}

		if ($this->action_id && $this->token) {
			$share = M("hongbao_share")->where(array("hongbao_id" => $this->action_id, "share_key" => $share_key, "share_wechaid" => $this->wecha_id))->find();

			if (!empty($share)) {
				if ($share["is_opened"] == 1) {
					$pack = M("hongbao_grabber")->where(array("hongbao_id" => $this->action_id, "token" => $this->token, "grabber_shareid" => $share_key))->find();

					if ($pack["isgrabbed"] == 2) {
						exit("红包已经被领取");
					}

					$where_share = array("hongbao_id" => $this->action_id, "share_key" => $share_key, "is_opened" => 2);
					$my_share_times = M("hongbao_share")->where($where_share)->count();

					if ($action["sharetimes"] <= $my_share_times) {
						exit("合体次数已到,你也去抢吧");
					}

					$rand_money = $this->rand_money($action["min_money"], $action["max_money"]);

					if ($action["total_money"] < $rand_money) {
						exit("红包总金额不足,合体失败");
					}

					$u_share = M("hongbao_share")->where(array("hongbao_id" => $this->action_id, "token" => $this->token, "share_key" => $share_key, "share_wechaid" => $this->wecha_id))->save(array("add_money" => $rand_money, "is_opened" => 2));
					$data = array();
					$data["money"] = array("exp", "money+" . $rand_money);
					$u_grabber = M("hongbao_grabber")->where(array("hongbao_id" => $this->action_id, "token" => $this->token, "grabber_shareid" => $share_key))->save($data);
					$hb_d = array();
					$hb_d["total_money"] = array("exp", "total_money-" . $rand_money);
					$u_hongbao = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token))->save($hb_d);
					if ($u_grabber && $u_hongbao) {
						exit("合体成功,您帮他贡献了" . $rand_money . "元");
					}
					else {
						exit("合体失败");
					}
				}

				exit("请勿重复合体");
			}
			else {
				exit("分享码错误");
			}
		}
		else {
			exit("非法操作");
		}
	}

	public function my_wallet()
	{
		if (!$this->action_id || !$this->token) {
			$this->error("非法操作");
			exit();
		}

		$action = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token, "status" => "1"))->find();
		$this->notice($action);
		$this->assign("action_id", $this->action_id);
		$this->assign($action);
		$this->assign("token", $this->token);
		$this->assign($this->my_packets);
		$scode1 = M("hongbao_grabber")->where(array("grabber_id" => $_SESSION["grabber_id"]))->getField("grabber_shareid");
		$this->assign("share_key", $this->my_packets["grabber_shareid"]);
		$total_grabber = M("hongbao_grabber")->where("isgrabbed != 0 and hongbao_id = $this->action_id")->count();
		$this->assign("total", $total_grabber);
		$this->display();
	}

	public function get_wallet()
	{
		if ($this->wecha_id && $this->token && $this->action_id) {
			$action = M("hongbao")->where(array("id" => $this->action_id, "token" => $this->token, "status" => "1"))->find();
			$way = (int) $_GET["way"];
			$msg = ($way == 1 ? "领取" : "充值");
			$w_share = array("hongbao_id" => $this->action_id, "share_key" => $this->my_packets["grabber_shareid"], "is_opened" => 2);
			$share_times = M("hongbao_share")->where($w_share)->count();

			if ($share_times < $action["sharetimes"]) {
				exit("分享次数不够" . $action["sharetimes"] . "次,不能" . $msg);
			}

			$pack = M("hongbao_grabber")->where(array("hongbao_id" => $this->action_id, "token" => $this->token, "grabber_wechaid" => $this->wecha_id))->find();

			if (!empty($pack)) {
				if (($pack["money"] < 1) && ($way == 1)) {
					exit("红包金额需超过1.00元才可领取");
				}

				if ($pack["isgrabbed"] == 2) {
					exit("你已经" . $msg . ",请勿重复" . $msg);
				}

				if ($pack["isgrabbed"] != 2) {
					if ($way == 1) {
						$config = array();
						$config["nick_name"] = $action["reply_title"];
						$config["send_name"] = $action["reply_title"];
						$config["wishing"] = $action["reply_content"];
						$config["act_name"] = $action["action_name"];
						$config["remark"] = $action["action_desc"];
						$config["token"] = $this->token;
						$config["openid"] = $this->wecha_id;
						$config["money"] = $pack["money"];
						$hb = new Hongbao($config);
						$res = json_decode($hb->send(), true);
					}
					else {
						$return_recharge = $this->recharge($this->token, $pack["grabber_wechaid"], $pack["money"]);
						$res = json_decode($return_recharge, true);
					}
				}

				if ($res["status"] == "SUCCESS") {
					$save = M("hongbao_grabber")->where(array("hongbao_id" => $pack["hongbao_id"], "token" => $pack["token"], "grabber_wechaid" => $pack["grabber_wechaid"]))->save(array("isgrabbed" => 2));
					exit("success");
				}
				else {
					exit($res["msg"] . ",请稍后再试");
				}
			}
			else {
				exit("不存在你的红包");
			}
		}
		else {
			exit("非法操作");
		}
	}

	public function common_packets()
	{
		$w_share = array("hongbao_id" => $this->action_id, "share_key" => $this->my_packets["grabber_shareid"], "is_opened" => 2);
		$my_share = M("hongbao_share")->where($w_share)->order("add_money desc")->select();
		$add_money = M("hongbao_share")->where($w_share)->sum("add_money");
		$lucky_guys = M("hongbao_grabber")->where("hongbao_id = $this->action_id AND token = '$this->token' AND isgrabbed!=0")->order("money desc")->select();
		$this->assign("my_share", $my_share);
		$this->assign("lucky_guys", $lucky_guys);
		$this->assign("my_money", $this->my_packets["money"] - $add_money);
		$this->assign("my_packets", $this->my_packets);
		$this->display();
	}

	public function add_grabber($data)
	{
		if (!is_array($data) || empty($data)) {
			return false;
		}

		$grabber_id = M("hongbao_grabber")->add($data);

		if ($grabber_id) {
			return $grabber_id;
		}
		else {
			return false;
		}
	}

	private function rand_money1($min, $max)
	{
		$rand = mt_rand($min, $max) / mt_rand($min, $max + $min);
		return round($rand, 2);
	}

	private function rand_money($min, $max)
	{
		$rand = $min + ((mt_rand() / mt_getrandmax()) * ($max - $min));
		return round($rand, 2);
	}

	private function get_key($length)
	{
		$str = substr(md5(time() . mt_rand(1000, 9999)), 0, $length);
		return $str;
	}

	private function recharge($token, $wecha_id, $money)
	{
		if (empty($token) || empty($money) || empty($wecha_id)) {
			exit("token和充值金额不能为空");
		}

		$card_set = M("member_card_set")->where(array("token" => $token))->find();

		if (empty($card_set)) {
			exit("商家未设置会员卡");
		}

		$info = M("member_card_create")->where(array("token" => $token, "wecha_id" => $wecha_id))->find();

		if (empty($info)) {
			exit("用户未领取会员卡");
		}

		$userinfo = M("userinfo")->where(array("token" => $token, "wecha_id" => $wecha_id))->find();

		if (empty($userinfo)) {
			exit("您还没有关注,请先关注");
		}

		$where = array("token" => $info["token"], "wecha_id" => $info["wecha_id"], "cardid" => $info["cardid"]);
		$record_data = array("orderid" => date("YmdHis", time()) . mt_rand(1000, 9999), "ordername" => "合体红包的会员卡充值", "price" => $money, "createtime" => time(), "paytime" => time(), "cardid" => $info["cardid"], "wecha_id" => $info["wecha_id"], "token" => $info["token"], "module" => "Card", "type" => 1, "paid" => 1);
		$data = array();
		$data["balance"] = array("exp", "balance+" . $money);
		$recharge = M("userinfo")->where(array("token" => $token, "wecha_id" => $wecha_id))->save($data);

		if ($recharge) {
			$add_record = M("Member_card_pay_record")->where($where)->add($record_data);
			return json_encode(array("status" => "SUCCESS", "msg" => "充值成功"));
		}
		else {
			return json_encode(array("status" => "FAIL", "msg" => "充值失败"));
		}
	}

	public function notice($actioninfo)
	{
		if ($actioninfo == "") {
			return false;
		}

		if (($actioninfo["need_follow"] == 1) && ($this->isSubscribe() == false)) {
			$this->memberNotice("", 1);
		}
		else {
			if (($actioninfo["need_phone"] == 1) && empty($this->fans["tel"])) {
				$this->memberNotice();
			}
			else {
				if ((strpos($actioninfo["remind_link"], "{siteUrl}") !== false) || (strpos($actioninfo["remind_link"], "{wechat_id}") !== false)) {
					$link = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $actioninfo["remind_link"]);
				}
				else {
					$link = $actioninfo["remind_link"];
				}

				$publicnum = M("home")->where(array("token" => $this->token))->find();
				$this->assign("remind_word", $actioninfo["remind_word"]);
				$this->assign("link", $link);
				$this->assign("logo", $publicnum["logo"]);
			}
		}
	}
}


?>
