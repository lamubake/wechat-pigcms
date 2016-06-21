<?php

class FrontPageAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction("FrontPage");
	}

	public function index()
	{
		$where = array("token" => $this->token);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["keyword|action_name"] = array("like", "%" . $search_word . "%");
		}

		$total = M("frontpage_action")->where($where)->count();
		$Page = new Page($total, 10);
		$list = M("frontpage_action")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("id desc")->select();
		$this->assign("list", $list);
		$this->assign("search_word", $search_word);
		$this->assign("page", $Page->show());
		$this->display();
	}

	public function addaction()
	{
		if (IS_POST) {
			$data["action_name"] = $this->_post("action_name", "trim");
			$data["keyword"] = $this->_post("keyword", "trim");
			$data["reply_title"] = $this->_post("reply_title", "trim");
			$data["reply_content"] = $this->_post("reply_content", "trim");
			$data["reply_pic"] = $this->_post("reply_pic", "trim");
			$data["remind_word"] = $this->_post("remind_word", "trim");
			$data["remind_img"] = $this->_post("remind_img", "trim");
			$data["remind_link"] = $this->_post("remind_link", "trim");
			$data["total_create"] = $this->_post("total_create", "intval");
			$data["day_create"] = $this->_post("day_create", "intval");
			$data["is_follow"] = $this->_post("is_follow", "intval");
			$data["is_register"] = $this->_post("is_register", "intval");
			$data["custom_sharetitle"] = $this->_post("custom_sharetitle", "trim");
			$data["custom_sharedsc"] = $this->_post("custom_sharedsc", "trim");
			$data["sponsors"] = $this->_post("sponsors", "trim");
			$data["start_time"] = strtotime($_POST["start_time"]);
			$data["end_time"] = strtotime($_POST["end_time"]);
			$data["latest_count"] = ($_POST["latest_count"] ? $this->_post("latest_count", "intval") : 10);
			$data["status"] = $this->_post("status", "intval");
			$data["id"] = $this->_post("id", "intval");

			if ($this->token == "") {
				$this->error("参数错误");
			}

			if ($data["action_name"] == "") {
				$this->error("活动名称不能为空");
			}

			if ($data["keyword"] == "") {
				$this->error("回复的关键词不能为空");
			}

			if ($data["reply_title"] == "") {
				$this->error("回复的标题不能为空");
			}

			if ($data["reply_content"] == "") {
				$this->error("回复的内容不能为空");
			}

			if ($data["reply_pic"] == "") {
				$this->error("回复图片不能为空");
			}

			if ($data["remind_word"] == "") {
				$this->error("广告语不能为空");
			}
			else if (45 < strlen($data["remind_word"])) {
				$this->error("广告语不超过15个字");
			}

			if ($data["remind_img"] == "") {
				$this->error("广告图片不能为空");
			}

			if ($data["remind_link"] == "") {
				$this->error("广告跳转地址不能为空");
			}

			if ((int) $data["total_create"] <= 0) {
				$this->error("允许制作新闻总条数请输入大于0的整数");
			}

			if ((int) $data["total_create"] < (int) $data["day_create"]) {
				$this->error("每人每天允许制作的新闻条数必须小于允许的总新闻条数");
			}

			if ($data["end_time"] < $data["start_time"]) {
				$this->error("开始时间不能大于结束时间");
			}

			if (strpos($data["reply_pic"], "http") === false) {
				$data["reply_pic"] = $this->siteUrl . $data["reply_pic"];
			}

			if (($data["remind_img"] != "") && (strpos($data["remind_img"], "http") === false)) {
				$data["remind_img"] = $this->siteUrl . $data["remind_img"];
			}

			$configlist = M("frontpage_configure")->where(array("token" => $this->token))->select();

			if (!empty($configlist)) {
				foreach ($configlist as $val ) {
					if (((time() - $val["addtime"]) < $val["expires_in"]) && ($data["status"] == 1)) {
						$data["status"] = 1;
						break;
					}
					else {
						$data["status"] = 0;
					}
				}
			}
			else {
				$data["status"] = 0;
			}

			if ($data["id"] == "") {
				$data["token"] = $this->token;
				$action_id = M("frontpage_action")->add($data);

				if ($action_id) {
					$this->handleKeyword($action_id, "FrontPage", $data["keyword"]);
					$this->success("添加我要上头条活动成功", U("FrontPage/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("添加我要上头条活动失败");
					exit();
				}
			}
			else {
				$stat = M("frontpage_action")->where(array("id" => $data["id"]))->save($data);

				if ($stat) {
					$this->handleKeyword($data["id"], "FrontPage", $data["keyword"]);
					S($this->token . "_" . $data["id"] . "_frontaction", NULL);
					$this->success("修改我要上头条活动成功", U("FrontPage/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("修改我要上头条活动失败");
					exit();
				}
			}
		}

		if ($_GET["id"] != "") {
			$set = M("frontpage_action")->where("id = {$_GET["id"]}")->find();
			$this->assign("set", $set);
		}

		$this->display();
	}

	public function listconfigure()
	{
		$where = array("token" => $this->token);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["apikey"] = array("like", "%" . $search_word . "%");
		}

		$total = M("frontpage_configure")->where($where)->count();
		$Page = new Page($total, 10);
		$list = M("frontpage_configure")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("id desc")->select();
		$this->assign("list", $list);
		$this->assign("page", $Page->show());
		$this->assign("search_word", $search_word);
		$this->display();
	}

	public function addconfigure()
	{
		if (IS_POST) {
			$apikey = $this->_post("apikey", "trim");
			$secretkey = $this->_post("secretkey", "trim");
			$id = $this->_post("id", "intval");

			if ($apikey == "") {
				$this->error("请求接口的Key不能为空");
			}

			if ($secretkey == "") {
				$this->error("请求接口的Secret Key不能为空");
			}

			$exists = M("frontpage_configure")->where("apikey = '$apikey' and secretkey = '$secretkey'")->find();

			if (empty($exists)) {
				$getAccessToken = $this->getAccessToken(array("apikey" => $apikey, "secretkey" => $secretkey));

				if ($getAccessToken["status"] == "success") {
					$config = array();
					$congfig["apikey"] = $apikey;
					$congfig["secretkey"] = $secretkey;
					$congfig["access_token"] = $getAccessToken["access_token"];
					$congfig["expires_in"] = $getAccessToken["expires_in"];
					$congfig["token"] = $this->token;
					$congfig["addtime"] = $_SERVER["REQUEST_TIME"];

					if ($this->IsValid($getAccessToken["access_token"])) {
						$addconfigure = M("frontpage_configure")->add($congfig);

						if ($addconfigure) {
							$this->success("添加配置成功", U("FrontPage/listconfigure", array("token" => $this->token)));
							exit();
						}
						else {
							$this->error("添加配置失败");
						}
					}
					else {
						$this->error("创建应用后请开通语音合成服务");
					}
				}
				else if ($getAccessToken["status"] == "error") {
					$this->error("添加配置失败," . $getAccessToken["error_description"]);
				}
			}
			else if ($exists["token"] != $this->token) {
				$config = array();
				$congfig["apikey"] = $apikey;
				$congfig["secretkey"] = $secretkey;
				$congfig["access_token"] = $exists["access_token"];
				$congfig["expires_in"] = $exists["expires_in"];
				$congfig["token"] = $this->token;
				$congfig["addtime"] = $_SERVER["REQUEST_TIME"];
				$addconfigure = M("frontpage_configure")->add($congfig);

				if ($addconfigure) {
					$this->success("添加配置成功", U("FrontPage/listconfigure", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("添加配置失败");
				}
			}
			else {
				$this->error("配置信息已存在,请勿重复添加");
			}
		}

		if ($_GET["id"] != "") {
			$set = M("frontpage_configure")->where("id = {$_GET["id"]}")->find();
			$this->assign("set", $set);
		}

		$this->display();
	}

	public function delaction()
	{
		$id = (int) $_GET["id"];
		$exists = M("frontpage_action")->where(array("id" => $id))->find();

		if (!empty($exists)) {
			$del = M("frontpage_action")->where(array("id" => $id))->delete();

			if ($del) {
				$newslist = M("frontpage_makenews")->where(array("cid" => $id, "token" => $this->token))->field("voicepath")->select();

				foreach ($newslist as $key => $val ) {
					$filename = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $val["voicepath"]);
					$filename = getcwd() . $filename;

					if (@file_exists($filename)) {
						unlink($filename);
					}
				}

				$this->handleKeyword($id, "FrontPage", "", "", 1);
				S($this->token . "_" . $id . "_frontaction", NULL);
				$this->success("删除成功", U("FrontPage/index", array("token" => $this->token)));
				exit();
			}
		}
		else {
			$this->error("不存在该删除项");
			exit();
		}
	}

	public function updateconfigure()
	{
		$id = (int) $_GET["id"];
		$config = M("frontpage_configure")->where(array("id" => $id))->find();

		if (!empty($config)) {
			if (($_SERVER["REQUEST_TIME"] - $config["addtime"]) < $config["expires_in"]) {
				$this->error("access_token还未过期,无需更新");
			}

			$getAccessToken = $this->getAccessToken(array("apikey" => $config["apikey"], "secretkey" => $config["secretkey"]));

			if ($getAccessToken["status"] == "success") {
				$config = array();
				$congfig["access_token"] = $getAccessToken["access_token"];
				$congfig["expires_in"] = $getAccessToken["expires_in"];
				$congfig["addtime"] = $_SERVER["REQUEST_TIME"];
				$updateconfigure = M("frontpage_configure")->where(array("id" => $id))->save($congfig);

				if ($updateconfigure) {
					$this->success("更新访问令牌成功", U("FrontPage/listconfigure", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("更新访问令牌失败");
				}
			}
			else if ($getAccessToken["status"] == "error") {
				$this->error("更新访问令牌失败," . $getAccessToken["error_description"]);
			}
		}
	}

	public function delconfigure()
	{
		$id = (int) $_GET["id"];
		$exists = M("frontpage_configure")->where(array("id" => $id))->find();

		if (!empty($exists)) {
			$del = M("frontpage_configure")->where(array("id" => $id))->delete();

			if ($del) {
				$this->success("删除成功", U("FrontPage/listconfigure", array("token" => $this->token)));
				exit();
			}
		}
		else {
			$this->error("不存在该删除项");
			exit();
		}
	}

	public function fansnews()
	{
		$where = array("token" => $this->token, "news_type" => 2, "cid" => $_GET["id"]);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["news_title"] = array("like", "%" . $search_word . "%");
		}

		$newstotal = M("frontpage_makenews")->where($where)->count();
		$Page = new Page($newstotal, 15);
		$findnews = M("frontpage_makenews")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("id desc")->select();
		$this->assign("list", $findnews);
		$this->assign("page", $Page->show());
		$this->assign("search_word", $search_word);
		$this->assign("news_type", $news_type);
		$this->display();
	}

	public function definenews()
	{
		$where = array("token" => $this->token, "news_type" => 1, "cid" => $_GET["id"]);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["news_title"] = array("like", "%" . $search_word . "%");
		}

		$newstotal = M("frontpage_makenews")->where($where)->count();
		$Page = new Page($newstotal, 20);
		$findnews = M("frontpage_makenews")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("id desc")->select();
		$this->assign("list", $findnews);
		$this->assign("page", $Page->show());
		$this->assign("search_word", $search_word);
		$this->assign("type", $_GET["type"]);
		$this->assign("news_type", 1);
		$this->display();
	}

	public function systemnews()
	{
		$frontpagenews = include "./PigCms/Lib/ORG/FrontPageNews.php";
		$default_id = M("frontpage_action")->where(array("id" => (int) $_GET["id"]))->getField("defaultnews_hide");
		$default_id = explode(",", $default_id);

		foreach ($frontpagenews as $key => $val ) {
			if (in_array($val["id"], $default_id)) {
				$frontpagenews[$key]["status"] = 0;
			}
			else {
				$frontpagenews[$key]["status"] = 1;
			}

			$frontpagenews[$key]["type"] = 3;
		}

		$this->assign("frontpagenews", $frontpagenews);
		$this->assign("type", $type);
		$this->assign("news_type", 1);
		$this->display();
	}

	public function adddefinenews()
	{
		if (IS_POST) {
			$data = array();
			$data["news_title"] = $this->_post("news_title", "trim");
			$data["news_txt"] = $this->_post("news_txt", "trim");
			$data["spd"] = 7;
			$data["per"] = $this->_post("per", "intval");
			$data["status"] = $this->_post("status", "intval");
			$id = $this->_post("id", "intval");
			$data["create_time"] = $_SERVER["REQUEST_TIME"];
			$data["cid"] = $this->_post("cid", "intval");
			$data["news_type"] = 1;

			if ($data["news_title"] == "") {
				$this->error("新闻标题不能为空");
			}

			if ($data["news_txt"] == "") {
				$this->error("新闻内容不能为空");
			}

			if ($data["cid"] == "") {
				$this->error("活动ID不能为空");
			}

			if ($id == "") {
				$data["userid"] = 0;
				$data["token"] = $this->token;
				$addid = M("frontpage_makenews")->add($data);

				if ($addid) {
					$this->success("添加成功", U("FrontPage/definenews", array("token" => $this->token, "news_type" => 1, "id" => $data["cid"], "type" => "mynews")));
					exit();
				}
				else {
					$this->error("添加失败");
					exit();
				}
			}
			else {
				$updatenew = M("frontpage_makenews")->where(array("id" => $id))->save($data);

				if ($updatenew) {
					$this->success("修改成功", U("FrontPage/definenews", array("token" => $this->token, "news_type" => 1, "id" => $data["cid"], "type" => "mynews")));
					exit();
				}
				else {
					$this->error("修改失败");
					exit();
				}
			}
		}

		$getid = (int) $_GET["id"];

		if ($new = M("frontpage_makenews")->where(array("id" => $getid))->find()) {
			$this->assign("set", $new);
		}

		$this->assign("cid", $_GET["cid"]);
		$this->display();
	}

	public function deldefinenews()
	{
		$id = $_GET["id"];
		$cid = (int) $_GET["cid"];
		$news_type = (int) $_GET["news_type"];
		$default_type = trim($_GET["default_type"]);
		$default_id = array("d1", "d2", "d3", "d4", "d5", "d6", "d7", "d8", "d9", "d0", "a1", "a2", "a3", "a4", "a5", "a6", "a7", "a8", "a9", "b1", "b2", "b3", "b4", "b5");

		if (in_array($id, $default_id)) {
			$defaultnews_hide = M("frontpage_action")->where(array("id" => $cid))->getField("defaultnews_hide");

			if ($default_type == "close") {
				$defaultnews_hide = $defaultnews_hide . $id . ",";
				$msg = "关闭";
			}
			else {
				if (($default_type == "open") && ($defaultnews_hide != "")) {
					$defaultnews_hide = str_replace($id . ",", "", $defaultnews_hide);
					$msg = "开启";
				}
			}

			$update = M("frontpage_action")->where(array("id" => $cid))->save(array("defaultnews_hide" => $defaultnews_hide));

			if ($update) {
				$this->success($msg . "成功", U("FrontPage/systemnews", array("token" => $this->token, "news_type" => $news_type, "id" => $cid)));
				exit();
			}
			else {
				$this->error($msg . "失败");
				exit();
			}
		}
		else {
			$id = (int) $_GET["id"];
		}

		$exists = M("frontpage_makenews")->where(array("id" => $id))->find();

		if (!empty($exists)) {
			$del = M("frontpage_makenews")->where(array("id" => $id))->delete();

			if ($del) {
				$filename = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $exists["voicepath"]);
				$filename = getcwd() . $filename;

				if (@file_exists($filename)) {
					unlink($filename);
				}

				if ($news_type == 1) {
					$this->success("删除成功", U("FrontPage/definenews", array("token" => $this->token, "news_type" => $news_type, "id" => $cid, "type" => "mynews")));
					exit();
				}
				else {
					$this->success("删除成功", U("FrontPage/fansnews", array("token" => $this->token, "news_type" => $news_type, "id" => $cid)));
					exit();
				}
			}
		}
		else {
			$this->error("不存在该删除项");
			exit();
		}
	}

	public function uploadimg()
	{
		if (IS_POST) {
			$data = array();

			if ($_POST["default_img"] == "") {
				$this->error("默认事件的图片不能为空");
			}

			if (($_POST["news_id"] == "") || ($_POST["token"] == "") || ($_POST["cid"] == "")) {
				$this->error("参数错误");
			}

			$default_img = M("frontpage_newsimg")->where(array("news_id" => $_POST["news_id"], "cid" => $_POST["cid"], "token" => $_POST["token"]))->getField("default_img");

			if ($default_img == "") {
				$data["news_id"] = $_POST["news_id"];
				$data["token"] = $_POST["token"];
				$data["cid"] = $_POST["cid"];
				$data["default_img"] = $_POST["default_img"];
				$add = M("frontpage_newsimg")->add($data);

				if ($add) {
					$this->success("添加图片成功", U("FrontPage/systemnews", array("token" => $data["token"], "type" => "default", "id" => $data["cid"])));
					exit();
				}
				else {
					$this->error("添加图片失败");
				}
			}
			else {
				$update = M("frontpage_newsimg")->where(array("news_id" => $_POST["news_id"], "cid" => $_POST["cid"], "token" => $_POST["token"]))->save(array("default_img" => $_POST["default_img"]));

				if ($update) {
					$this->success("修改图片成功", U("FrontPage/systemnews", array("token" => $_POST["token"], "type" => "default", "id" => $_POST["cid"])));
					exit();
				}
				else {
					$this->error("修改图片失败");
				}
			}
		}

		$id = $_GET["id"];
		$cid = (int) $_GET["cid"];
		$token = trim($_GET["token"]);
		$frontpagenews = include "./PigCms/Lib/ORG/FrontPageNews.php";

		foreach ($frontpagenews as $key => $val ) {
			if ($id == $val["id"]) {
				$assignNews = $val;
				break;
			}
		}

		$imgstr = M("frontpage_newsimg")->where(array("news_id" => $id, "token" => $token, "cid" => $cid))->getField("default_img");
		$this->assign("news", $assignNews);
		$this->assign("default_img", $imgstr);
		$this->assign("news_id", $id);
		$this->assign("token", $token);
		$this->assign("cid", $cid);
		$this->display();
	}

	public function batch_close()
	{
		$ids = $_POST["ids"];
		$cid = (int) $_GET["cid"];
		$token = trim($_GET["token"]);

		if (empty($ids)) {
			exit("fail");
		}

		$defaultnews_hide = M("frontpage_action")->where(array("id" => $cid))->getField("defaultnews_hide");
		$ids_array = explode(",", $defaultnews_hide);

		foreach ($ids as $key => $val ) {
			if (in_array($val, $ids_array)) {
				unset($ids[$key]);
			}
		}

		if (empty($ids)) {
			$close_ids = "";
		}
		else {
			$close_ids = implode(",", $ids);
		}

		$hide_ids = $defaultnews_hide . "," . $close_ids;
		$update = M("frontpage_action")->where(array("id" => $cid))->save(array("defaultnews_hide" => trim($hide_ids, ",")));

		if ($update) {
			exit("done");
		}
		else {
			exit("fail");
		}
	}

	public function unbatch_open()
	{
		$ids = $_POST["unids"];
		$cid = (int) $_GET["cid"];
		$token = trim($_GET["token"]);

		if (empty($ids)) {
			exit("fail");
		}

		$defaultnews_hide = M("frontpage_action")->where(array("id" => $cid))->getField("defaultnews_hide");

		if ($defaultnews_hide == "") {
			exit("done");
		}

		$ids_array = explode(",", $defaultnews_hide);
		$open_id = array_intersect($ids, $ids_array);

		foreach ($ids_array as $key => $val ) {
			if (in_array($val, $open_id)) {
				unset($ids_array[$key]);
			}
		}

		if (empty($ids_array)) {
			$close_ids = "";
		}
		else {
			$close_ids = implode(",", $ids_array);
		}

		$update = M("frontpage_action")->where(array("id" => $cid))->save(array("defaultnews_hide" => trim($close_ids, ",")));

		if ($update) {
			exit("done");
		}
		else {
			exit("fail");
		}
	}

	public function unbatch_delfansnews()
	{
		$ids = $_POST["ids"];

		if (empty($ids)) {
			return false;
		}

		foreach ($ids as $id ) {
			$exists = M("frontpage_makenews")->where(array("id" => $id))->find();

			if ($exists) {
				$stat = M("frontpage_makenews")->where(array("id" => $id))->delete();
				$filename = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $exists["voicepath"]);
				$filename = getcwd() . $filename;

				if (@file_exists($filename)) {
					unlink($filename);
				}

				if (!$stat) {
					break;
				}
			}
			else {
				continue;
			}
		}

		if ($stat) {
			exit("done");
		}
		else {
			exit("fail");
		}
	}

	private function getAccessToken($post)
	{
		if (($post["apikey"] == "") && ($post["secretkey"] == "")) {
			return false;
		}

		$url = "https://openapi.baidu.com/oauth/2.0/token";
		$params["post"] = array();
		$params["post"]["grant_type"] = "client_credentials";
		$params["post"]["client_id"] = trim($post["apikey"]);
		$params["post"]["client_secret"] = trim($post["secretkey"]);
		$response = HttpClient::getInstance()->setOption(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0)->post($url, $params);
		$result = json_decode($response, true);
		if (($result["access_token"] != "") && ($result["expires_in"] != "")) {
			return array("status" => "success", "access_token" => $result["access_token"], "expires_in" => $result["expires_in"]);
		}
		else {
			return array("status" => "error", "error" => $result["error"], "error_description" => $result["error_description"]);
		}
	}

	public function IsValid($access_token)
	{
		if ($access_token == "") {
			return false;
		}

		$url = "http://tsn.baidu.com/text2audio";
		$params["post"] = array();
		$params["post"]["tex"] = "test";
		$params["post"]["lan"] = "zh";
		$params["post"]["tok"] = $access_token;
		$params["post"]["ctp"] = 1;
		$params["post"]["cuid"] = md5(substr(time() . $access_token, 0, 32));
		$params["post"]["per"] = 1;
		$params["post"]["spd"] = 5;
		$binary_file = HttpClient::getInstance()->post($url, $params);
		$result = json_decode($binary_file, true);
		if (($result["err_no"] != "") && ($result["err_msg"] != "")) {
			return false;
		}
		else {
			return true;
		}
	}
}

echo "\r\n";

?>
