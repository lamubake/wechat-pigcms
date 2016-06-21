<?php

class AuthAction extends BaseAction
{
	private $_salt = "";
	private $_status = array(
		0     => array("status" => 0, "message" => "success"),
		10001 => array("status" => 10001, "message" => "签名无效"),
		40001 => array("status" => 40001, "message" => "非法请求"),
		40101 => array("status" => 40101, "message" => "对接失败"),
		40201 => array("status" => 40201, "message" => "登录失败,用户名或密码错误"),
		40301 => array("status" => 40301, "message" => "没有权限"),
		40302 => array("status" => 40302, "message" => "域名未被授权"),
		40401 => array("status" => 40401, "message" => "非法操作"),
		40501 => array("status" => 40501, "message" => "请确认是否存在这个token是否存在，如果不存在请先创建"),
		40502 => array("status" => 40502, "message" => "找不到这个用户"),
		50001 => array("status" => 50001, "message" => "Model模型格式错误")
		);
	private $_callback = array(
		1 => array("pay" => "/wap/otherPay.php?", "auth" => "/wap/otherAuth.php?", "share" => "/wap/otherShare.php?", "follow" => "/wap/otherFollow.php?"),
		2 => array("pay" => "/wap.php?c=Wxapp&a=pay&", "auth" => "/wap.php?c=Wxapp&a=redirect&", "share" => "/wap.php?c=Wxapp&a=share&", "follow" => "/wap.php?c=Wxapp&a=check_follow&")
		);
	private $_type = array("weidian" => 1, "o2o" => 2);
	public $_accessListAction = array("bargain", "seckill", "crowdfunding", "unitary", "cutprice", "lottery", "red_packet", "guajiang", "jiugong", "luckyfruit", "goldenegg", "voteimg", "custom", "card", "game", "live", "research", "forum", "autumn", "helping");
	private $_allowModel = array(
		"select" => array("Bargain", "SeckillAction", "Crowdfunding", "Unitary", "Cutprice", "RedPacket", "Lottery", "Voteimg", "CustomSet", "Cards", "Games", "Research", "ForumConfig", "Live", "Helping"),
		"update" => array("Nothing", "Bargain", "Seckill", "Crowdfunding", "Unitary", "Cutprice", "RedPacket", "Lottery", "Voteimg", "CustomSet", "Cards", "Games", "Research", "ForumConfig", "Live", "Helping"),
		"insert" => array("Userinfo")
		);
	private $_allowDbMethod = array("where", "limit", "order", "field", "group", "page", "having", "distinct", "lock", "cache");
	private $_activityToOrder = array("Seckill" => "SeckillBook");

	public function _initialize()
	{
		parent::_initialize();
		$this->_salt = C("encryption") ? C("encryption") : "pigcms";
	}

	public function getCallbackUrl($type, $action)
	{
		return $this->_callback[$type][$action];
	}

	public function signin()
	{
		$params = array();
		$sign = $_POST["sign"];
		unset($_POST["sign"]);
		if (IS_POST && $this->_signVeryfy($_POST, $sign)) {
			if ($this->_login()) {
				$params = $this->_status[0];
				$params["session_id"] = session_id();
			}
			else {
				$params = $this->_status[40201];
			}
		}
		else {
			$params = $this->_status[40001];
		}

		exit(json_encode($params));
	}

