<?php

class DiymenAction extends UserAction
{
	public $thisWxUser;
	public $access_token;

	public function _initialize()
	{
		parent::_initialize();
	}

	public function imagenews($token, $type)
	{
		if ("1" == $type) {
			$where = array(
				"token"    => $token,
				"media_id" => array("neq", "")
				);
			$db = M("Img");
			$tpl = "news";
		}
		else if ("2" == $type) {
			$db = M("Files");
			$tpl = "image";
		}

		$where = array(
			"token"    => $token,
			"media_id" => array("neq", "")
			);
		$count = $db->where($where)->count();
		$page = new Page($count, 20);
		$info = $db->where($where)->order("ID DESC")->limit($page->firstRow . "," . $page->listRows)->select();
		$this->assign("page", $page->show());
		$this->assign("info", $info);
		$this->display($tpl);
	}

	public function index()
	{
		$data = M("Diymen_set")->where(array("token" => $_SESSION["token"]))->find();
		$this->assign("diymen", $data);

		if (IS_POST) {
			$_POST["token"] = $_SESSION["token"];

			if ($data == false) {
				$this->all_insert("Diymen_set");
			}
			else {
				$_POST["id"] = $data["id"];
				$this->all_save("Diymen_set");
			}

			M("Wxuser")->where(array("token" => $this->token))->save(array("appid" => trim($this->_post("appid")), "appsecret" => trim($this->_post("appsecret"))));
		}
		else {
			$class = M("Diymen_class")->where(array("token" => session("token"), "pid" => 0))->order("sort desc, id ASC")->select();
			$i = 0;
			$count = 3;

			foreach ($class as $key => $vo ) {
				if ($vo["is_show"]) {
					$i++;
					$count = (4 < $i ? 4 : $i);
					$displayMenu[] = $vo;
				}

				$c = M("Diymen_class")->where(array("token" => session("token"), "pid" => $vo["id"]))->order("sort desc, id ASC")->select();
				$class[$key]["class"] = $c;

				foreach ($c as $k => $v ) {
					if ($v["is_show"] && $vo["is_show"]) {
						$displayMenu[$i - 1]["class"][] = $v;
					}
				}
			}

			$model = M("Wxuser")->where(array("token" => session("token")))->find();
			$this->assign("id", $model["id"]);
			$this->assign("token", $model["token"]);
			$this->assign("fuwuAppid", $model["fuwuappid"]);
			$this->assign("class", $class);
			$this->assign("show", array("is_show" => 1));
			$this->assign("wxsys", $this->_get_sys());
			$this->assign("displayMenu", $displayMenu);
			$this->assign("count", $count);
			$this->display();
		}
	}

	public function class_add()
	{
		if (IS_POST) {
			if (($_POST["menu_type"] == 2) && ($_POST["url"] != "")) {
				if (!D("Img")->isOpenSync($this->wxuser)) {
					if ($this->dwzQuery(array("tinyurl" => $_POST["url"]))) {
						$this->error("禁止使用短网址");
					}

					$_POST["url"] = $this->replaceUrl($_POST["url"], array(
	"query" => array("wecha_id" => "{wechat_id}")
	));
				}
			}

			$type = array(
				1 => "keyword",
				2 => "url",
				3 => "wxsys",
				4 => "tel",
				5 => array("longitude", "latitude")
				);

			foreach ($type as $key => $value ) {
				if ($_POST["menu_type"] != $key) {
					if (is_array($value)) {
						foreach ($value as $v ) {
							unset($_POST[$v]);
						}
					}
					else {
						unset($_POST[$value]);
					}
				}
			}

			$this->all_insert("Diymen_class", "/index");
		}
		else {
			$class = M("Diymen_class")->where(array("token" => session("token"), "pid" => 0))->order("sort desc")->select();
			$this->assign("class", $class);
			$this->assign("wxsys", $this->_get_sys());
			$this->display();
		}
	}

