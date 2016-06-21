<?php

class poi_check_notifyEventReply
{
	public $token;
	public $wecha_id;
	public $data;

	public function __construct($token, $wecha_id, $data, $siteurl)
	{
		$this->token = $token;
		$this->wecha_id = $wecha_id;
		$this->data = $data;
	}

	public function index()
	{
		$where = array("token" => $this->token, "sid" => $this->data["UniqId"]);

		if ($this->data["Result"] != "fail") {
			M("company")->where($where)->save(array("available_state" => 3, "location_id" => $this->data["PoiId"]));
		}
		else {
			M("company")->where($where)->save(array("available_state" => 4));
		}

		return "";
	}
}


?>
