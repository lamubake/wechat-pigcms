<?php

class GameAction extends WapAction
{
	public $jump_url;

	public function _initialize()
	{
		parent::_initialize();
		$this->jump_url = "http://www.meihua.com/index.php?m=Game&c=start&a=index";
	}

	public function jump()
	{
		$data = $this->clear_html($_GET);

		if ($this->isSubscribe()) {
			$data["attention"] = 1;
		}
		else {
			$data["attention"] = 2;
		}

		if (isset($data["code"])) {
			unset($data["code"]);
		}

		if (isset($data["state"])) {
			unset($data["state"]);
		}

		$jump_url = $this->jump_url . "&" . http_build_query($data);
		header("Location:" . $jump_url);
		exit();
	}

	final public function clear_html($array)
	{
		if (!is_array($array)) {
			return trim(htmlspecialchars($array, ENT_QUOTES));
		}

		foreach ($array as $key => $value ) {
			if (is_array($value)) {
				$this->clear_html($value);
			}
			else {
				$array[$key] = trim(htmlspecialchars($value, ENT_QUOTES));
			}
		}

		return $array;
	}

	public function link()
	{
		$id = $this->_get("id");
		$wecha_id = $this->wecha_id;
		$siteUrl = $this->_get("siteurl");
		$item = M("Games")->where(array("id" => $id))->find();

		if ($item) {
			$game = new game();
			$url = $game->getLink($item, $wecha_id, $siteUrl);
			header("Location:" . $url);
		}
	}

	public function card()
	{
		$card = M("Cards")->where(array("id" => $this->_get("id")))->find();
		$url = "http://www.meihua.com/index.php?m=Card&c=index&a=index&unique={unique}&crid={cardid}&usercardid={id}&token={token}";
		$unique = base64_encode($_SERVER["SERVER_NAME"] . "_pigcms_" . $this->token);
		$url = strtr($url, array("{token}" => $this->token, "{id}" => $card["id"], "{unique}" => $unique, "{cardid}" => $card["cardid"]));
		header("Location:" . $url);
	}
}


?>
