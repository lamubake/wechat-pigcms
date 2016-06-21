<?php

class CollectwordAction extends WapAction
{
	public $info;
	public $fans;
	public $collectword;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->fans = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->find();

		if ($this->fans == "") {
		}

		$id = $this->_get("id", "intval");
		$info = M("Collectword")->where(array("id" => $id, "token" => $this->token, "is_open" => 0))->find();
		$this->info = $info;
		$news_list = S($id . "Collectword" . $this->token . "news");

		if ($news_list == "") {
			$news_list = M("CollectwordNews")->where(array("token" => $this->token, "pid" => $id))->order("id")->select();

			foreach ($news_list as $nk => $nv ) {
				$news_list[$nk]["url"] = $this->getLink($nv["url"]);
			}

			S($id . "Collectword" . $this->token . "news", $news_list);
		}

		$prize_list = M("CollectwordPrize")->where(array("token" => $this->token, "pid" => $id))->order("id")->select();

		if ("1" == $_GET["isjoin"]) {
			if ($this->info["start"] < time()) {
				M("CollectwordUser")->where(array("pid" => $id, "token" => $this->token, "wecha_id" => $this->wecha_id))->setField("is_join", "1");
			}
			else {
				$this->error("活动尚未开始。");
			}
		}

		$params["word"] = $this->info["word"];
		$params["count"] = $prize_list[0]["num"];
		$params["sCount"] = $params["count"] - M("CollectwordUser")->where(array("token" => $this->token, "pid" => $id, "is_prize" => "1"))->count();
		$params["time"] = $this->info["end"] - time();
		$params["expect"] = $this->info["expect"];
		$this->collectword = new CollectWord($params);
		$this->info["wordNum"] = $this->collectword->getLength();
		$this->info["word"] = $this->collectword->getWord();
		$this->assign("news_list", $news_list);
		$this->assign("prize_list", $prize_list);
		$this->info["fxtitle_d"] = strtr($this->info["fxtitle"], array("{{活动名称}}" => $this->info["title"]));
		$this->info["fxinfo_d"] = strtr($this->info["fxinfo"], array("{{活动名称}}" => $this->info["title"]));
		$this->info["fxtitle"] = strtr($this->info["fxtitle"], array("{{活动名称}}" => $this->info["title"], "{{字数}}" => count($this->_record())));
		$this->info["fxinfo"] = strtr($this->info["fxinfo"], array("{{活动名称}}" => $this->info["title"], "{{字数}}" => count($this->_record())));
		$this->info["prize_fxtitle"] = strtr($this->info["prize_fxtitle"], array("{{活动名称}}" => $this->info["title"], "{{奖品名称}}" => $prize_list[0]["title"]));
		$this->info["prize_fxinfo"] = strtr($this->info["prize_fxinfo"], array("{{活动名称}}" => $this->info["title"], "{{奖品名称}}" => $prize_list[0]["title"]));
		$this->info["reply_pic"] = (empty($this->info["fxpic"]) ? $this->info["reply_pic"] : $this->info["fxpic"]);
		$replyPic = strstr($this->info["reply_pic"], "://");

		if (empty($replyPic)) {
			$this->info["reply_pic"] = C("site_url") . $this->info["reply_pic"];
		}