	public function signup()
	{
		if (!IS_POST) {
			exit(json_encode($this->_status[40001]));
		}

		$sign = $_POST["sign"];
		unset($_POST["sign"]);
		$is_syn = ($this->_type[$_POST["type"]] ? $this->_type[$_POST["type"]] : 0);

		if ($this->_signVeryfy($_POST, $sign)) {
			$user = array("username" => $_POST["username"] . "_" . $_POST["type"], "password" => md5($_POST["username"] . $_POST["userid"]), "createtime" => time(), "createip" => get_client_ip(), "gid" => 4, "usertplid" => 1, "viptime" => strtotime("+20 year"), "status" => 1, "is_syn" => $is_syn, "openid" => $_POST["userid"], "source_domain" => $_POST["domain"]);
			$user_id = M("Users")->where(array("username" => $user["username"], "openid" => $_POST["userid"], "source_domain" => $_POST["domain"]))->getField("id");

			if (empty($user_id)) {
				$data = M("Users")->where(array("source_domain" => $_POST["domain"], "is_syn" => $is_syn))->find();

				if (empty($data)) {
					if (M("Users")->where(array("is_syn" => $is_syn))->find()) {
						exit(json_encode($this->_status[40302]));
					}
				}

				$user_id = M("Users")->add($user);

				if (!$user_id) {
					exit(json_encode($this->_status[40101]));
				}
			}

			$token = substr(md5($_POST["domain"] . $_POST["wxuserid"] . $_POST["type"]), 8, 16);
			$wxuser = M("Wxuser")->where(array("token" => $token))->find();

			if (empty($wxuser)) {
				$wxuser = array("uid" => $user_id, "routerid" => $_POST["wxuserid"], "wxname" => $_POST["type"] . "_" . substr(md5($_POST["wxuserid"]), 8, 10), "token" => $token, "winxintype" => $_POST["wxtype"], "oauth" => 0, "is_syn" => $is_syn, "createtime" => time());
				$alipay_config_db = M("Alipay_config");
				$this->alipayConfig = $alipay_config_db->add(array("token" => $token, "open" => 1));
				$result = M("Wxuser")->add($wxuser);
			}

			exit(json_encode($this->_status[0]));
		}
	}

	public function oauth2()
	{
		$token = $_GET["token"];
		$wechat_id = $_GET["wechat_id"];
		$callback = $_SESSION["auth_callback_" . $token];
		$_SESSION["auth_callback_" . $token] = NULL;

		if (empty($callback)) {
			exit($this->_status[40001]["message"]);
		}

		$userinfo = M("Userinfo")->where(array("token" => $token, "wecha_id" => $wechat_id))->find();

		if (empty($userinfo)) {
			exit($this->_status[40501]["message"]);
		}

		$session_openid_name = "token_openid_" . $token;
		session($session_openid_name, $wechat_id);
		header("Location:" . $callback["url"] . "&wecha_id=" . $wechat_id);
	}

	public function access()
	{
		session_commit();
		session_id($this->_get("session_id"));
		session_start();
		$sign = $this->_get("sign");
		unset($_GET["sign"]);
		$action = $this->_get("action");
		if (!in_array(strtolower($action), $this->_accessListAction) || empty($_SERVER["HTTP_REFERER"])) {
			exit($this->_status[40301]["message"]);
		}

		$token = $this->_get("token");

		if ($token) {
			session("routerid", M("Wxuser")->where(array("token" => $token))->getField("routerid"));
			session("token", $token);
		}
		else {
			exit($this->_status[40501]["message"]);
		}

		if ($_COOKIE[session_name()]) {
			$session = $_SESSION;
			session_commit();
			session_id($_COOKIE[session_name()]);
			session_start();
			$_SESSION = $session;
		}

		if ($this->_signVeryfy($_GET, $sign)) {
			redirect($this->siteUrl . U("User/" . ucfirst($action) . "/index", array("token" => $token)));
		}
	}

	public function count()
	{
		$result = $this->_db("select");
		$_POST["option"] = array_intersect_key($_POST["option"], array_flip($this->_allowDbMethod));
		$result["data"]["count"] = M($result["model"])->where($_POST["option"]["where"])->count();
		$this->_debug($result);
		$result["sign"] = $this->_getSign($result);
		exit(json_encode($result));
	}