	public function class_del()
	{
		$class = M("Diymen_class")->where(array("token" => session("token"), "pid" => $this->_get("id")))->order("sort desc")->find();

		if ($class == false) {
			$back = M("Diymen_class")->where(array("token" => session("token"), "id" => $this->_get("id")))->delete();

			if ($back == true) {
				$this->success("删除成功", U("Diymen/index"));
			}
			else {
				$this->error("删除失败");
			}
		}
		else {
			$class2 = M("Diymen_class")->where(array("token" => session("token"), "id" => $this->_get("id")))->order("sort desc")->find();

			if (0 != $class2["pid"]) {
				$back = M("Diymen_class")->where(array("token" => session("token"), "pid" => $this->_get("id")))->delete();
				$back2 = M("Diymen_class")->where(array("token" => session("token"), "id" => $this->_get("id")))->delete();
				if (($back == true) && ($back2 == true)) {
					$this->success("删除成功", U("Diymen/index"));
				}
				else {
					$this->error("删除失败");
				}
			}
			else {
				$this->error("请先删除子菜单");
			}
		}
	}

	public function class_edit()
	{
		$this->assign("wxsys", $this->_get_sys());

		if (IS_POST) {
			$_POST["id"] = $this->_get("id");
			if (($_POST["menu_type"] == 1) && ($_POST["keyword"] != "")) {
				$set = array("url" => "", "wxsys" => "", "tel" => "", "nav" => "");
				unset($_POST["wxsys"]);
				unset($_POST["url"]);
				unset($_POST["tel"]);
				unset($_POST["longitude"]);
				unset($_POST["latitude"]);
			}
			else {
				if (($_POST["menu_type"] == 2) && ($_POST["url"] != "")) {
					if (!D("Img")->isOpenSync($this->wxuser)) {
						if ($this->dwzQuery(array("tinyurl" => $_POST["url"]))) {
							$this->error("禁止使用短网址");
						}

						$_POST["url"] = $this->replaceUrl($_POST["url"], array(
	"query" => array("wecha_id" => "{wechat_id}")
	));
					}

					$set = array("keyword" => "", "wxsys" => "", "tel" => "", "nav" => "");
					unset($_POST["keyword"]);
					unset($_POST["wxsys"]);
					unset($_POST["tel"]);
					unset($_POST["longitude"]);
					unset($_POST["latitude"]);
				}
				else {
					if (($_POST["menu_type"] == 3) && ($_POST["wxsys"] != "")) {
						$set = array("keyword" => "", "url" => "", "tel" => "", "nav" => "");
						unset($_POST["keyword"]);
						unset($_POST["url"]);
						unset($_POST["tel"]);
						unset($_POST["longitude"]);
						unset($_POST["latitude"]);
					}
					else {
						if (($_POST["menu_type"] == 4) && ($_POST["tel"] != "")) {
							$set = array("keyword" => "", "url" => "", "wxsys" => "", "nav" => "");
							unset($_POST["keyword"]);
							unset($_POST["url"]);
							unset($_POST["wxsys"]);
							unset($_POST["longitude"]);
							unset($_POST["latitude"]);
						}
						else {
							if (($_POST["menu_type"] == 5) && ($_POST["longitude"] != "") && ($_POST["latitude"] != "")) {
								$set = array("keyword" => "", "url" => "", "wxsys" => "", "tel" => "");
								unset($_POST["keyword"]);
								unset($_POST["url"]);
								unset($_POST["wxsys"]);
								unset($_POST["tel"]);
							}
						}
					}
				}
			}

			M("Diymen_class")->where(array("id" => $_POST["id"]))->save($set);
			$this->all_save("Diymen_class", "/index?id=" . $this->_get("id"));
		}
		else {
			$data = M("Diymen_class")->where(array("token" => session("token"), "id" => $this->_get("id")))->find();

			if ($data == false) {
				$this->error("您所操作的数据对象不存在！");
			}

			$class = M("Diymen_class")->where(array("token" => session("token"), "pid" => 0))->order("sort desc")->select();

			if ($data["keyword"] != "") {
				$type = 1;
			}
			else if ($data["url"] != "") {
				$type = 2;
			}
			else if ($data["wxsys"] != "") {
				$type = 3;
			}
			else if ($data["tel"] != "") {
				$type = 4;
			}
			else if ($data["nav"] != "") {
				$type = 5;
				list($data["longitude"], $data["latitude"]) = explode(",", $data["nav"]);
			}

			$class2 = M("Diymen_class")->where(array("token" => session("token"), "pid" => $this->_get("id")))->order("sort desc")->find();

			foreach ($class as $key => $value ) {
				if ($this->_get("id") == $value["id"]) {
					unset($class[$key]);
				}
			}

			if ((0 != $data["pid"]) || ($class2 == false)) {
				$this->assign("class", $class);
			}

			$this->assign("show", $data);
			$this->assign("type", $type);
			$json["html"] = $this->fetch();
			$json["status"] = 200;
			$json["url"] = U("Diymen/class_edit", array("id" => $this->_get("id")));
			exit(json_encode($json));
		}
	}

