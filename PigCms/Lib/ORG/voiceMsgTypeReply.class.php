<?php

class voiceMsgTypeReply
{
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public $action;
	public $data;

	public function __construct($token, $wechat_id, $data, $siteUrl)
	{
		$this->wechat_id = $wechat_id;
		$this->siteUrl = $siteUrl;
		$this->token = $token;
		$this->data = $data;
		$this->action = A("Home/Weixin");
	}

	public function index()
	{
		$data["Content"] = $data["Recognition"];

		if ($data["Recognition"]) {
			$this->data["Content"] = $data["Recognition"];
		}
		else {
			$voice_apiwhere = array("token" => $this->token, "status" => 1);
			$voice_apiwhere["noanswer"] = array("gt", 0);
			$api = M("Api")->where($voice_apiwhere)->find();

			if (!$api) {
				return $this->action->api("noreplyReturn");
			}
			else {
				return $this->action->api("nokeywordApi");
			}
		}
	}
}


?>