	public function select()
	{
		$result = $this->_db("select");
		$_POST["option"] = array_intersect_key($_POST["option"], array_flip($this->_allowDbMethod));
		$result["data"] = M($result["model"])->select($_POST["option"]);
		$this->_debug($result);
		$result["sign"] = $this->_getSign($result);
		exit(json_encode($result));
	}

	public function update()
	{
		$result = $this->_db("update");
		unset($_POST["data"]["token"]);
		$_POST["option"]["where"]["token"] = $_POST["token"];
		$_POST["option"] = array_intersect_key($_POST["option"], array_flip($this->_allowDbMethod));

		if (!empty($_POST["toOrder"])) {
			$result["model"] = $this->_activityToOrder[$result["model"]];
		}

		$result["data"] = D($result["model"])->save($_POST["data"], $_POST["option"]);
		$this->_debug($result, "D");
		$result["select"] = M($result["model"])->select($_POST["option"]);
		$this->_debug($result);
		$result["sign"] = $this->_getSign($result);
		exit(json_encode($result));
	}

	public function insert()
	{
		$result = $this->_db("insert");
		$data = array();

		if (isset($_POST["option"])) {
			$_POST["option"]["where"]["token"] = $_POST["token"];
			$data = M($result["model"])->find($_POST["option"]);
			$this->_debug($result);
		}

		if (empty($data)) {
			$_POST["data"]["token"] = $_POST["token"];
			$result["data"] = D($result["model"])->add($_POST["data"]);
		}
		else {
			unset($_POST["data"]["token"]);
			$result["data"] = D($result["model"])->save($_POST["data"], $_POST["option"]);
		}

		$this->_debug($result, "D");
		$result["sign"] = $this->_getSign($result);
		exit(json_encode($result));
	}

	public function order()
	{
		if (empty($_GET["from"])) {
			$result = $this->_db("update");

			if (!empty($_POST["toOrder"])) {
				$result["model"] = $this->_activityToOrder[$result["model"]];
			}

			$from = htmlentities($_POST["from"]);
			$transactionid = $_POST["transactionid"];
			$token = $_POST["token"];
			$orderid = $_POST["option"]["where"]["orderid"];
			$payType = $_POST["data"]["paytype"];
			$payHandel = new payHandle($token, $from, $payType);
			$orderInfo = $payHandel->beforePay($orderid);

			if (empty($orderInfo["paid"])) {
				$orderInfo = $payHandel->afterPay($orderid, $transactionid);
				$url = C("site_url") . "/index.php?g=Wap&m=" . $from . "&a=payReturn&token=" . $orderInfo["token"] . "&wecha_id=" . $orderInfo["wecha_id"] . "&rget=1&orderid=" . $orderid;
				file_get_contents($url);
			}
		}
		else {
			$sign = $_GET["sign"];
			unset($_GET["sign"]);

			if ($this->_signVeryfy($_GET, $sign)) {
				$from = htmlentities($_GET["from"]);
				$transactionid = $_GET["transactionid"];
				$token = $_GET["token"];
				$orderid = $_GET["orderid"];
				$payType = $_GET["paytype"];
				$payHandel = new payHandle($token, $from, $payType);
				$orderInfo = $payHandel->beforePay($orderid);

				if (empty($orderInfo["paid"])) {
					$orderInfo = $payHandel->afterPay($orderid, $transactionid);
					$url = C("site_url") . "/index.php?g=Wap&m=" . $from . "&a=payReturn&token=" . $orderInfo["token"] . "&wecha_id=" . $orderInfo["wecha_id"] . "&orderid=" . $orderid;
					header("Location:" . $url);
				}
				else {
					$url = C("site_url") . "/index.php?g=Wap&m=" . $from . "&a=payReturn&nohandle=1&token=" . $orderInfo["token"] . "&wecha_id=" . $orderInfo["wecha_id"] . "&orderid=" . $orderid;
					header("Location:" . $url);
				}
			}
		}
	}

