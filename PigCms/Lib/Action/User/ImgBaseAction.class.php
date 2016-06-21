<?php

class ImgBaseAction extends UserAction
{
	public function index()
	{
		$db = D("Img");
		$token = session("token");
		import("ORG.Util.Page");
		$where_page["token"] = $this->token;
		if (IS_POST && ($_POST["search"] != "")) {
			$search = trim($this->_post("search"));
			$where = "token = '$token' AND title like '%$search%'";
			$where_page["search"] = $_POST["search"];
		}
		else if (!empty($_GET["search"])) {
			$search = trim($this->_get("search"));
			$where = "token = '$token' AND title like '%$search%'";
			$where_page["search"] = $_GET["search"];
		}
		else {
			$where["token"] = $token;
		}

		$result = $this->_getGroup();

		foreach ($result["groups"] as $key => $value ) {
			$group[$value["id"]] = $value["name"];
		}

		$this->assign("group", $group);
		$count = $db->where($where)->count();
		$page = new Page($count, 20);

		foreach ($where_page as $key => $val ) {
			$pagethis->parameter .= "$key=" . urlencode($val) . "&";
		}

		$info = $db->where($where)->order("usort DESC")->limit($page->firstRow . "," . $page->listRows)->select();
		$this->assign("page", $page->show());
		$this->assign("info", $info);
	}

	private function _getGroup()
	{
		$apiOauth = new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($this->wxuser["appid"]);
		$url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=" . $access_token;
		$result = HttpClient::getInstance()->get($url);
		$result = json_decode($result, true);
		return $result;
	}

	private function _getWechatGroupHtml($wechat_group)
	{
		if (2 < $this->wxuser["winxintype"]) {
			if ((NULL !== $wechat_group) && ("" !== $wechat_group)) {
				$group = explode(",", $wechat_group);
			}

			$result = $this->_getGroup();

			foreach ($result["groups"] as $key => $value ) {
				if (in_array($value["id"], $group)) {
					$html .= "<label><input type=\"checkbox\" checked=\"checked\" name=\"wechat_group[]\" value=\"" . $value["id"] . "\" />" . $value["name"] . "</label>　";
				}
				else {
					$html .= "<label><input type=\"checkbox\" name=\"wechat_group[]\" value=\"" . $value["id"] . "\" />" . $value["name"] . "</label>　";
				}
			}

			return $html;
		}
		else {
			return false;
		}
	}

	public function add()
	{
		$classify_db = M("Classify");
		$class = $classify_db->field("fid,id,name,concat(path,'-',id) as bpath")->order("bpath ASC")->where(array("token" => session("token")))->select();

		foreach ($class as $k => $v ) {
			$total = (count(explode("-", $v["bpath"])) - 2) * 10;

			for ($i = 0; $i < $total; $i++) {
				$class[$k]["fg"] .= "-";
			}

			$id = $v["id"];
			$fidArr[] = $classify_db->field("distinct(fid)")->where(array("token" => session("token"), "fid" => $id))->select();

			if (!$fidArr[$k][0]["fid"] == NULL) {
				$fid[] = $fidArr[$k][0]["fid"];
			}
		}

		if ($class == false) {
			$this->error("请先添加3G网站分类", U("Classify/index", array("token" => session("token"))));
		}

		$this->assign("group", $this->_getWechatGroupHtml());
		$this->assign("info", $class);
		$this->assign("fid", $fid);
	}

	public function edit()
	{
		$db = M("Classify");
		$where["token"] = session("token");
		$where["id"] = $this->_get("id", "intval");
		$where["uid"] = session("uid");
		$res = D("Img")->where($where)->find();
		$thisClass = M("Classify")->field("id,path")->where(array("id" => $res["classid"]))->find();
		$path = $thisClass["path"] . "-" . $thisClass["id"];
		$tree = explode("-", $path);

		foreach ($tree as $k => $v ) {
			if ($v != 0) {
				$name[] = $db->field("name")->where(array("token" => session("token"), "id" => $v))->find();
			}
			else {
				unset($tree[$k]);
			}
		}

		foreach ($name as $key => $val ) {
			$t .= $val["name"] . " >> ";
			$lastName = $val["name"];
		}

		$t = rtrim($t, " >> ");
		$this->assign("classValue", array_pop($tree) . "," . $lastName);
		$this->assign("group", $this->_getWechatGroupHtml($res["wechat_group"]));
		$this->assign("thisClass", $thisClass);
		$this->assign("classtree", $t);
		$this->assign("fid", $fid);
		$this->assign("info", $res);
		$this->assign("class", $class);
		$this->assign("res", $class);
	}

