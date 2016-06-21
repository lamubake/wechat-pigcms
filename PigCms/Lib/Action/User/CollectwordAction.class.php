<?php

class CollectwordAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction("Collectword");
	}

	public function index()
	{
		$where["token"] = $this->token;
		$where_page["token"] = $this->token;

		if (!empty($_GET["name"])) {
			$where["title"] = array("like", "%" . $_GET["name"] . "%");
			$where_page["name"] = $_GET["name"];
		}

		import("ORG.Util.Page");
		$count = M("Collectword")->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val ) {
			$pagethis->parameter .= "$key=" . urlencode($val) . "&";
		}

		$show = $page->show();
		$list = M("Collectword")->where($where)->order("addtime desc")->limit($page->firstRow . "," . $page->listRows)->select();

		foreach ($list as $k => $v ) {
			$share_count = 0;
			$user_list = M("CollectwordUser")->where(array("token" => $this->token, "pid" => $v["id"]))->field("share_num")->select();

			foreach ($user_list as $vo ) {
				$share_count = $share_count + $vo["share_num"];
			}

			$list[$k]["share_count"] = $share_count;

			if ($v["is_open"] == 0) {
				if (time() < $v["start"]) {
					$list[$k]["state"] = 1;
				}
				else if ($v["end"] < time()) {
					$list[$k]["state"] = 2;
				}
				else {
					$list[$k]["state"] = 3;
				}
			}
			else {
				$list[$k]["state"] = 0;
			}
		}

		$this->assign("page", $show);
		$this->assign("list", $list);
		$this->display();
	}

	public function set()
	{
		if ($this->wxuser["oauth"] != 1) {
			$this->error("本活动必须开启网页授权！", U("User/Auth/index", array("token" => $this->token)));
		}
		else if ($this->wxuser["oauthinfo"] != 1) {
			$this->error("本活动必须选择获取昵称头像等所有信息！", U("User/Auth/index", array("token" => $this->token)));
		}

		$id = $this->_get("id", "intval");
		$where = array("token" => $this->token, "id" => $id);
		$Sentiment = M("Collectword")->where($where)->find();

		if (empty($Sentiment)) {
			$Sentiment["is_attention"] = 1;
			$Sentiment["is_reg"] = 1;
		}

		if (IS_POST) {
			$set["token"] = $this->token;
			$set["keyword"] = $_POST["keyword"];
			$set["word"] = $_POST["word"];
			$set["reply_pic"] = $_POST["reply_pic"];
			$set["fxpic"] = $_POST["fxpic"];
			$set["title"] = $_POST["title"];
			$set["fxtitle"] = $_POST["fxtitle"];
			$set["fxinfo"] = $_POST["fxinfo"];
			$set["prize_fxinfo"] = $_POST["prize_fxinfo"];
			$set["prize_fxtitle"] = $_POST["prize_fxtitle"];
			$set["intro"] = $_POST["intro"];
			$set["info"] = $_POST["info"];
			$set["is_sms"] = intval($_POST["is_sms"]);
			$set["rank_num"] = intval($_POST["rank_num"]);
			$set["is_attention"] = intval($_POST["is_attention"]);
			$set["is_reg"] = intval($_POST["is_reg"]);
			$set["is_open"] = intval($_POST["is_open"]);
			$set["start"] = strtotime($_POST["start"]);
			$set["end"] = strtotime($_POST["end"]);
			$set["count"] = intval($_POST["count"]);
			$set["help_count"] = intval($_POST["help_count"]);
			$set["prize_display"] = intval($_POST["prize_display"]);
			$set["day_count"] = intval($_POST["day_count"]);
			$set["expect"] = intval($_POST["expect"]);
			$news_imgurl = $_POST["news_imgurl"];
			$news_title = $_POST["news_title"];
			$news_url = $_POST["news_url"];
			$prize_title = $_POST["prize_title"];
			$prize_imgurl = $_POST["prize_imgurl"];
			$prize_num = $_POST["prize_num"];

			if ($Sentiment["id"]) {
				$del_news = M("CollectwordNews")->where(array("token" => $this->token, "pid" => $id))->delete();
				$del_prize = M("CollectwordPrize")->where(array("token" => $this->token, "pid" => $id))->delete();

				foreach ($news_imgurl as $nk => $nv ) {
					$add_news["token"] = $this->token;
					$add_news["pid"] = $id;
					$add_news["imgurl"] = $nv;
					$add_news["title"] = $news_title[$nk];
					$add_news["url"] = $news_url[$nk];
					$id_news = M("CollectwordNews")->add($add_news);
				}

				foreach ($prize_title as $pk => $pv ) {
					$add_prize["token"] = $this->token;
					$add_prize["pid"] = $id;
					$add_prize["title"] = $pv;
					$add_prize["imgurl"] = $prize_imgurl[$pk];
					$add_prize["num"] = $prize_num[$pk];
					$id_prize = M("CollectwordPrize")->add($add_prize);
				}

				$update_Sentiment = M("Collectword")->where($where)->save($set);
				$this->handleKeyword($id, "Collectword", $this->_post("keyword", "trim"));
				S($id . "Collectword" . $this->token, NULL);
				S($id . "Collectword" . $this->token . "news", NULL);
				S($id . "Collectword" . $this->token . "prize", NULL);
				$this->success("修改成功", U("Collectword/index", array("token" => $this->token)));
			}
			else {
				$set["addtime"] = time();
				$id = M("Collectword")->add($set);

				foreach ($news_imgurl as $nk => $nv ) {
					$add_news["token"] = $this->token;
					$add_news["pid"] = $id;
					$add_news["imgurl"] = $nv;
					$add_news["title"] = $news_title[$nk];
					$add_news["url"] = $news_url[$nk];
					$id_news = M("CollectwordNews")->add($add_news);
				}

				foreach ($prize_title as $pk => $pv ) {
					$add_prize["token"] = $this->token;
					$add_prize["pid"] = $id;
					$add_prize["title"] = $pv;
					$add_prize["imgurl"] = $prize_imgurl[$pk];
					$add_prize["num"] = $prize_num[$pk];
					$id_prize = M("CollectwordPrize")->add($add_prize);
				}

				$this->handleKeyword($id, "Collectword", $this->_post("keyword", "trim"));
				$this->success("添加成功", U("Collectword/index", array("token" => $this->token)));
			}
		}
		else {
			$this->assign("start_date", date("Y-m-d", time()));
			$this->assign("end_date", date("Y-m-d", strtotime("+1 month")));
			$this->assign("set", $Sentiment);
			$news_list = M("CollectwordNews")->where(array("token" => $this->token, "pid" => $id))->order("id")->select();
			$prize_list = M("CollectwordPrize")->where(array("token" => $this->token, "pid" => $id))->order("id")->select();
			$newsnum = count($news_list);
			$prizenum = count($prize_list);
			$man_label = explode(",", $Sentiment["man_label"]);
			$woman_label = explode(",", $Sentiment["woman_label"]);
			$this->assign("man_label", $man_label);
			$this->assign("woman_label", $woman_label);
			$this->assign("news_list", $news_list);
			$this->assign("prize_list", $prize_list);
			$this->assign("newsnum", $newsnum);
			$this->assign("prizenum", $prizenum);
			$this->display();
		}
	}

	public function del()
	{
		$id = $this->_get("id", "intval");
		$keyword = M("Collectword")->where(array("token" => $this->token, "id" => $id))->getField("keyword");
		$this->handleKeyword($id, "Collectword", $keyword, 0, 1);

		if (M("Collectword")->where(array("token" => $this->token, "id" => $id))->delete()) {
			M("CollectwordNews")->where(array("token" => $this->token, "pid" => $id))->delete();
			M("CollectwordPrize")->where(array("token" => $this->token, "pid" => $id))->delete();
			$this->success("删除成功", U("Collectword/index", array("token" => $this->token)));
		}
	}

	public function rank()
	{
		$id = $this->_get("id", "intval");
		$where = array("token" => $this->token, "pid" => $id, "is_join" => 1);

		if ($_GET["word_count"]) {
			$order = "word_count " . $_GET["word_count"];
		}

		if ($_GET["share_num"]) {
			$order = "share_num " . $_GET["share_num"];
		}

		if ($_GET["addtime"]) {
			$order = "addtime " . $_GET["addtime"];
		}

		$order = (empty($order) ? "word_count DESC, share_num DESC,addtime ASC" : $order);
		$count = M("CollectwordUser")->where($where)->count();
		$Page = new Page($count, 15);
		$list = M("CollectwordUser")->where($where)->order($order)->limit($Page->firstRow . "," . $Page->listRows)->select();

		foreach ($list as $key => $val ) {
			$user_info = M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $val["wecha_id"]))->find();
			$list[$key]["nickname"] = ($user_info["wechaname"] ? $user_info["wechaname"] : $user_info["truename"]);
			$list[$key]["nickname"] = ($list[$key]["nickname"] ? $list[$key]["nickname"] : "匿名");
			$list[$key]["mobile"] = ($user_info["tel"] ? $user_info["tel"] : $val["tel"]);
			$list[$key]["mobile"] = ($list[$key]["mobile"] ? $list[$key]["mobile"] : "无");
		}

		$this->assign("list", $list);
		$this->assign("page", $Page->show());
		$this->display();
	}

	public function rank_del()
	{
		$id = $this->_get("id", "intval");
		$user = M("CollectwordUser")->where(array("token" => $this->token, "id" => $id))->find();

		if (M("CollectwordUser")->where(array("token" => $this->token, "id" => $id))->delete()) {
			$this->success("删除成功");
		}
	}

	public function prize()
	{
		$id = $this->_get("id", "intval");
		$where = array("token" => $this->token, "pid" => $id, "is_prize" => 1);
		$count = M("CollectwordUser")->where($where)->count();
		$Page = new Page($count, 15);
		$list = M("CollectwordUser")->where($where)->order("word_count DESC, share_num DESC,addtime ASC")->limit($Page->firstRow . "," . $Page->listRows)->select();

		foreach ($list as $key => $val ) {
			$user_info = M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $val["wecha_id"]))->find();
			$list[$key]["nickname"] = ($user_info["wechaname"] ? $user_info["wechaname"] : $user_info["truename"]);
			$list[$key]["nickname"] = ($list[$key]["nickname"] ? $list[$key]["nickname"] : "匿名");
			$list[$key]["mobile"] = ($user_info["tel"] ? $user_info["tel"] : $val["tel"]);
			$list[$key]["mobile"] = ($list[$key]["mobile"] ? $list[$key]["mobile"] : "无");
		}

		$this->assign("list", $list);
		$this->assign("page", $Page->show());
		$this->display();
	}

	public function excel()
	{
		$id = $this->_get("id", "intval");
		$token = $this->token;
		$create = M("CollectwordUser");
		$userinfo = M("Userinfo");
		$where = array("token" => $this->token, "pid" => $id, "is_prize" => 1);
		$where = array("token" => $this->token, "pid" => $id, "is_prize" => 1);
		$list = M("CollectwordUser")->where($where)->order("word_count DESC, share_num DESC,addtime ASC")->select();

		foreach ($list as $key => $val ) {
			$user_info = M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $val["wecha_id"]))->find();
			$list[$key]["wechaname"] = ($user_info["wechaname"] ? $user_info["wechaname"] : $user_info["truename"]);
			$list[$key]["wechaname"] = ($list[$key]["wechaname"] ? $list[$key]["wechaname"] : "匿名");
			$list[$key]["tel"] = ($user_info["tel"] ? $user_info["tel"] : $val["tel"]);
			$list[$key]["tel"] = ($list[$key]["tel"] ? $list[$key]["tel"] : "无");
		}

		$fileName = "prize" . $id . ".xls";
		$this->_exeExportPrize($list, $fileName, $id);
	}

	private function _exeExportPrize($createInfo, $name, $id)
	{
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=" . $name);
		$arr = array(
			array("en" => "id", "cn" => "排名"),
			array("en" => "wechaname", "cn" => "昵称"),
			array("en" => "tel", "cn" => "手机号"),
			array("en" => "word_count", "cn" => "集字数"),
			array("en" => "share_num", "cn" => "打开次数"),
			array("en" => "addtime", "cn" => "参与时间")
			);
		$fieldCount = count($arr);
		$s = 0;

		foreach ($arr as $f ) {
			if ($s < ($fieldCount - 1)) {
				echo iconv("utf-8", "gbk", $f["cn"]) . "\t";
			}
			else {
				echo iconv("utf-8", "gbk", $f["cn"]) . "\n";
			}

			$s++;
		}

		if ($createInfo) {
			$listCount = count($createInfo);

			for ($i = 0; $i < $listCount; $i++) {
				for ($k = 0; $k < count($arr); $k++) {
					$fieldValue = $createInfo[$i][$arr[$k]["en"]];

					switch ($arr[$k]["en"]) {
					case "id":
						$fieldValue = $i + 1;
						break;

					case "tel":
						$fieldValue = iconv("utf-8", "gbk", $fieldValue);
						break;

					case "wechaname":
						$fieldValue = iconv("utf-8", "gbk", $fieldValue);
						break;

					case "addtime":
						$fieldValue = date("Y-m-d", $fieldValue);
						break;
					}

					if ($k < ($fieldCount - 1)) {
						echo $fieldValue . "\t";
					}
					else {
						echo $fieldValue . "\n";
					}
				}
			}
		}
	}
}


?>
