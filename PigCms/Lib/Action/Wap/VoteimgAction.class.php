<?php

class VoteimgAction extends WapAction
{
	public $action_id;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->action_id = $this->_request("id", "intval");
		D("Userinfo")->convertFake(M("voteimg_users"), array("token" => $this->token, "wecha_id" => $this->wecha_id, "fakeopenid" => $this->fakeopenid));

		if ($this->fakeopenid) {
			$userinfo = M("userinfo")->where(array("fakeopenid" => $this->fakeopenid, "token" => $this->token))->order("id asc")->find();
			if (!empty($userinfo) && ($this->wecha_id != $userinfo["wecha_id"])) {
				exit("操作失败");
			}
		}
	}

	public function index()
	{
		if (empty($this->action_id) || empty($this->token)) {
			$this->error("非法操作");
			exit();
		}

		$action_info = M("voteimg")->where(array("id" => $this->action_id))->find();

		if (!empty($action_info)) {
			if ($action_info["display"] != 1) {
				$this->error("该活动未开启");
				exit();
			}

			$this->assign("action_info", $action_info);
		}
		else {
			$this->error("该活动不存在");
			exit();
		}

		$this->add_users($action_info["territory_limit"]);
		$this->notice($action_info);
		$where_index = array();
		$where_index["vote_id"] = (int) $this->action_id;
		$where_index["token"] = $this->token;
		$where_index["check_pass"] = 1;
		$type = trim($this->_request("type"));
		$order = (empty($type) || ($type == "new") ? "baby_id desc" : "baby_id asc");
		$key_word = trim($this->_request("key_word"));

		if (!empty($key_word)) {
			C("TOKEN_ON", false);

			if (is_numeric($key_word)) {
				$where_index["baby_id"] = (int) $key_word;
			}
			else {
				$where_index["vote_title"] = array("like", "%$key_word%");
			}

			$item = M("voteimg_item")->where($where_index)->select();

			if (count($item) == 1) {
				if ($item[0]["jump_url"] != "") {
					if ((strpos($item[0]["jump_url"], "{siteUrl}") !== false) || (strpos($item[0]["jump_url"], "{wechat_id}") !== false)) {
						$jump_url = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $item[0]["jump_url"]);
						$jump_url = htmlspecialchars_decode($jump_url);
					}
					else {
						$jump_url = $item[0]["jump_url"];
					}
				}
				else {
					$jump_url = U("Voteimg/popup_view", array("id" => $item[0]["vote_id"], "token" => $item[0]["token"], "item_id" => $item[0]["id"]));
				}

				redirect($jump_url);
				exit();
			}
		}

		if ($action_info["page_type"] == "waterfall") {
			$list = M("voteimg_item")->where($where_index)->order($order)->limit(0, 10)->select();
		}
		else {
			$total = M("voteimg_item")->where($where_index)->count();
			$Page = new Page($total, 10);
			$list = M("voteimg_item")->where($where_index)->order($order)->limit($Page->firstRow . "," . $Page->listRows)->select();
			$Page->setConfig("prev", "<<");
			$Page->setConfig("next", ">>");
			$Page->setConfig("theme", "%upPage% %linkPage% %downPage%");
			$this->assign("page", $Page->show());
		}

		foreach ($list as $key => $val ) {
			if (strpos($val["vote_img"], ";") !== false) {
				$vote_img = explode(";", $val["vote_img"]);
				$list[$key]["vote_img"] = end($vote_img);
			}
			else {
				$list[$key]["vote_img"] = $val["vote_img"];
			}

			if ($val["jump_url"] != "") {
				if ((strpos($val["jump_url"], "{siteUrl}") !== false) || (strpos($val["jump_url"], "{wechat_id}") !== false)) {
					$list[$key]["jump_url"] = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $val["jump_url"]);
				}
				else {
					$list[$key]["jump_url"] = $val["jump_url"];
				}
			}
			else {
				$list[$key]["jump_url"] = U("Voteimg/popup_view", array("id" => $val["vote_id"], "token" => $val["token"], "item_id" => $val["id"]));
			}
		}

		$this->get_head_content($action_info["default_skin"]);
		$this->clear_vote_day();
		$this->assign("alllist", $list);
		$this->assign("id", $this->action_id);
		$this->assign("token", $this->token);
		$this->assign("key_word", $key_word);
		$this->assign("type", $type);
		$this->assign("page_type", $action_info["page_type"]);
		$this->assign("imgUrl", $action_info["reply_pic"]);
		$this->check_expire($action_info);
		$this->display($this->get_tplname($action_info));
	}

	public function getList()
	{
		$num = $this->_get("num", "intval");
		$id = $this->_get("id", "intval");
		$key_word = $this->_get("key_word");
		$type = $this->_get("type", "trim");
		$order = (empty($type) || ($type == "new") ? "baby_id desc" : "baby_id asc");
		$where = "vote_id = $id AND token = '$this->token' AND check_pass = 1";

		if (!empty($key_word)) {
			if (is_numeric($key_word)) {
				$where .= " AND baby_id = $key_word";
			}
			else {
				$where .= " AND vote_title like '%$key_word%'";
			}
		}

		$item = M("voteimg_item")->where($where)->order($order)->limit(10 + ((int) $num * 4), 4)->select();

		foreach ($item as $key => $val ) {
			if (strpos($val["vote_img"], ";") !== false) {
				$vote_img = explode(";", $val["vote_img"]);
				$item[$key]["vote_img"] = end($vote_img);
			}
			else {
				$item[$key]["vote_img"] = $val["vote_img"];
			}

			if ($val["jump_url"] != "") {
				if ((strpos($val["jump_url"], "{siteUrl}") !== false) || (strpos($val["jump_url"], "{wechat_id}") !== false)) {
					$jump_url = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $val["jump_url"]);
					$item[$key]["jump_url"] = htmlspecialchars_decode($jump_url);
				}
				else {
					$item[$key]["jump_url"] = $val["jump_url"];
				}
			}
			else {
				$item[$key]["jump_url"] = U("Voteimg/popup_view", array("id" => $val["vote_id"], "token" => $val["token"], "item_id" => $val["id"]));
			}
		}

		if ($item) {
			exit(json_encode(array("status" => "SUCCESS", "data" => $item)));
		}
		else {
			exit(json_encode(array("status" => "FAIL", "data" => $item)));
		}
	}

	public function vote()
	{
		$vote_id = $this->_get("vote_id", "intval");
		$id = $this->_get("id", "intval");

		if (empty($this->wecha_id)) {
			echo json_encode(array("status" => "fail", "data" => "投票失败,参数错误"));
			exit();
		}

		$voteimg = M("voteimg")->where(array("id" => $vote_id))->find();
		if (!empty($voteimg) && ($voteimg["display"] != 1)) {
			echo json_encode(array("status" => "fail", "data" => "投票失败,活动已关闭"));
			exit();
		}
		else if (empty($voteimg)) {
			echo json_encode(array("status" => "fail", "data" => "投票失败,活动不存在"));
			exit();
		}

		if ($voteimg["end_time"] < $_SERVER["REQUEST_TIME"]) {
			echo json_encode(array("status" => "fail", "data" => "投票失败,活动已结束"));
			exit();
		}
		else if ($_SERVER["REQUEST_TIME"] < $voteimg["start_time"]) {
			echo json_encode(array("status" => "fail", "data" => "投票失败,活动未开始"));
			exit();
		}

		if ($voteimg["self_status"] != 1) {
			$wecha_id = M("voteimg_item")->where(array("id" => $id, "check_pass" => 1))->getField("wecha_id");
			if (!empty($wecha_id) && ($this->wecha_id == $wecha_id)) {
				echo json_encode(array("status" => "fail", "data" => "自己不能给自己投票"));
				exit();
			}
		}

		$where = array("vote_id" => $vote_id, "token" => $this->token, "wecha_id" => $this->wecha_id);
		$vote_user = M("voteimg_users")->where($where)->find();

		if (0 < (int) $voteimg["limit_vote_day"]) {
			if ($voteimg["limit_vote_day"] <= $vote_user["votenum_day"]) {
				echo json_encode(array("status" => "fail", "data" => "超过今日投票数限制"));
				exit();
			}
		}

		if (0 < (int) $voteimg["limit_vote_item"]) {
			$vote_today = explode(",", $vote_user["vote_today"]);
			$today_count = array_count_values($vote_today);

			if ($voteimg["limit_vote_item"] <= $today_count[$id]) {
				echo json_encode(array("status" => "fail", "data" => "超今日票数限制请投其他选项"));
				exit();
			}
		}

		if (0 < (int) $voteimg["limit_vote"]) {
			if ($voteimg["limit_vote"] <= $vote_user["votenum"]) {
				echo json_encode(array("status" => "fail", "data" => "超过总投票数限制"));
				exit();
			}
		}

		$u = array();
		$u["item_id"] = trim($vote_user["item_id"] . "," . $id, ",");
		$u["votenum"] = array("exp", "votenum+1");
		$u["votenum_day"] = array("exp", "votenum_day+1");
		$u["vote_today"] = trim($vote_user["vote_today"] . "," . $id, ",");
		$u["vote_time"] = $_SERVER["REQUEST_TIME"];
		$update_user = M("voteimg_users")->where($where)->save($u);

		if ($update_user) {
			$d = array();
			$d["vote_count"] = array("exp", "vote_count+1");
			$update_item = M("voteimg_item")->where(array("id" => $id))->save($d);

			if ($update_item) {
				if ($voteimg["limit_vote_day"] == 0) {
					echo json_encode(array(
	"status" => "done",
	"data"   => array("left_vote_day" => "")
	));
					exit();
				}
				else {
					echo json_encode(array(
	"status" => "done",
	"data"   => array("left_vote_day" => $voteimg["limit_vote_day"] - $vote_user["votenum_day"] - 1)
	));
					exit();
				}
			}
			else {
				echo json_encode(array("status" => "fail", "data" => "投票失败"));
				exit();
			}
		}
		else {
			echo json_encode(array("status" => "fail", "data" => "投票失败"));
			exit();
		}
	}

	private function add_users($territory_limit)
	{
		$voter = M("voteimg_users")->where(array("vote_id" => $this->action_id, "token" => $this->token, "wecha_id" => $this->wecha_id))->find();
		if ($this->wecha_id && empty($voter)) {
			$data = array("vote_id" => (int) $this->action_id, "item_id" => "", "wecha_id" => $this->wecha_id, "nick_name" => !empty($this->fans["wechaname"]) ? $this->fans["wechaname"] : "no", "votenum" => 0, "votenum_day" => 0, "phone" => !empty($this->fans["tel"]) ? $this->fans["tel"] : "no", "vote_time" => $_SERVER["REQUEST_TIME"], "token" => $this->token);

			if ($territory_limit == 1) {
				$data["phone"] = "no";
			}

			$user_id = M("voteimg_users")->add($data);
			$_SESSION["user_id"] = $user_id;
		}
		else {
			if (!empty($this->fans["tel"]) && ($territory_limit != 1)) {
				M("voteimg_users")->where(array("vote_id" => $this->action_id, "token" => $this->token, "wecha_id" => $this->wecha_id))->save(array("phone" => $this->fans["tel"], "nick_name" => $this->fans["wechaname"]));
			}
		}
	}

	public function apply()
	{
		if (IS_POST) {
			$img = (!empty($_POST["inputimg"]) ? implode(";", $_POST["inputimg"]) : "");
			if (empty($_POST["vote_id"]) || empty($_POST["token"])) {
				$this->del_upload($img);
				$this->error("非法操作");
				exit();
			}

			if (empty($this->wecha_id)) {
				$this->del_upload($img);
				$this->error("报名失败,参数错误。");
				exit();
			}

			$vote_img = M("voteimg")->where(array("id" => $_POST["vote_id"], "display" => 1))->find();

			if (!$this->notice($vote_img)) {
				$this->del_upload($img);
				$this->error("请先关注、注册");
				exit();
			}

			if ($vote_img["apply_end_time"] < $_SERVER["REQUEST_TIME"]) {
				$this->del_upload($img);
				$this->error("报名已截止,谢谢您的参与");
				exit();
			}

			if ($_SERVER["REQUEST_TIME"] < $vote_img["apply_start_time"]) {
				$this->del_upload($img);
				$this->error("报名还未开始,请耐心等待");
				exit();
			}

			if (empty($_POST["inputimg"])) {
				$this->del_upload($img);
				$this->error("请上传图片");
				exit();
			}

			if (5 < count($_POST["inputimg"])) {
				$this->del_upload($img);
				$this->error("最多上传5张");
				exit();
			}

			if (($vote_img["is_register"] == 0) && empty($_POST["contact"])) {
				$this->del_upload($img);
				$this->error("联系方式不能为空");
				exit();
			}

			if (!empty($_POST["contact"]) && !preg_match("/^([0-9]){6,}$/", $_POST["contact"])) {
				$this->del_upload($img);
				$this->error("手机号格式不正确");
				exit();
			}

			$exist = M("voteimg_item")->where(array("vote_id" => $_POST["vote_id"], "token" => $_POST["token"], "wecha_id" => $this->wecha_id))->find();

			if ($exist) {
				$this->del_upload($img);
				$this->error("已经报过名了,请勿重复报名");
				exit();
			}

			if (24 < strlen($this->_post("vote_title", "trim"))) {
				$this->del_upload($img);
				$this->error("输入的标题字数不能超过8个字,请修改后重新提交");
				exit();
			}

			$data = array();
			$data["vote_title"] = $this->_post("vote_title", "trim");
			$data["introduction"] = $this->_post("introduction", "trim");
			$data["manifesto"] = $this->_post("manifesto", "trim");
			$data["contact"] = (empty($_POST["contact"]) ? $this->fans["tel"] : $this->_post("contact"));
			$data["vote_count"] = 0;
			$data["upload_time"] = $_SERVER["REQUEST_TIME"];
			$data["check_pass"] = 0;
			$data["upload_type"] = 0;
			$data["token"] = $_POST["token"];
			$data["vote_id"] = $_POST["vote_id"];
			$data["baby_id"] = 0;
			$data["vote_img"] = trim(implode(";", $_POST["inputimg"]), ";");
			$data["wecha_id"] = $this->wecha_id;
			$insert = M("voteimg_item")->add($data);

			if ($insert) {
				$this->success("申请报名成功,等待审核", U("Voteimg/index", array("id" => $_POST["vote_id"], "token" => $_POST["token"])));
				exit();
			}
			else {
				$this->error("申请报名失败");
				exit();
			}
		}

		$vote_id = $this->_get("id", "intval");
		$action_info = M("voteimg")->where(array("id" => $vote_id))->find();

		if (!empty($action_info)) {
			if ($action_info["display"] != 1) {
				$this->error("该活动未开启");
				exit();
			}

			$this->assign("action_info", $action_info);
		}
		else {
			$this->error("该活动不存在");
			exit();
		}

		$this->notice($action_info);
		$this->get_head_content($action_info["default_skin"]);
		$this->assign("vote_id", $this->action_id);
		$this->assign("token", $this->token);
		$this->assign($action_info);
		$this->assign("imgUrl", $action_info["reply_pic"]);
		$this->check_expire($action_info);

		if (C("up_size")) {
			$maxSize = C("up_size");
		}
		else {
			$maxSize = 2048;
		}

		$this->assign("maxSize", $maxSize);
		$this->display($this->get_tplname($action_info));
	}

	public function popup_view()
	{
		$is_share = $this->_get("is_share");
		$key_word = $this->_get("key_word");
		$vote_id = $this->_get("id");
		if (empty($vote_id) || empty($this->token)) {
			$this->error("非法操作");
			exit();
		}

		$action_info = M("voteimg")->where(array("id" => $vote_id))->find();

		if (!empty($action_info)) {
			if ($action_info["display"] != 1) {
				$this->error("该活动未开启");
				exit();
			}

			$this->assign("action_info", $action_info);
		}
		else {
			$this->error("该活动不存在");
			exit();
		}

		$this->add_users($action_info["territory_limit"]);
		$this->notice($action_info);
		$where = "vote_id = $vote_id AND token = '$this->token' AND check_pass = 1";

		if (!empty($key_word)) {
			C("TOKEN_ON", false);
			$where .= " AND baby_id = " . (int) $key_word;
		}
		else {
			$item_id = $this->_get("item_id", "intval");

			if (!$item_id) {
				exit("加载失败");
			}

			$where = "id = $item_id";
		}

		$item = M("voteimg_item")->where($where)->find();
		$vote_img = explode(";", $item["vote_img"]);

		if ($item["upload_type"] == 1) {
			foreach ($vote_img as $key => $val ) {
				$vote_img[$key] = $val;
			}
		}
		else {
			foreach ($vote_img as $key => $val ) {
				$vote_img[$key] = str_replace("thumb_", "big_", $val);
			}
		}

		$this->assign("imgUrl", end($vote_img));
		$this->assign("vote_img", $vote_img);
		$this->assign("item", $item);
		$this->get_head_content($action_info["default_skin"]);
		$this->assign("item_id", $item["id"]);
		$this->assign("token", $this->token);
		$this->assign("vote_id", $vote_id);
		$this->assign("is_share", $is_share);
		$this->assign($action_info);
		$this->check_expire($action_info);
		$this->clear_vote_day();
		$this->display($this->get_tplname($action_info));
	}

	public function share()
	{
		$item_id = $this->_get("item_id");
		$vote_id = $this->_get("id");
		if (empty($item_id) || empty($vote_id) || empty($this->token)) {
			$this->error("非法操作");
			exit();
		}

		$action_info = M("voteimg")->where(array("id" => $vote_id))->find();

		if (!empty($action_info)) {
			if ($action_info["display"] != 1) {
				$this->error("该活动未开启");
				exit();
			}

			$this->assign("action_info", $action_info);
		}
		else {
			$this->error("该活动不存在");
			exit();
		}

		$this->add_users($action_info["territory_limit"]);
		$this->notice($action_info);
		$where = array("id" => $item_id);
		$item = M("voteimg_item")->where($where)->find();
		$vote_img = explode(";", $item["vote_img"]);

		foreach ($vote_img as $key => $val ) {
			$vote_img[$key] = str_replace("thumb_", "big_", $val);
		}

		$this->clear_vote_day();
		$this->assign("item", $item);
		$this->assign("vote_img", $vote_img);
		$this->assign("token", $this->token);
		$this->assign("vote_id", $vote_id);
		$this->assign("item_id", $item_id);
		$this->display($this->get_tplname($action_info));
	}

	public function vote_record()
	{
		if (empty($this->action_id) || empty($this->token)) {
			$this->assign("vote_record", "");
		}

		$type = $this->_get("type", "trim");

		if ($type == "ranking") {
			$vote_id = $this->_get("id", "intval");
			$action_info = M("voteimg")->where(array("id" => $vote_id))->find();

			if (!empty($action_info)) {
				if ($action_info["display"] != 1) {
					$this->error("该活动未开启");
					exit();
				}

				$this->assign("action_info", $action_info);
			}
			else {
				$this->error("该活动不存在");
				exit();
			}

			$this->get_head_content($action_info["default_skin"]);
			$total = M("voteimg_item")->where(array("vote_id" => $this->action_id, "token" => $this->token, "check_pass" => 1))->count();
			$page = new Page($total, 30);
			$page->setConfig("prev", "<<");
			$page->setConfig("next", ">>");
			$page->setConfig("theme", "%upPage% %linkPage% %downPage%");
			$ranking = M("voteimg_item")->where(array("vote_id" => $this->action_id, "token" => $this->token, "check_pass" => 1))->limit($page->firstRow . "," . $page->listRows)->order("vote_count desc")->select();

			if ($ranking) {
				$this->assign("vote_record", $ranking);
				$this->assign("page", $page->show());
				$this->assign("firstRow", $page->firstRow);
			}
			else {
				$this->assign("vote_record", "");
			}

			$this->assign("vote_id", $vote_id);
			$this->assign("token", $this->token);
			$this->assign($action_info);
			$this->assign("imgUrl", $action_info["reply_pic"]);
			$this->check_expire($action_info);

			if ($action_info["default_skin"] == 1) {
				$this->display("vote_record_index");
			}
			else {
				$this->display("vote_record_index_new");
			}
		}
		else {
			$item_id = M("voteimg_users")->where(array("vote_id" => $this->action_id, "token" => $this->token, "wecha_id" => $this->wecha_id))->getField("item_id");

			if (!empty($item_id)) {
				$all_items = explode(",", $item_id);
				$times = array_count_values($all_items);
				$item_ids = array_unique($all_items);
				$vote_record = array();

				foreach ($item_ids as $k => $id ) {
					$record = M("voteimg_item")->where(array("id" => $id))->field("vote_title,vote_count")->find();
					if (!empty($record["vote_count"]) && !empty($record["vote_title"])) {
						$vote_record[$k]["vote_count"] = $record["vote_count"];
						$vote_record[$k]["vote_title"] = $record["vote_title"];
						$vote_record[$k]["my_vote_count"] = $times[$id];
					}
				}

				rsort($vote_record);
				$this->assign("vote_record", $vote_record);
			}
			else {
				$this->assign("vote_record", "");
			}

			$this->display();
		}
	}

	public function stat_info()
	{
		if (empty($this->action_id) || empty($this->token)) {
			$return_json = json_encode(array("item_count" => 0, "voted_count" => 0, "visit_count" => 0));
		}

		$item_count = M("voteimg_item")->where(array("vote_id" => $this->action_id, "token" => $this->token, "check_pass" => 1))->count();
		$voted_count = M("voteimg_item")->where(array("vote_id" => $this->action_id, "token" => $this->token))->sum("vote_count");
		$visit_count_self = M("voteimg_stat")->where(array("vote_id" => $this->action_id, "token" => $this->token))->getField("count");
		$visit_count = M("voteimg_users")->where(array("vote_id" => $this->action_id, "token" => $this->token))->count();

		if (0 < $visit_count_self) {
			$visit_count = $visit_count_self + $visit_count;
		}

		$return_json = json_encode(array("item_count" => $item_count, "voted_count" => $voted_count, "visit_count" => $visit_count));
		exit($return_json);
	}

	public function localupload($token)
	{
		$upload = new UploadFile();
		$upload->allowExts = array("gif", "jpg", "jpeg", "bmp", "png");
		$upload->autoSub = 1;
		$firstLetter = substr($token, 0, 1);
		$upload->savePath = "./uploads/" . $firstLetter . "/" . $token . "/";
		if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads") || !is_dir($_SERVER["DOCUMENT_ROOT"] . "/uploads")) {
			mkdir($_SERVER["DOCUMENT_ROOT"] . "/uploads", 511);
		}

		$firstLetterDir = $_SERVER["DOCUMENT_ROOT"] . "/uploads/" . $firstLetter;
		if (!file_exists($firstLetterDir) || !is_dir($firstLetterDir)) {
			mkdir($firstLetterDir, 511);
		}

		if (!file_exists($firstLetterDir . "/" . $token) || !is_dir($firstLetterDir . "/" . $token)) {
			mkdir($firstLetterDir . "/" . $token, 511);
		}

		if (!file_exists($upload->savePath) || !is_dir($upload->savePath)) {
			mkdir($upload->savePath, 511);
		}

		$upload->hashLevel = 2;

		if ($upload->upload()) {
			$info = $upload->getUploadFileInfo();
			$this->siteUrl = $this->siteUrl ? $this->siteUrl : C("site_url");
			$msg = $this->siteUrl . substr($upload->savePath, 1) . $info[0]["savename"];
			return array("status" => "SUCCESS", "img_url" => $msg);
		}
		else {
			$msg = $upload->getErrorMsg();
			return array("status" => "FAIL", "error_msg" => $msg);
		}
	}

	private function notice($voteimg)
	{
		if (empty($voteimg)) {
			$this->error("该活动不存在");
			return false;
		}

		if (($voteimg["is_follow"] == 2) && ($this->isSubscribe() == false)) {
			$follow_msg = (!empty($voteimg["follow_msg"]) ? $voteimg["follow_msg"] : "");
			$custom_url = (!empty($voteimg["follow_url"]) ? $voteimg["follow_url"] : "");
			$custom_btn_msg = (!empty($voteimg["follow_btn_msg"]) ? $voteimg["follow_btn_msg"] : "");
			$this->assign("notice_content", "no_follow");
			$this->memberNotice($follow_msg, 1, $custom_url, $custom_btn_msg);
			return false;
		}

		if ($voteimg["territory_limit"] == 1) {
			$voter = M("voteimg_users")->where(array("vote_id" => $voteimg["id"], "token" => $voteimg["token"], "wecha_id" => $this->wecha_id))->find();

			if ($voter["phone"] == "no") {
				$this->assign("notice_content", "no_register");
				$custom_register_msg = (!empty($voteimg["register_msg"]) ? $voteimg["register_msg"] : "");
				$config["is_voteimg"] = 1;
				$config["wecha_id"] = $this->wecha_id;
				$this->memberNotice($custom_register_msg, 0, $config, $voteimg["id"]);
				return false;
			}
			else {
				$check_mobile = $this->CheckMobile($voteimg["pro_city"], $voter["phone"]);

				if (!$check_mobile) {
					$this->assign("notice_content", "mobile_limit");
					return false;
				}
			}
		}
		else {
			if (($voteimg["is_register"] == 1) && empty($this->fans["tel"])) {
				$custom_register_msg = (!empty($voteimg["register_msg"]) ? $voteimg["register_msg"] : "");
				$this->assign("notice_content", "no_register");
				$this->memberNotice($custom_register_msg);
				return false;
			}
		}

		$this->assign("notice_content", "");
		return true;
	}

	public function ajaxImgUpload()
	{
		$filename = trim($_POST["filename"]);
		$img = $_POST[$filename];
		$img = str_replace("data:image/png;base64,", "", $img);
		$img = str_replace(" ", "+", $img);
		$imgdata = base64_decode($img);
		$getupload_dir = "/uploads/voteimg/" . date("Ymd");
		$upload_dir = "." . $getupload_dir;

		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 511, true);
		}

		$newfilename = "voteimg_" . date("YmdHis") . ".jpg";
		$save = file_put_contents($upload_dir . "/big_" . $newfilename, $imgdata);
		$image = new Image();
		$thumb = $image->thumb($upload_dir . "/big_" . $newfilename, $upload_dir . "/thumb_" . $newfilename, "", 650, 950);
		$up_domainname = (C("up_domainname") ? str_replace("http://", "", C("up_domainname")) : "");
		$upload_type = ((C("upload_type") != "") && ($up_domainname != "") ? C("upload_type") : "local");

		if ($upload_type == "upyun") {
			$json = $this->Upyun_upload($upload_dir . "/thumb_" . $newfilename);
			$decode_json = json_decode($json, true);
			$this->del_upload($upload_dir . "/thumb_" . $newfilename);
			$this->del_upload($upload_dir . "/big_" . $newfilename);
			if (($decode_json["code"] == 200) && ($decode_json["message"] == "ok")) {
				$this->statisticsFiles("http://" . $up_domainname . $decode_json["url"]);
				$this->dexit(array(
	"error" => 0,
	"data"  => array("code" => 1, "url" => "http://" . $up_domainname . $decode_json["url"], "msg" => "")
	));
			}
			else {
				$this->dexit(array(
	"error" => 1,
	"data"  => array("code" => 0, "url" => "", "msg" => $decode_json["message"])
	));
			}
		}
		else if ($save) {
			$this->statisticsFiles($this->siteUrl . $getupload_dir . "/thumb_" . $newfilename);
			$this->dexit(array(
	"error" => 0,
	"data"  => array("code" => 1, "url" => $this->siteUrl . $getupload_dir . "/thumb_" . $newfilename, "msg" => "")
	));
		}
		else {
			$this->dexit(array(
	"error" => 1,
	"data"  => array("code" => 0, "url" => "", "msg" => "保存失败！")
	));
		}
	}

	public function Upyun_upload($resource)
	{
		$resource = $_SERVER["DOCUMENT_ROOT"] . str_replace(array("./"), array("/"), $resource);

		if (!@file_exists($resource)) {
			return json_encode(array("code" => 1000, "message" => "上传文件不存在"));
		}

		$bucket = C("up_bucket");
		$form_api_secret = C("up_form_api_secret");
		if (empty($bucket) || empty($form_api_secret)) {
			return json_encode(array("code" => 1002, "message" => "参数错误,请登录总后台在[站点管理-附件设置]中设置正确的值"));
		}

		$options = array();
		$options["bucket"] = $bucket;
		$options["expiration"] = time() + 600;
		$options["save-key"] = "/" . $this->token . "/{year}/{mon}/{day}/" . time() . "_{random}{.suffix}";
		$options["allow-file-type"] = C("up_exts");
		$options["content-length-range"] = "0," . (intval(C("up_size")) * 1024);
		$policy = base64_encode(json_encode($options));
		$signature = md5($policy . "&" . $form_api_secret);
		$requestUrl = "http://v0.api.upyun.com/" . $bucket;
		$respon_json = $this->postCurl($requestUrl, array("file" => "@" . $resource, "policy" => $policy, "signature" => $signature));
		return $respon_json;
	}

	private function statisticsFiles($url)
	{
		if (!empty($url)) {
			$Files = new Files();
			$fileinfo = get_headers($url, 1);
			$fileinfo["Content-Type"] = ($fileinfo["Content-Type"] ? $fileinfo["Content-Type"] : "");
			$Files->index($url, intval($fileinfo["Content-Length"]), $fileinfo["Content-Type"], "", $this->token);
			return true;
		}
		else {
			return false;
		}
	}

	private function dexit($data)
	{
		if (is_array($data)) {
			echo json_encode($data);
		}
		else {
			echo $data;
		}

		exit();
	}

	public function postCurl($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$exec = curl_exec($ch);

		if ($exec) {
			curl_close($ch);
			return $exec;
		}
		else {
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			return json_encode(array("errcode" => $errno, "errmsg" => $error));
		}
	}

	private function del_upload($img_url)
	{
		if (empty($img_url)) {
			return false;
		}

		if (strpos($img_url, ";")) {
			$img_array = explode(";", $img_url);

			foreach ((array) $img_array as $img ) {
				$filename = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $img);
				$filename = getcwd() . $filename;
				$big_img = str_replace("thumb_", "big_", $img);
				$big_file = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $big_img);
				$big_file = getcwd() . $big_file;
				if (!empty($filename) && @file_exists($filename)) {
					unlink($filename);
				}

				if (!empty($big_file) && @file_exists($big_file)) {
					unlink($big_file);
				}
			}
		}
		else {
			$filename = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $img_url);
			$filename = getcwd() . $filename;
			$big_img = str_replace("thumb_", "big_", $img_url);
			$big_file = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $big_img);
			$big_file = getcwd() . $big_file;
			if (!empty($filename) && @file_exists($filename)) {
				unlink($filename);
			}

			if (!empty($big_file) && @file_exists($big_file)) {
				unlink($big_file);
			}
		}

		return true;
	}

	private function get_head_content($default_skin)
	{
		$where = array("token" => $this->token, "vote_id" => $this->action_id);
		$cache_banner = S($this->token . "_" . $this->action_id . "_banner");

		if (!empty($cache_banner)) {
			$banner = $cache_banner;
		}
		else {
			$banner = M("voteimg_banner")->where($where)->order("banner_rank asc")->field("img_url,external_links")->select();
			S($this->token . "_" . $this->action_id . "_banner", $banner);
		}

		foreach ($banner as $key => $b ) {
			if ($b["external_links"] != "") {
				if ((strpos($b["external_links"], "{siteUrl}") !== false) || (strpos($b["external_links"], "{wechat_id}") !== false)) {
					$external_links = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $b["external_links"]);
					$banner[$key]["external_links"] = htmlspecialchars_decode($external_links);
				}
				else {
					$banner[$key]["external_links"] = $b["external_links"];
				}
			}
		}

		$this->assign("banner", $banner);
		$cache_stat = S($this->token . "_" . $this->action_id . "_stat");

		if (!empty($cache_stat)) {
			$stat = $cache_stat;
		}
		else {
			$stat = M("voteimg_stat")->where(array("token" => $this->token, "vote_id" => $this->action_id))->find();
			S($this->token . "_" . $this->action_id . "_stat", $stat);
		}

		if ($stat) {
			$name = explode(",", $stat["stat_name"]);
			$this->assign("hide", $stat["hide"]);
			$this->assign("name", $name);
		}

		$cache_menu = S($this->token . "_" . $this->action_id . "_menu");

		if (!empty($cache_menu)) {
			$all_menu = $cache_menu;
		}
		else {
			$all_menu = M("voteimg_menus")->where(array("token" => $this->token, "vote_id" => $this->action_id))->select();
			S($this->token . "_" . $this->action_id . "_menu", $all_menu);
		}

		foreach ($all_menu as $key => $val ) {
			if ($val["type"] == 2) {
				$menu[$key] = $all_menu[$key];
			}
			else {
				if (($val["type"] == 1) && ($val["hide"] == 1)) {
					$custom_menu[$key] = $all_menu[$key];

					if (!empty($val["menu_link"])) {
						$menu_link = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $val["menu_link"]);
					}

					$custom_menu[$key]["menu_link"] = htmlspecialchars_decode($menu_link);
				}
			}
		}

		foreach ($menu as $k => $v ) {
			if (!empty($v["menu_link"])) {
				$url = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $v["menu_link"]);
				$menu[$k]["menu_link"] = htmlspecialchars_decode($url);
			}
		}

		$this->assign("menu", $menu);
		$this->assign("custom_menu", $custom_menu);
		$cache_bottom = S($this->token . "_" . $this->action_id . "_bottom");

		if (!empty($cache_bottom)) {
			$all_bottom = $cache_bottom;
		}
		else {
			$all_bottom = M("voteimg_bottom")->where(array("token" => $this->token, "vote_id" => $this->action_id))->select();
			S($this->token . "_" . $this->action_id . "_bottom", $all_bottom);
		}

		$bottom_hide = 0;
		$i = 0;

		foreach ($all_bottom as $k => $v ) {
			if ($v["type"] == 2) {
				if ($default_skin == 2) {
					$all_bottom[$k]["bottom_icon"] = str_replace("/img/", "/newvoteimg/images/", $all_bottom[$k]["bottom_icon"]);
				}

				if ($v["hide"] == 2) {
					$bottom_hide += 1;
				}
				else {
					$show_id[] = $i;
				}

				$bottom[$i] = $all_bottom[$k];
				$i++;
			}
			else {
				if (($v["type"] == 1) && ($v["hide"] == 1)) {
					$custom_bottom[$v["bottom_rank"]] = $all_bottom[$k];

					if (!empty($v["bottom_link"])) {
						$bottom_link = str_replace(array("{siteUrl}", "{wechat_id}"), array($this->siteUrl, $this->wecha_id), $v["bottom_link"]);
						$custom_bottom[$v["bottom_rank"]]["bottom_link"] = htmlspecialchars_decode($bottom_link);
					}
				}
			}
		}

		$left_count = count($custom_bottom) + (4 - $bottom_hide);

		if (4 < $left_count) {
			for ($i = 0; $i < ($left_count - 4); $i++) {
				$bottom[$show_id[$i]]["hide"] = 2;
			}
		}

		krsort($custom_bottom);
		$this->assign("bottom", $bottom);
		$this->assign("custom_bottom", $custom_bottom);
		return true;
	}

	private function get_tplname($action_info)
	{
		if (empty($action_info)) {
			return ACTION_NAME;
		}

		$flag = "";

		if ($action_info["default_skin"] != 1) {
			$flag = "_new";
		}

		if ((ACTION_NAME == "index") && ($action_info["page_type"] == "page")) {
			C("TOKEN_ON", false);
			return "index_page" . $flag;
		}
		else {
			return ACTION_NAME . $flag;
		}
	}

	private function check_expire($action_info)
	{
		if (empty($action_info)) {
			$this->assign("allow_apply", "over");
			$this->assign("vote_date", "over");
			return false;
		}

		if ($action_info["apply_end_time"] < $_SERVER["REQUEST_TIME"]) {
			$this->assign("disabled", "disabled = 'disabled'");
			$this->assign("allow_apply", "over");
		}
		else if ($_SERVER["REQUEST_TIME"] < $action_info["apply_start_time"]) {
			$this->assign("disabled", "disabled = 'disabled'");
			$this->assign("allow_apply", "wait");
		}
		else {
			$this->assign("disabled", "");
			$this->assign("allow_apply", "");
		}

		if ($action_info["end_time"] < $_SERVER["REQUEST_TIME"]) {
			$this->assign("vote_date", "over");
		}
		else if ($_SERVER["REQUEST_TIME"] < $action_info["start_time"]) {
			$this->assign("vote_date", "wait");
		}
		else {
			$this->assign("vote_date", "");
		}

		$voteimg_item = M("voteimg_item")->where(array("vote_id" => $this->action_id, "token" => $this->token, "wecha_id" => $this->wecha_id))->find();

		if (!empty($voteimg_item)) {
			$this->assign("disabled", "disabled = 'disabled'");
			$this->assign("allow_apply", "exists");
		}

		return true;
	}

	private function clear_vote_day()
	{
		if (S($this->token . "_" . $this->action_id . "_" . $this->wecha_id . "_vote_day") == "") {
			$today_time = strtotime(date("Y-m-d 00:00:00", $_SERVER["REQUEST_TIME"]));
			$evening_time = strtotime(date("Y-m-d 23:59:59", $_SERVER["REQUEST_TIME"]));
			$cache_time = $evening_time - $_SERVER["REQUEST_TIME"];
			$where = "vote_id = $this->action_id and token = '$this->token' and wecha_id = '$this->wecha_id' and vote_time < '$today_time'";
			$yesterday = M("voteimg_users")->where($where)->find();

			if (!empty($yesterday)) {
				M("voteimg_users")->where($where)->save(array("votenum_day" => 0, "vote_today" => ""));
			}

			S($this->token . "_" . $this->action_id . "_" . $this->wecha_id . "_vote_day", 1, $cache_time);
		}
	}

	public function CheckMobile($pro_city, $mobile)
	{
		if (empty($pro_city) && (strpos($pro_city, "_") === false)) {
			return false;
		}

		if (empty($mobile)) {
			return false;
		}

		$MobileAttribution = $this->MobileAttribution($mobile);
		$MobileAttribution = mb_convert_encoding(urldecode($MobileAttribution), "UTF-8", "GBK");
		list($limit_pro, $limit_city) = explode("=", $MobileAttribution);

		if (strpos($pro_city, "|") !== false) {
			$pro_citys = explode("|", $pro_city);

			foreach ($pro_citys as $key => $val ) {
				list($p, $c) = explode("_", $val);
				if ((strpos($p, $limit_pro) !== false) && empty($c)) {
					return true;
				}
				else {
					if ((strpos($p, $limit_pro) !== false) && (strpos($c, $limit_city) !== false)) {
						return true;
					}
					else {
						continue;
					}
				}
			}
		}
		else {
			list($province, $city) = explode("_", $pro_city);
			if ((strpos($province, $limit_pro) !== false) && empty($city)) {
				return true;
			}
			else {
				if ((strpos($province, $limit_pro) !== false) && (strpos($city, $limit_city) !== false)) {
					return true;
				}
			}
		}

		return false;
	}

	private function MobileAttribution($mobile)
	{
		if ($mobile == "") {
			return false;
		}

		header("Content-Type:text/html;Charset=utf-8");

		if (F("cache_" . $mobile) != "") {
			return F("cache_" . $mobile);
		}

		$request_url = "http://virtual.paipai.com/extinfo/GetMobileProductInfo?mobile=" . $mobile . "&amount=10000";
		$repost_string = file_get_contents($request_url);
		$preg = "/^\(\{mobile:'\d+',province:'(.*?)',.*?cityname:'(.*?)'\}\);/";
		preg_match($preg, $repost_string, $match);
		if (!empty($match[1]) && !empty($match[2])) {
			F("cache_" . $mobile, urlencode($match[1] . "=" . $match[2]));
		}

		return $match[1] . "=" . $match[2];
	}
}


?>