	public function del()
	{
		$where["id"] = $this->_get("id", "intval");
		$where["token"] = $this->token;
		$list = M("Img")->where($where)->find();

		if (D("Img")->where($where)->delete()) {
			$this->handleKeyword(intval($_GET["id"]), "Img", "", "", 1);
			$this->success("操作成功", U("Img/index"));
		}
		else {
			$this->error("操作失败", U("Img/index"));
		}
	}

	public function insert()
	{
		$lastid = M("Img")->where(array("token" => session("token")))->order("usort DESC")->limit(1)->getField("usort");

		if ($this->dwzQuery(array("tinyurl" => $_POST["url"]))) {
			$this->error("禁止使用短网址");
		}

		$_POST["url"] = $this->replaceUrl($_POST["url"], array(
	"query" => array("wecha_id" => "{wechat_id}")
	));
		$_POST["usort"] = $lastid + 1;
		$_POST["info"] = filterWeiXinContent($_POST["info"]);
		$_POST["wechat_group"] = nulltoblank(implode(",", $_POST["wechat_group"]));
		$usersdata = M("Users");
		$usersdata->where(array("id" => $this->user["id"]))->setInc("diynum");
		if ($_POST["pc_cat_id"] && $_POST["pc_show"]) {
			$database_pc_news_category = D("Pc_news_category");
			$condition_pc_news_category["cat_id"] = $_POST["pc_cat_id"];
			$condition_pc_news_category["token"] = session("token");
			$now_category = $database_pc_news_category->field(true)->where($condition_pc_news_category)->find();

			if (empty($now_category)) {
				$this->error("检测到与该分类的电脑网站文章分类不存在！请您编辑该分类解绑电脑网站文章分类后再重试。");
			}

			$database_pc_news = D("Pc_news");
			$data_pc_news["cat_id"] = $_POST["pc_cat_id"];
			$data_pc_news["token"] = session("token");
			$data_pc_news["title"] = $this->_post("title");
			$data_pc_news["info"] = $this->_post("text");
			$data_pc_news["pic"] = $this->_post("pic");
			$data_pc_news["content"] = $this->_post("info", "stripslashes,htmlspecialchars_decode");
			$data_pc_news["time"] = $_SERVER["REQUEST_TIME"];

			if (!$database_pc_news->data($data_pc_news)->add()) {
				$this->error("添加到电脑网站文章失败！请重试。");
			}
		}

		$this->syncNews("add");
		$this->all_insert();
	}

	public function syncNews($type)
	{
		if (!D("Img")->isOpenSync($this->wxuser)) {
			return false;
		}

		$material = new Material($this->wxuser);

		if (isset($_POST["pic"])) {
			$result = M("Files")->where(array("url" => $_POST["pic"]))->find();
			$thumb_media_id = $result["media_id"];

			if (empty($thumb_media_id)) {
				if (strpos($_POST["pic"], C("site_url")) !== false) {
					$params["form"] = "data";
					$params["post"]["media"] = str_replace(C("site_url"), ".", $_POST["pic"]);
				}
				else {
					$imgStr = HttpClient::getInstance()->get($_POST["pic"]);

					if (!$imgStr) {
						$this->error("您的图文消息没有封面或者封面获取不到" . $_POST["pic"]);
					}

					$file = RUNTIME_PATH . basename($_POST["pic"]);
					file_put_contents($file, $imgStr);
					$params["form"] = "data";
					$params["post"]["media"] = $file;
				}

				$params["post"]["media"] = "@" . $_SERVER["DOCUMENT_ROOT"] . str_replace(array("./"), array("/"), $params["post"]["media"]);
				$params["post"]["type"] = "image";
				$image = $material->add($params);
				unlink($file);
				$thumb_media_id = $image->media_id;

				if ($result) {
					$result["sync_url"] = $image->url;
					$result["media_id"] = $image->media_id;
					$id = $result["id"];
					unset($result["id"]);
					M("Files")->where(array("id" => $id))->save($result);
				}
			}
		}

		$params = array();
		$content = str_replace("&amp;", "&", $this->_post("info"));
		$content = html_entity_decode($content);
		$content = preg_replace("/<img [^>]*srctitle=['\\\"]([^'\\\"]+)[^>]*>/", "<img src=\"\$1\" />", $content);
		$content = str_replace(array("\"/upload"), array("\"" . C("site_url") . "/upload"), $content);
		$params["post"]["articles"] = array(
			array("title" => $this->_post("title"), "thumb_media_id" => $thumb_media_id, "author" => $this->_post("writer"), "digest" => $this->_post("text"), "show_cover_pic" => $this->_post("showpic"), "content" => $content, "content_source_url" => $this->convertLink($this->_post("url")))
			);
		$data = M("Img")->where(array("id" => $this->_post("id")))->find();

		if ($data["media_id"]) {
			$params["post"]["articles"] = $params["post"]["articles"][0];
			$params["post"]["media_id"] = $data["media_id"];
			$params["post"] = json_encode($params["post"], 256);
			$return = $material->updateNews($params);
		}
		else {
			$params["post"] = json_encode($params["post"], 256);
			$return = $material->addNews($params);
			$_POST["media_id"] = $return->media_id;
		}

		return $return;
	}

