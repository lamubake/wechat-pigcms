<?php

class CoinTreeAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction("CoinTree");
	}

	public function index()
	{
		$where = array("token" => $this->token);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["action_name|keyword"] = array("like", "%" . $search_word . "%");
		}

		$total = M("cointree")->where($where)->count();
		$Page = new Page($total, 10);
		$list = M("cointree")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("id desc")->select();
		$this->assign("list", $list);
		$this->assign("page", $Page->show());
		$this->assign("search_word", $search_word);
		$this->display();
	}

	public function add_action()
	{
		if (IS_POST) {
			$data = array();
			$data["action_name"] = $this->_post("action_name", "trim");
			$data["keyword"] = $this->_post("keyword", "trim");
			$data["reply_title"] = $this->_post("reply_title", "trim");
			$data["reply_content"] = $this->_post("reply_content", "trim");
			$data["reply_pic"] = $this->_post("reply_pic", "trim");
			$data["action_desc"] = $this->_post("action_desc", "trim");
			$data["remind_word"] = $this->_post("remind_word", "trim");
			$data["remind_link"] = $this->_post("remind_link", "trim");
			$data["starttime"] = strtotime($this->_post("starttime"));
			$data["endtime"] = strtotime($this->_post("endtime"));
			$data["totaltimes"] = $this->_post("totaltimes", "intval");
			$data["everydaytimes"] = $this->_post("everydaytimes", "intval");
			$data["join_number"] = $this->_post("join_number", "intval");
			$data["usedup_conins"] = $this->_post("usedup_conins", "intval");
			$data["gain_conins"] = $this->_post("gain_conins", "intval");
			$data["timespan"] = $this->_post("timespan", "intval");
			$data["record_nums"] = $this->_post("record_nums", "intval");
			$data["is_limitwin"] = $this->_post("is_limitwin", "intval");
			$data["is_follow"] = $this->_post("is_follow", "intval");
			$data["is_register"] = $this->_post("is_register", "intval");
			$data["is_amount"] = $this->_post("is_amount", "intval");
			$data["custom_sharetitle"] = $this->_post("custom_sharetitle", "trim");
			$data["custom_sharedsc"] = $this->_post("custom_sharedsc", "trim");
			$data["follow_msg"] = $this->_post("follow_msg", "trim");
			$data["follow_btn_msg"] = $this->_post("follow_btn_msg", "trim");
			$data["custom_follow_url"] = $this->_post("custom_follow_url", "trim");
			$data["register_msg"] = $this->_post("register_msg", "trim");
			$data["sms_verify"] = $this->_post("sms_verify", "intval");
			$data["status"] = $this->_post("status", "intval");

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
				$this->error("活动提示语不能为空");
			}

			if ($data["remind_link"] == "") {
				$this->error("提示语跳转链接不能为空");
			}

			if ((int) $data["totaltimes"] <= 0) {
				$this->error("总摇奖次数请输入大于0的整数");
			}

			if ((int) $data["everydaytimes"] < 0) {
				$this->error("每天摇奖次数请输入整数");
			}

			if ((int) $data["totaltimes"] < (int) $data["everydaytimes"]) {
				$this->error("每人每天的摇奖次数小于总摇奖次数");
			}

			if ((int) $data["join_number"] <= 0) {
				$this->error("预计总参加人数请输入大于0的整数");
			}

			if ((int) $data["usedup_conins"] <= 0) {
				$this->error("每摇奖一次消耗的金币数请输入大于0的整数");
			}

			if ((int) $data["gain_conins"] <= 0) {
				$this->error("帮助分享一次增加的金币数请输入大于0的整数");
			}

			if ($data["endtime"] < $data["starttime"]) {
				$this->error("开始时间不能大于结束时间");
			}

			if (strpos($data["reply_pic"], "http") === false) {
				$data["reply_pic"] = $this->siteUrl . $data["reply_pic"];
			}

			if (2400 < strlen($data["action_desc"])) {
				$this->error("活动简介不超过800字");
			}

			$prize = array();
			$prize["first_nums"] = $this->_post("first_nums", "intval");
			$prize["first_img"] = $this->_post("first_img", "trim");
			$prize["first_prize"] = $this->_post("first_prize", "trim");

			if ((int) $prize["first_nums"] <= 0) {
				$this->error("一等奖数量请输入大于0的整数");
			}

			if ($prize["first_img"] == "") {
				$this->error("一等奖展示图片不能为空");
			}

			if ($prize["first_prize"] == "") {
				$this->error("一等奖奖品说明不能为空");
			}

			if ((0 < $this->_post("second_nums", "intval")) && ($this->_post("second_img", "trim") != "") && ($this->_post("second_prize", "trim") != "")) {
				$prize["second_nums"] = $this->_post("second_nums", "intval");
				$prize["second_img"] = $this->_post("second_img", "trim");
				$prize["second_prize"] = $this->_post("second_prize", "trim");
			}
			else {
				$prize["second_nums"] = "";
				$prize["second_img"] = "";
				$prize["second_prize"] = "";
			}

			if ((0 < $this->_post("third_nums", "intval")) && ($this->_post("third_img", "trim") != "") && ($this->_post("third_prize", "trim") != "")) {
				$prize["third_nums"] = $this->_post("third_nums", "intval");
				$prize["third_img"] = $this->_post("third_img", "trim");
				$prize["third_prize"] = $this->_post("third_prize", "trim");
			}
			else {
				$prize["third_nums"] = "";
				$prize["third_img"] = "";
				$prize["third_prize"] = "";
			}

			if ((0 < $this->_post("fourth_nums", "intval")) && ($this->_post("fourth_img", "trim") != "") && ($this->_post("fourth_prize", "trim") != "")) {
				$prize["fourth_nums"] = $this->_post("fourth_nums", "intval");
				$prize["fourth_img"] = $this->_post("fourth_img", "trim");
				$prize["fourth_prize"] = $this->_post("fourth_prize", "trim");
			}
			else {
				$prize["fourth_nums"] = "";
				$prize["fourth_img"] = "";
				$prize["fourth_prize"] = "";
			}

			if ((0 < $this->_post("fifth_nums", "intval")) && ($this->_post("fifth_img", "trim") != "") && ($this->_post("fifth_prize", "trim") != "")) {
				$prize["fifth_nums"] = $this->_post("fifth_nums", "intval");
				$prize["fifth_img"] = $this->_post("fifth_img", "trim");
				$prize["fifth_prize"] = $this->_post("fifth_prize", "trim");
			}
			else {
				$prize["fifth_nums"] = "";
				$prize["fifth_img"] = "";
				$prize["fifth_prize"] = "";
			}

			if ((0 < $this->_post("sixth_nums", "intval")) && ($this->_post("sixth_img", "trim") != "") && ($this->_post("sixth_prize", "trim") != "")) {
				$prize["sixth_nums"] = $this->_post("sixth_nums", "intval");
				$prize["sixth_img"] = $this->_post("sixth_img", "trim");
				$prize["sixth_prize"] = $this->_post("sixth_prize", "trim");
			}
			else {
				$prize["sixth_nums"] = "";
				$prize["sixth_img"] = "";
				$prize["sixth_prize"] = "";
			}

			$id = $this->_post("id", "intval");

			if (!$id) {
				$data["token"] = $this->token;
				$action_id = M("cointree")->add($data);
				$prize["cid"] = $action_id;
				$add_prize = M("cointree_prize")->add($prize);
				if ($action_id && $add_prize) {
					$this->handleKeyword($action_id, "CoinTree", $this->_post("keyword", "trim"));
					$this->success("添加摇钱树活动成功", U("CoinTree/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("添加摇钱树活动失败");
					exit();
				}
			}
			else {
				$stat = true;
				$update_action = M("cointree")->where(array("id" => $id))->save($data);

				if ($update_action === false) {
					$stat = false;
				}

				$update_prize = M("cointree_prize")->where(array("cid" => $id))->save($prize);

				if ($update_prize === false) {
					$stat = false;
				}

				if ($stat) {
					$this->handleKeyword($id, "CoinTree", $this->_post("keyword", "trim"));
					S($this->token . "_" . $id . "_cointree", NULL);
					$this->success("修改摇钱树活动成功", U("CoinTree/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("修改摇钱树活动失败");
					exit();
				}
			}
		}

		if ($_GET["id"] != "") {
			$action_info = M("cointree")->where(array("id" => $_GET["id"]))->find();
			$prize_info = M("cointree_prize")->where(array("cid" => $_GET["id"]))->find();
			if (!empty($action_info) && !empty($prize_info)) {
				$this->assign("set", $action_info);
				$this->assign("vo", $prize_info);
			}
		}

		$this->assign("token", $this->token);
		$this->display();
	}

	public function prizerecord()
	{
		$token = $this->_get("token", "trim");
		$action_id = $this->_get("id", "intval");
		$where = array("token" => $token, "cid" => $action_id);
		$search_word = $this->_post("search_word", "trim");

		if (!empty($search_word)) {
			$where["serialnumber|wecha_name"] = array("like", "%" . $search_word . "%");
		}

		$total = M("cointree_record")->where($where)->count();
		$Page = new Page($total, 10);
		$list = M("cointree_record")->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("prize asc")->select();
		$this->assign("list", $list);
		$this->assign("token", $token);
		$this->assign("id", $action_id);
		$this->assign("page", $Page->show());
		$this->assign("search_word", $search_word);
		$this->display();
	}

	public function editprizerecord()
	{
		if (IS_POST) {
			$sendstutas = $this->_post("sendstutas", "intval");
			$id = $this->_post("id", "intval");

			if ($sendstutas == 1) {
				$data["sendstutas"] = 1;
				$data["sendtime"] = time();
			}
			else {
				$data["sendstutas"] = 0;
				$data["sendtime"] = "";
			}

			$exists = M("cointree_record")->where(array("id" => $id))->find();

			if (!empty($exists)) {
				$update = M("cointree_record")->where(array("id" => $id))->save($data);

				if ($update) {
					$this->success("编辑成功", U("CoinTree/prizerecord", array("token" => $exists["token"], "id" => $exists["cid"])));
					exit();
				}
				else {
					$this->error("编辑失败");
					exit();
				}
			}
			else {
				$this->error("不存在该编辑项");
				exit();
			}
		}

		if ($_GET["id"]) {
			$info = M("cointree_record")->where(array("id" => $_GET["id"]))->find();

			if (!empty($info)) {
				$this->assign("set", $info);
			}
		}

		$this->display();
	}

	public function delprizerecord()
	{
		$id = (int) $_GET["id"];
		$exists = M("cointree_record")->where(array("id" => $id))->find();

		if (!empty($exists)) {
			$del = M("cointree_record")->where(array("id" => $id))->delete();

			if ($del) {
				$this->success("删除成功", U("CoinTree/prizerecord", array("token" => $this->token, "id" => $exists["cid"])));
				exit();
			}
		}
		else {
			$this->error("不存在该删除项");
			exit();
		}
	}

	public function del_action()
	{
		$id = (int) $_GET["id"];
		$exists = M("cointree")->where(array("id" => $id))->find();

		if (!empty($exists)) {
			$del = M("cointree")->where(array("id" => $id))->delete();

			if ($del) {
				$this->handleKeyword($id, "CoinTree", "", "", 1);
				M("cointree_prize")->where(array("cid" => $id))->delete();
				S($this->token . "_" . $id . "_cointree", NULL);
				$this->success("删除成功", U("CoinTree/index", array("token" => $this->token)));
				exit();
			}
		}
		else {
			$this->error("不存在该删除项");
			exit();
		}
	}
}


?>