		$this->assign("info", $this->info);
	}

	public function index()
	{
		D("Userinfo")->convertFake(M("CollectwordUser"), array("token" => $this->token, "fakeopenid" => $this->fakeopenid, "wecha_id" => $this->wecha_id));
		D("Userinfo")->convertFake(M("CollectwordShare"), array("token" => $this->token, "fakeopenid" => $this->fakeopenid, "wecha_id" => $this->wecha_id));
		D("Userinfo")->convertFake(M("CollectwordRecord"), array("token" => $this->token, "fakeopenid" => $this->fakeopenid, "wecha_id" => $this->wecha_id));
		$id = $this->_get("id", "intval");
		$share_key = $this->_get("share_key", "trim");
		$now = time();

		if ($share_key != "") {
			$is_my = M("CollectwordUser")->where(array("token" => $this->token, "pid" => $id, "wecha_id" => $this->wecha_id, "share_key" => $share_key))->find();

			if ($is_my != "") {
				$this->redirect("Collectword/index", array("token" => $this->token, "id" => $id));
				exit();
			}
			else {
				$share = M("CollectwordShare")->where(array("token" => $this->token, "pid" => $id, "share_key" => $share_key, "wecha_id" => $this->wecha_id))->find();

				if (empty($share)) {
					$is_prize = M("CollectwordUser")->where(array("token" => $this->token, "pid" => $id, "share_key" => $share_key))->getField("is_prize");
					if (empty($is_prize) && $this->wecha_id) {
						M("CollectwordUser")->where(array("token" => $this->token, "pid" => $id, "share_key" => $share_key))->setInc("count", $this->info["help_count"]);
						M("CollectwordUser")->where(array("token" => $this->token, "pid" => $id, "share_key" => $share_key))->setInc("share_num", 1);
						M("CollectwordShare")->add(array("pid" => $id, "token" => $this->token, "share_key" => $share_key, "wecha_id" => $this->wecha_id, "addtime" => time()));
						$this->assign("firstShare", true);
					}
				}
			}
		}

		if ($now < $this->info["start"]) {
			$is_over = 1;
		}
		else if ($this->info["end"] < $now) {
			$is_over = 2;
		}
		else {
			$is_over = 0;
		}

		if ($this->fans) {
			$my = M("CollectwordUser")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id))->find();
			if (!empty($this->wecha_id) && empty($my)) {
				$data = array("pid" => $id, "wecha_id" => $this->wecha_id, "token" => $this->token, "count" => $this->info["count"], "addtime" => time(), "update_time" => time());
				$uid = M("CollectwordUser")->add($data);
				$share_key2 = $this->getKey($uid);
				$my["pid"] = $id;
				$my["wecha_id"] = $this->wecha_id;
				$my["token"] = $this->token;
				$my["share_key"] = $share_key2;
				M("CollectwordUser")->where(array("token" => $this->token, "id" => $uid))->save(array("share_key" => $share_key2));
			}

			$my["tel"] = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->getField("tel");

			if ($share_key != "") {
				$user = M("CollectwordUser")->where(array("token" => $this->token, "share_key" => $share_key, "pid" => $id))->find();
				$user["wechaname"] = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $user["wecha_id"]))->getField("wechaname");
				$user["portrait"] = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $user["wecha_id"]))->getField("portrait");
			}
			else {
				$user["share_key"] = $my["share_key"];
			}
		}

		if (($this->info["is_attention"] == 1) && !$this->isSubscribe()) {
			$this->memberNotice("", 1);
			$this->assign("subscribe", 1);
		}
		else {
			if ((($this->info["is_reg"] == 1) || ($this->info["is_sms"] == 1)) && empty($this->fans["tel"])) {
				if ($this->Collectword["is_sms"] == 0) {
					$this->memberNotice();
				}
				else {
					$this->assign("sms", 1);
					$this->assign("memberNotice", "<div style=\"display:none\"></div>");
				}
			}
			else {
				if (($this->info["is_sms"] == 1) && empty($this->fans["tel"]) && ($this->fans["isverify"] != 1)) {
					$this->assign("sms", 1);
					$this->assign("memberNotice", "<div style=\"display:none\"></div>");
				}
			}
		}

		$this->assign("rank", $this->_rank($this->info["rank_num"]));

		if (empty($this->info["prize_display"])) {
			$this->assign("prize", $this->_prize());
		}

		if ($this->info["day_count"]) {
			if ($my["update_time"] < strtotime(date("Y-m-d"))) {
				M("CollectwordUser")->where(array("id" => $my["id"]))->save(array("update_time" => time(), "count" => $my["count"] + $this->info["day_count"]));
				$my["count"] += $this->info["day_count"];
			}
		}

		$this->assign("TA", $share_key ? "TA" : "我");
		$this->assign("share_key", $share_key);
		$this->assign("user", $user);
		$this->assign("fans", $this->fans);
		$this->assign("is_over", $is_over);
		$this->assign("my", $my);

		if ($share_key) {
			$this->assign("wordNum", $this->_record($user));
		}
		else {
			$this->assign("wordNum", $this->_record());
		}

		if (($this->collectword->getCount() - $this->_count($id)) < 1) {
			$is_over = 2;
		}

		$shareCount = M("CollectwordShare")->where(array("token" => $this->token, "pid" => $id, "share_key" => $my["share_key"]))->count();
		$this->assign("shareCount", $shareCount);
		$class = "start";

		if ($my["is_prize"]) {
			$class = "prize";
		}

		if (empty($my["is_join"])) {
			$class = "in";
		}

		if ($is_over == 2) {
			$class = "end";
		}

		$this->assign("class", $class);
		$this->display();
	}

	public function share()
	{
		$id = $this->_get("id", "intval");
		$model = M("CollectwordShare");
		$models = $model->alias("share")->where(array("share.token" => $this->token, "share.pid" => $id, "share.share_key" => $_GET["key"]))->join("LEFT JOIN " . C("DB_PREFIX") . "userinfo AS user ON share.wecha_id = user.wecha_id AND share.token = user.token")->order("share.id DESC")->group("user.wecha_id")->field("share.*,user.wechaname,portrait")->limit(100)->select();
		$this->assign("models", $models);
		$this->display();
	}

	private function _count($id)
	{
		$count = M("CollectwordUser")->where(array(0 => "token", 1 => $this->token, "pid" => $id, "is_prize" => 1))->count();
		return $count;
	}

	public function ajaxPrize()
	{
		$id = $this->_get("id", "intval");
		$modelUser = M("CollectwordUser");
		$modelRecord = M("CollectwordRecord");
		$prize_count = $modelUser->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id))->getField("count");

		if ($this->info["end"] < time()) {
			$is_over = 2;
			$result["status"] = "-1";
			exit(json_encode($result));
		}

		if (0 < $prize_count) {
			$modelUser->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id))->setDec("count");
		}
		else {
			$result["status"] = "-1";
			exit(json_encode($result));
		}

		$record = $this->_record();
		$this->collectword->setSelfNum(array_keys($record));
		$result = $this->collectword->prize();
		$word = $modelRecord->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id, "word" => $result["message"]))->find();

		if ($word) {
			$modelRecord->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id, "word" => $result["message"]))->setInc("count");
			$result["count"] = $word["count"] + 1;
		}
		else {
			$data["token"] = $this->token;
			$data["wecha_id"] = $this->wecha_id;
			$data["pid"] = (int) $_GET["id"];
			$data["word"] = $result["message"];
			$data["addtime"] = time();
			$result["count"] = $data["count"] = 1;
			$modelRecord->add($data);
		}

		if ($result["status"]) {
			$data2["is_prize"] = 1;
		}

		$data2["word_count"] = $modelRecord->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id))->count();
		$modelUser->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id))->save($data2);
		$result["prize_count"] = $prize_count - 1;
		$result["total"] = count($this->_record());
		exit(json_encode($result));
	}

	private function _record($user)
	{
		$id = $this->_get("id", "intval");

		if ($user) {
			$result = M("CollectwordRecord")->where(array("token" => $this->token, "wecha_id" => $user["wecha_id"], "pid" => $id))->select();
		}
		else {
			$result = M("CollectwordRecord")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id))->select();
		}

		foreach ($result as $key => $value ) {
			$selfNum[$value["word"]] = $value["count"];
		}

		return $selfNum;
	}

	private function _rank($limit)
	{
		$id = $this->_get("id", "intval");
		$model = M("CollectwordUser");
		$result = $model->alias("cuser")->where(array(
	"cuser.token"      => $this->token,
	"cuser.pid"        => $id,
	"cuser.is_join"    => 1,
	"cuser.word_count" => array("neq", "0")
	))->join("LEFT JOIN " . C("DB_PREFIX") . "userinfo AS user ON cuser.wecha_id = user.wecha_id AND cuser.token = user.token")->order("cuser.word_count DESC, cuser.share_num DESC,cuser.id ASC")->group("user.wecha_id")->field("cuser.*,user.wechaname,portrait")->limit($limit)->select();
		return $result;
	}

	private function _prize()
	{
		$id = $this->_get("id", "intval");
		$model = M("CollectwordUser");
		$result = $model->alias("cuser")->where(array("cuser.token" => $this->token, "cuser.pid" => $id, "cuser.is_prize" => 1, "cuser.is_join" => 1))->join("LEFT JOIN " . C("DB_PREFIX") . "userinfo AS user ON cuser.wecha_id = user.wecha_id AND cuser.token = user.token")->order("cuser.id ASC")->group("user.wecha_id")->field("cuser.*,user.wechaname,portrait")->select();
		return $result;
	}

	public function sms()
	{
		if ($_POST["tel"] != "") {
			$is_tel = M("userinfo")->where(array("token" => $_POST["token"], "tel" => $_POST["tel"], "isverify" => 1))->find();

			if ($is_tel == "") {
				$params = array();
				$session_sms = session($_POST["wecha_id"] . "codeSentiment" . $_POST["token"] . $_POST["id"]);
				if ((time() < $session_sms["time"]) && ($session_sms["tel"] == $_POST["tel"])) {
					$code = $session_sms["code"];
				}
				else {
					session($_POST["wecha_id"] . "codeSentiment" . $_POST["token"] . $_POST["id"], NULL);
					$code = rand(100000, 999999);
					$session_sms["tel"] = $_POST["tel"];
					$session_sms["code"] = $code;
					$session_sms["time"] = time() + (60 * 30);
					session($_POST["wecha_id"] . "codeSentiment" . $_POST["token"] . $_POST["id"], $session_sms);
				}

				$params["sms"] = array("token" => $this->token, "mobile" => $_POST["tel"], "content" => "您的验证码是：" . $code . "。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！");
				$data["error"] = MessageFactory::method($params, "SmsMessage");
				$this->ajaxReturn($data, "JSON");
			}
			else {
				$data["error"] = "tel";
				$this->ajaxReturn($data, "JSON");
			}
		}
	}

	public function smsyz()
	{
		$session_sms = session($_POST["wecha_id"] . "codeSentiment" . $_POST["token"] . $_POST["id"]);

		if ($_POST["code"] != $session_sms["code"]) {
			$data["error"] = 1;
		}
		else if ($_POST["tel"] != $session_sms["tel"]) {
			$data["error"] = 2;
		}
		else if ($session_sms["time"] < time()) {
			$data["error"] = 3;
		}
		else {
			$data["error"] = 0;
		}

		M("CollectwordUser")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => (int) $_GET["id"]))->save(array("tel" => $session_sms["tel"], "is_join" => 1));
		$this->ajaxReturn($data, "JSON");
	}

	public function getKey($id)
	{
		$str = md5(time() . mt_rand(1000, 9999) . $id);
		return $str;
	}
}


?>