	public function upsave()
	{
		if ($this->dwzQuery(array("tinyurl" => $_POST["url"]))) {
			$this->error("禁止使用短网址");
		}

		$_POST["url"] = $this->replaceUrl($_POST["url"], array(
	"query" => array("wecha_id" => "{wechat_id}")
	));
		$_POST["info"] = filterWeiXinContent($_POST["info"]);
		$_POST["wechat_group"] = nulltoblank(implode(",", $_POST["wechat_group"]));
		$this->syncNews("update");
		$this->all_save();
	}

	public function editClass()
	{
		$token = $this->token;
		$db = M("Classify");
		$id = (int) $this->_get("id");
		$class = $db->where("token = '$token' AND fid = $id")->select();

		foreach ($class as $k => $v ) {
			$fid = $v["id"];
			$class[$k]["sub"] = $db->where("token = '$token' AND fid = $fid")->field("id,name")->select();
			$class[$k]["pc_cat_id"] = intval($class[$k]["pc_cat_id"]);
		}

		$this->assign("class", $class);
	}

	public function editUsort()
	{
		$token = $this->_post("token", "htmlspecialchars");
		unset($_POST["__hash__"]);

		foreach ($_POST as $k => $v ) {
			$k = str_replace("usort", "", $k);
			$data[$k] = $v;
			M("Img")->where(array("token" => $token, "id" => $k))->setField("usort", $v);
		}

		$this->success("保存成功");
	}

	public function multiImgDel()
	{
		$id = (int) $this->_get("id");
		if (M("Img_multi")->where(array("token" => $this->token, "id" => $id))->delete() && M("Keyword")->where(array("token" => $this->token, "pid" => $id))->delete()) {
			$this->success("删除成功");
		}
		else {
			$this->error("删除失败，请稍后再试~");
		}
	}

	public function multi()
	{
		if ((int) $this->_get("tip") == 2) {
			$multi = M("Img_multi");
			$img = M("Img");
			$where["token"] = $this->token;
			$count = $multi->where($where)->count();
			$page = new Page($count, 20);
			$list = $multi->where($where)->limit($page->firstRow . "," . $page->listRows)->order("id DESC")->select();
			$p = $page->show();

			foreach ($list as $k => $v ) {
				$id = explode(",", $v["imgs"]);

				foreach ($id as $key => $val ) {
					$title[$k][$val] = $img->where(array("token" => $this->token, "id" => $val))->getField("title");
				}

				$list[$k]["title"] = $title[$k];
			}

			$this->assign("list", $list);
			$this->assign("page", $p);
		}
	}

	public function multiSave()
	{
		$keywords = $this->_post("keywords", "trim");
		$imgs = $this->_post("imgids");
		$imgs = trim($imgs, ",");

		if (!$keywords) {
			$this->error("请填写关键词。");
		}

		if (!$imgs) {
			$this->error("请选择图文消息。");
		}

		if (M("Img_multi")->where(array("token" => $this->token, "keywords" => $keywords))->getField(id)) {
			$this->error("这个关键词已经存在了，请换个关键词哦。");
		}

		$data["imgs"] = $imgs;
		$data["keywords"] = $keywords;
		$data["token"] = $this->token;
		$multi = M("Img_multi");

		if ($multiid = $multi->add($data)) {
			$keyInfo["keyword"] = $keywords;
			$keyInfo["token"] = $this->token;
			$keyInfo["module"] = "Multi";
			$keyInfo["pid"] = $multiid;

			if (M("Keyword")->add($keyInfo)) {
				$this->success("保存成功", U("Img/multi", array("tip" => 2)));
			}
		}
		else {
			$this->error("保存失败，请稍后再试~");
		}
	}
}


?>