	public function share()
	{
		$wxuser["appid"] = C("appid");
		$wxuser["secret"] = C("secret");
		$apiOauth = new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($wxuser["appid"], $wxuser);
		$result["ticket"] = $apiOauth->getAuthorizerTicket($wxuser["appid"], $access_token);
		$result["appid"] = $wxuser["appid"];
		exit(json_encode($result));
	}

	private function _db($option)
	{
		if (IS_POST) {
			$model = $this->_post("model");
			$sign = $this->_post("sign");
			unset($_POST["sign"]);

			if (!preg_match("/^([A-Z]{1}[a-z]+)+$/", $model)) {
				exit(json_encode($this->_status[50001]));
			}

			if (!in_array($model, $this->_allowModel[$option])) {
				exit(json_encode($this->_status[40301]));
			}

			if ("select" != $option) {
				$data = M("Wxuser")->where(array("token" => $_POST["token"]))->find();

				if (empty($data["token"])) {
					exit(json_encode($this->_status[40501]));
				}
			}

			if ($this->_signVeryfy($_POST, $sign)) {
				$result = $this->_status[0];
				$result["request_time"] = $_SERVER["REQUEST_TIME_FLOAT"];
				$result["model"] = $model;
				return $result;
			}
		}
		else {
			exit(json_encode($this->_status[40001]));
		}
	}

	private function _debug(&$result, $method)
	{
		if ($_POST["debug"]) {
			if ("D" == $method) {
				$result["debug"]["sql"][] = D($result["model"])->_sql();
			}
			else {
				$result["debug"]["sql"][] = M($result["model"])->_sql();
			}
		}
	}

	private function _signVeryfy($data, $sign)
	{
		if ($this->_getSign($data) == $sign) {
			return true;
		}
		else {
			exit(json_encode($this->_status[10001]));
		}
	}

	private function _getSign($data)
	{
		foreach ($data as $key => $value ) {
			if (is_array($value)) {
				$validate[$key] = $this->_getSign($value);
			}
			else {
				$validate[$key] = $value;
			}
		}

		$validate["salt"] = $this->_salt;
		sort($validate, SORT_STRING);
		return sha1(implode($validate));
	}

	private function _login()
	{
		$db = D("Users");
		$uname = $this->_post("username");
		$userid = $this->_post("userid");
		$type = strtolower($this->_post("type"));
		$autDomain = explode(".", $_SERVER["HTTP_HOST"]);
		$viptime = $db->where(array("username" => $uname))->getfield("viptime");
		$pwd = md5($uname . $userid);
		$res = $db->where(array("username" => $uname . "_" . $type, "openid" => $_POST["userid"], "is_syn" => $this->_type[$type]))->find();
		if ($res && ($pwd === $res["password"])) {
			session("uid", $res["id"]);
			session("gid", $res["gid"]);
			session("uname", $res["username"]);
			$info = M("user_group")->find($res["gid"]);
			session("diynum", $res["diynum"]);
			session("connectnum", $res["connectnum"]);
			session("activitynum", $res["activitynum"]);
			session("viptime", $res["viptime"]);
			session("gname", $res["name"]);
			session("is_syn", $res["is_syn"]);
			session("source_domain", $res["source_domain"]);
			$now = time();
			$month = date("m", $now);
			if (($month != $res["lastloginmonth"]) && ($res["lastloginmonth"] != 0)) {
				$data["id"] = $res["id"];
				$data["imgcount"] = 0;
				$data["diynum"] = 0;
				$data["textcount"] = 0;
				$data["musiccount"] = 0;
				$data["connectnum"] = 0;
				$data["activitynum"] = 0;
				$db->save($data);
				session("diynum", 0);
				session("connectnum", 0);
				session("activitynum", 0);
			}

			$db->where(array("id" => $res["id"]))->save(array("lasttime" => $now, "lastloginmonth" => $month, "lastip" => htmlspecialchars(trim(get_client_ip()))));
			return true;
		}
		else {
			return false;
		}
	}
}


?>
