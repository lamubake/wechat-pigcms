<?php

class GameAction extends UserAction
{
	public $config;
	public $cats;
	public $game;

	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction("Gamecenter");
		$this->game = new game();
		$this->cats = $this->game->gameCats();
		$this->assign("cats", $this->cats);
	}

	public function config()
	{
		$config = M("Game_config")->where(array("token" => $this->token))->find();

		if (IS_POST) {
			$data = array("token" => $this->token, "wxid" => $this->_post("wxid"), "wxname" => $this->_post("wxname"), "wxlogo" => $this->_post("wxlogo"), "link" => $this->_post("link"), "attentionText" => $this->_post("attentionText"));

			if (!$config) {
				D("Game_config")->add($data);
			}
			else {
				D("Game_config")->where(array("id" => $config["id"]))->save($data);
			}

			$data["link"] = $this->convertLink($data["link"]);
			$rt = $this->game->config($this->token, $data["wxname"], $data["wxid"], $data["wxlogo"], $data["link"], $data["attentionText"]);
			D("Game_config")->where(array("token" => $this->token))->save(array("uid" => $rt["id"], "key" => $rt["key"]));
			$this->success("设置成功");
		}
		else {
			if (!$config) {
				$config = $this->wxuser;
				$config["wxlogo"] = $config["headerpic"];
			}

			$this->assign("info", $config);
			$this->display();
		}
	}

	public function index()
	{
		$this->_toConfig();
		$where = array("token" => $this->token);
		$count = M("Games")->where($where)->count();
		$Page = new Page($count, 15);
		$list = M("Games")->where($where)->order("id desc")->limit($Page->firstRow . "," . $Page->listRows)->select();
		$thisUser = M("Game_config")->where(array("token" => $this->token))->find();
		$this->assign("thisUser", $thisUser);
		$this->assign("count", $count);
		$this->assign("page", $Page->show());
		$this->assign("list", $list);
		$this->display();
	}

	public function delGame()
	{
		$config = $this->_toConfig();
		$id = (isset($_GET["id"]) ? intval($_GET["id"]) : 0);
		$thisItem = M("games")->where(array("id" => $id, "token" => $this->token))->find();
		$gameid = $thisItem["gameid"];
		$thisGame = $this->game->getGame(intval($gameid));
		$this->game->gameSelfSet($config["uid"], $thisGame["id"], $id, "game", $config["key"], "");
		M("games")->where(array("id" => $id, "token" => $this->token))->delete();
		$this->success("删除成功", U("Game/index"));
	}

	public function gameSet()
	{
		$id = (isset($_GET["id"]) ? intval($_GET["id"]) : 0);
		$this->assign("id", $id);

		if ($id) {
			$thisItem = M("games")->where(array("id" => $id, "token" => $this->token))->find();
			$gameid = $thisItem["gameid"];
		}
		else {
			$gameid = intval($_GET["gameid"]);
		}

		$config = $this->_toConfig();
		$thisGame = $this->game->getGame(intval($gameid));
		$gameSet = $this->game->gameSet($config["uid"], $thisGame["id"], $id, $config["key"]);

		if ($gameSet) {
			$thisItem["rule"] = htmlspecialchars_decode(base64_decode($gameSet["rule"]));
			$thisItem["awards"] = htmlspecialchars_decode(base64_decode($gameSet["awards"]));
			$thisItem["attention_url"] = $gameSet["attention_url"];
			$thisItem["is_phone"] = $gameSet["is_phone"];
			$thisItem["is_attention"] = $gameSet["is_attention"];
		}

		$selfs = $this->game->gameSelfs($thisGame["id"], $config["uid"], $id, $config["key"]);

		if (IS_POST) {
			$data = array("token" => $this->token, "title" => $this->_post("title"), "intro" => $this->_post("intro"), "keyword" => $this->_post("keyword"), "picurl" => $this->_post("picurl"), "time" => time(), "gameid" => $thisGame["id"]);
			$selfValues = array();
			$jsonStr = "{";

			if ($selfs) {
				$comma = "";

				foreach ($selfs as $s ) {
					$selfValues["self_" . $s["id"]] = $this->_post("self_" . $s["id"]);
					$jsonStr .= $comma . "\"self_" . $s["id"] . "\":\"" . $selfValues["self_" . $s["id"]] . "\"";
					$comma = ",";
				}
			}

			$jsonStr .= "}";
			$data["selfinfo"] = $jsonStr;

			if (!isset($_POST["id"])) {
				$usergameid = M("Games")->add($data);
			}
			else {
				$usergameid = intval($_POST["id"]);
				M("Games")->where(array("id" => $usergameid))->save($data);
			}

			$gameSet["rule"] = $_POST["gameSet_rule"];
			$gameSet["awards"] = $_POST["gameSet_awards"];
			$gameSet["is_phone"] = $this->_post("is_phone");
			$gameSet["is_attention"] = $this->_post("is_attention");
			$home_set = M("Home")->field("id,gzhurl")->where(array("token" => $this->token))->find();
			if (($gameSet["is_attention"] == 1) && (!isset($home_set["gzhurl"]) || ($home_set["gzhurl"] == ""))) {
				$this->error("需要关注，请先去首页回复设置一键关注链接", U("Game/index"));
			}

			$gameSet["attention_url"] = $home_set["gzhurl"];
			$this->game->gameSet($config["uid"], $thisGame["id"], $usergameid, $config["key"], $gameSet, "game");
			$this->handleKeyword($usergameid, "Game", $data["keyword"], $precisions = 0, $delete = 0);
			$this->game->gameSelfSet($config["uid"], $thisGame["id"], $usergameid, "game", $config["key"], $selfValues);
			$this->success("设置成功", U("Game/index"));
		}
		else {
			$this->assign("thisGame", $thisGame);

			if (!$id) {
				$thisItem = array();
				$thisItem["title"] = $thisGame["title"];
				$thisItem["intro"] = $thisGame["intro"];
				$thisItem["keyword"] = $thisGame["title"];
				$thisItem["rule"] = $thisGame["rule"];
			}

			if ($id) {
				if ($selfs) {
					$selfValues = json_decode($thisItem["selfinfo"], 1);
					$i = 0;

					foreach ($selfs as $s ) {
						$selfs[$i]["value"] = $selfValues["self_" . $s["id"]];

						if ($selfs[$i]["value"]) {
							$selfs[$i]["defaultvalue"] = $selfs[$i]["value"];
						}

						$i++;
					}
				}
			}

			$this->assign("selfs", $selfs);
			$this->assign("info", $thisItem);
			$this->display();
		}
	}

	public function gameDelete()
	{
	}

	public function gameResults()
	{
	}

	public function gameLibrary()
	{
		$catid = (isset($_GET["catid"]) ? intval($_GET["catid"]) : 1);
		$games = $this->game->gameList($catid);
		$this->assign("games", $games);
		$this->assign("catid", $catid);
		$this->display();
	}

	public function _toConfig()
	{
		$config = M("Game_config")->where(array("token" => $this->token))->find();

		if (!$config) {
			$this->success("请先配置游戏相关信息", U("Game/config"));
			exit();
		}
		else {
			return $config;
		}
	}

	public function record()
	{
		$where = array("token" => $this->token, "gameid" => $this->_get("id", "intval"));
		$count = M("Game_records")->where($where)->count();
		$Page = new Page($count, 15);
		$list = M("Game_records")->where($where)->order("score desc,time desc")->limit($Page->firstRow . "," . $Page->listRows)->select();
		$wecha_id = array();

		foreach ($list as $key => $val ) {
			if (!in_array($val["wecha_id"], $wecha_id)) {
				$wecha_id[] = $val["wecha_id"];
			}
		}

		$data["open_id"] = implode(",", $wecha_id);
		$fans_info = $this->valtokey($this->game->fansInfo($data), "openid");

		foreach ($list as $key => $val ) {
			$list[$key]["username"] = $fans_info[$val["wecha_id"]]["wechaname"];
			$list[$key]["phone"] = $fans_info[$val["wecha_id"]]["phone"];
		}

		$this->assign("list", $list);
		$this->assign("fans_info", $fans_info);
		$this->assign("page", $Page->show());
		$this->display();
	}

	public function record_del()
	{
		$where = array("token" => $this->token, "id" => $this->_get("id", "intval"));

		if (M("Game_records")->where($where)->delete()) {
			$this->success("删除成功", U("Game/record", array("token" => $this->token, "id" => $this->_get("rid", "intval"))));
		}
	}

	public function valtokey($data, $field)
	{
		$return = array();

		foreach ($data as $key => $val ) {
			$return[$val[$field]] = $val;
		}

		return $return;
	}
}


?>
