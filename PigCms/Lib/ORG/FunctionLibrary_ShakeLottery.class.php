<?php

class FunctionLibrary_ShakeLottery
{
	public $sub;
	public $token;

	public function __construct($token, $sub)
	{
		$this->sub = $sub;
		$this->token = $token;
	}

	public function index()
	{
		if (!$this->sub) {
			return array("name" => "摇一摇抽奖", "subkeywords" => 1, "sublinks" => 1);
		}
		else {
			$db = M("shakelottery");
			$where = array("token" => $this->token);
			$items = $db->where($where)->order("id DESC")->select();
			$arr = array(
				"name"        => "摇一摇抽奖",
				"subkeywords" => array(),
				"sublinks"    => array()
				);

			if ($items) {
				foreach ($items as $v ) {
					$arr["subkeywords"][$v["id"]] = array("name" => $v["action_name"], "keyword" => $v["keyword"]);
					$arr["sublinks"][$v["id"]] = array("name" => $v["action_name"], "link" => "{siteUrl}/index.php?g=Wap&m=ShakeLottery&a=index&token=" . $this->token . "&wecha_id={wechat_id}&id=" . $v["id"]);
				}
			}

			return $arr;
		}
	}
}


?>