	public function _get_sys($type, $key)
	{
		$wxsys = array("扫码带提示", "扫码推事件", "系统拍照发图", "拍照或者相册发图", "微信相册发图", "发送位置");

		if ($type == "send") {
			$wxsys = array("扫码带提示" => "scancode_waitmsg", "扫码推事件" => "scancode_push", "系统拍照发图" => "pic_sysphoto", "拍照或者相册发图" => "pic_photo_or_album", "微信相册发图" => "pic_weixin", "发送位置" => "location_select");
			return $wxsys[$key];
			exit();
		}

		return $wxsys;
	}

	public function class_send()
	{
		if (IS_GET) {
			$model = M("Wxuser")->where(array("token" => session("token")))->find();

			if (empty($model["appid"])) {
				$this->error("AppId 不能为空，请填写保存后再进行生成菜单");
			}
			else if (empty($model["appsecret"])) {
				if (1 != $model["type"]) {
					$this->error("非托管用户 AppSecret 不能为空，请填写保存后再进行生成菜单");
				}
			}

			$this->thisWxUser["appid"] = $model["appid"];
			$this->thisWxUser["appsecret"] = $model["appsecret"];
			$apiOauth = new apiOauth();

			if (D("Img")->isOpenSync($model)) {
				$this->access_token = $apiOauth->authorizerToken($this->thisWxUser["appid"]);
			}
			else {
				$this->access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser["appid"]);
			}

			$data = "{\"button\":[";
			$class = M("Diymen_class")->where(array("token" => session("token"), "pid" => 0, "is_show" => 1))->limit(3)->order("sort DESC, id ASC")->select();
			$kcount = count($class);
			$k = 1;

			foreach ($class as $key => $vo ) {
				$data .= "{\"name\":\"" . $vo["title"] . "\",";
				$c = M("Diymen_class")->where(array("token" => session("token"), "pid" => $vo["id"], "is_show" => 1))->limit(5)->order("sort DESC, id ASC")->select();
				$count = count($c);
				$vo["url"] = $this->convertLink($vo["url"]);
				$tel = $this->convertLink("{siteUrl}" . U("Wap/Index/tel", array("tel" => $vo["tel"], "token" => session("token"))));
				$amap = $this->convertLink("{siteUrl}" . U("Wap/Index/map", array("nav" => $vo["nav"], "name" => $vo["title"], "token" => session("token"))));

				if ($c != false) {
					$data .= "\"sub_button\":[";
				}
				else if ($vo["keyword"]) {
					if (D("Img")->isOpenSync($model)) {
						$data .= "\"type\":\"view_limited\",\"media_id\":\"" . $vo["keyword"] . "\"";
					}
					else {
						$data .= "\"type\":\"click\",\"key\":\"" . $vo["keyword"] . "\"";
					}
				}
				else if ($vo["url"]) {
					if (D("Img")->isOpenSync($model)) {
						$data .= "\"type\":\"media_id\",\"media_id\":\"" . $vo["url"] . "\"";
					}
					else {
						$data .= "\"type\":\"view\",\"url\":\"" . $vo["url"] . "\"";
					}
				}
				else if ($vo["wxsys"]) {
					$data .= "\"type\":\"" . $this->_get_sys("send", $vo["wxsys"]) . "\",\"key\":\"" . $vo["wxsys"] . "\"";
				}
				else if ($vo["tel"]) {
					$data .= "\"type\":\"view\",\"url\":\"" . $tel . "\"";
				}
				else if ($vo["nav"]) {
					$data .= "\"type\":\"view\",\"url\":\"" . $amap . "\"";
				}

				$i = 1;

				foreach ($c as $voo ) {
					$voo["url"] = $this->convertLink($voo["url"]);
					$tel = $this->convertLink($this->siteUrl . U("Wap/Index/tel", array("tel" => $voo["tel"], "token" => session("token"))));
					$amap = $this->convertLink("{siteUrl}" . U("Wap/Index/map", array("nav" => $voo["nav"], "name" => $voo["title"], "token" => session("token"))));

					if ($i == $count) {
						if ($voo["keyword"]) {
							if (D("Img")->isOpenSync($model)) {
								$data .= "{\"type\":\"view_limited\",\"name\":\"" . $voo["title"] . "\",\"media_id\":\"" . $voo["keyword"] . "\"}";
							}
							else {
								$data .= "{\"type\":\"click\",\"name\":\"" . $voo["title"] . "\",\"key\":\"" . $voo["keyword"] . "\"}";
							}
						}
						else if ($voo["url"]) {
							if (D("Img")->isOpenSync($model)) {
								$data .= "{\"type\":\"media_id\",\"name\":\"" . $voo["title"] . "\",\"media_id\":\"" . $voo["url"] . "\"}";
							}
							else {
								$data .= "{\"type\":\"view\",\"name\":\"" . $voo["title"] . "\",\"url\":\"" . $voo["url"] . "\"}";
							}
						}
						else if ($voo["wxsys"]) {
							$data .= "{\"type\":\"" . $this->_get_sys("send", $voo["wxsys"]) . "\",\"name\":\"" . $voo["title"] . "\",\"key\":\"" . $voo["wxsys"] . "\"}";
						}
						else if ($voo["tel"]) {
							$data .= "{\"type\":\"view\",\"name\":\"" . $voo["title"] . "\",\"url\":\"" . $tel . "\"}";
						}
						else if ($voo["nav"]) {
							$data .= "{\"type\":\"view\",\"name\":\"" . $voo["title"] . "\",\"url\":\"" . $amap . "\"}";
						}
					}
					else if ($voo["keyword"]) {
						if (D("Img")->isOpenSync($model)) {
							$data .= "{\"type\":\"view_limited\",\"name\":\"" . $voo["title"] . "\",\"media_id\":\"" . $voo["keyword"] . "\"},";
						}
						else {
							$data .= "{\"type\":\"click\",\"name\":\"" . $voo["title"] . "\",\"key\":\"" . $voo["keyword"] . "\"},";
						}
					}
					else if ($voo["url"]) {
						if (D("Img")->isOpenSync($model)) {
							$data .= "{\"type\":\"media_id\",\"name\":\"" . $voo["title"] . "\",\"media_id\":\"" . $voo["url"] . "\"},";
						}
						else {
							$data .= "{\"type\":\"view\",\"name\":\"" . $voo["title"] . "\",\"url\":\"" . $voo["url"] . "\"},";
						}
					}
					else if ($voo["wxsys"]) {
						$data .= "{\"type\":\"" . $this->_get_sys("send", $voo["wxsys"]) . "\",\"name\":\"" . $voo["title"] . "\",\"key\":\"" . $voo["wxsys"] . "\"},";
					}
					else if ($voo["tel"]) {
						$data .= "{\"type\":\"view\",\"name\":\"" . $voo["title"] . "\",\"url\":\"" . $tel . "\"},";
					}
					else if ($voo["nav"]) {
						$data .= "{\"type\":\"view\",\"name\":\"" . $voo["title"] . "\",\"url\":\"" . $amap . "\"},";
					}

					$i++;
				}

				if ($c != false) {
					$data .= "]";
				}

				if ($k == $kcount) {
					$data .= "}";
				}
				else {
					$data .= "},";
				}

				$k++;
			}

			$data .= "]}";
			file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=" . $this->access_token);
			$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->access_token;
			$rt = $this->api_notice_increment($url, $data);

			if ($rt["rt"] == false) {
				$errmsg = GetErrorMsg::wx_error_msg($rt["errorno"]);

				if ("480001" == $rt["errorno"]) {
					$this->error("没有生成自定义菜单的权限！");
				}
				else {
					$this->error($rt["errorno"] . ":" . $errmsg . "!");
				}
			}
			else {
				$this->success("生成菜单成功");
			}

			exit();
		}
		else {
			$this->error("非法操作");
		}
	}

	public function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);

		if ($errorno) {
			return array("rt" => false, "errorno" => $errorno);
		}
		else {
			$js = json_decode($tmpInfo, 1);

			if ($js["errcode"] == "0") {
				return array("rt" => true, "errorno" => 0);
			}
			else {
				$errmsg = GetErrorMsg::wx_error_msg($js["errcode"]);

				if ("480001" == $rt["errorno"]) {
					$this->error("没有生成自定义菜单的权限！！");
				}
				else {
					$this->error($js["errcode"] . ":" . $errmsg . "!!");
				}
			}
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
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
}


?>
