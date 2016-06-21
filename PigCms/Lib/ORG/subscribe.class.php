<?php

class subscribe
{
	public $token;
	public $wecha_id;
	public $thisWxUser = array();

	public function __construct($token, $wecha_id, $data, $siteurl)
	{
		$this->token = $token;
		$this->wecha_id = $wecha_id;
		$this->thisWxUser = M("Wxuser")->field("appid,appsecret,winxintype")->where(array("token" => $token))->find();
	}

	public function sub()
	{
		if ($this->thisWxUser["appid"] && ($this->thisWxUser["winxintype"] == 3)) {
			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser["appid"]);
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?openid=" . $this->wecha_id . "&access_token=" . $access_token;
			$classData = json_decode($this->curlGet($url));
			if ($classData->subscribe && ($classData->subscribe == 1)) {
				$datainfo["wechaname"] = str_replace(array("'", "\\"), array(""), $classData->nickname);
				$datainfo["sex"] = $classData->sex;
				$datainfo["portrait"] = $classData->headimgurl;
				$datainfo["token"] = $this->token;
				$datainfo["wecha_id"] = $this->wecha_id;
				$datainfo["city"] = $classData->city;
				$datainfo["province"] = $classData->province;
				$datainfo["tel"] = "";
				$datainfo["birthday"] = "";
				$datainfo["address"] = "";
				$datainfo["info"] = "";
				$datainfo["sign_score"] = 0;
				$datainfo["expend_score"] = 0;
				$datainfo["continuous"] = 0;
				$datainfo["add_expend"] = 0;
				$datainfo["add_expend_time"] = 0;
				$datainfo["live_time"] = 0;
				$datainfo["getcardtime"] = 0;
			}
		}
		else {
			$datainfo["wechaname"] = "";
			$datainfo["sex"] = "";
			$datainfo["portrait"] = "";
			$datainfo["tel"] = "";
			$datainfo["birthday"] = "";
			$datainfo["address"] = "";
			$datainfo["info"] = "";
			$datainfo["sign_score"] = 0;
			$datainfo["expend_score"] = 0;
			$datainfo["continuous"] = 0;
			$datainfo["add_expend"] = 0;
			$datainfo["add_expend_time"] = 0;
			$datainfo["live_time"] = 0;
			$datainfo["getcardtime"] = 0;
			$datainfo["token"] = $this->token;
			$datainfo["wecha_id"] = $this->wecha_id;
		}

		if (!M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->getField("id")) {
			$uid = D("Userinfo")->add($datainfo);

			if (empty($uid)) {
				return false;
			}

			if ($cardSet = D("Member_card_set")->where(array("token" => $this->token, "sub_give" => 1))->find()) {
				if ($card = M("Member_card_create")->field("id, number")->where("token='" . $this->token . "' and cardid=" . intval($cardSet["id"]) . " and wecha_id = ''")->order("id ASC")->find()) {
					$is_card = M("Member_card_create")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->find();

					if (empty($is_card)) {
						M("Member_card_create")->where(array("id" => $card["id"]))->save(array("wecha_id" => $this->wecha_id));
						$now = time();

						if (M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->save(array("getcardtime" => $now))) {
							$gwhere = array(
								"token"   => $this->token,
								"cardid"  => $cardSet["id"],
								"is_open" => "1",
								"start"   => array("lt", $now),
								"end"     => array("gt", $now)
								);
							$gifts = M("Member_card_gifts")->where($gwhere)->select();

							foreach ($gifts as $key => $value ) {
								if ($value["type"] == "1") {
									$arr = array();
									$arr["itemid"] = 0;
									$arr["token"] = $this->token;
									$arr["wecha_id"] = $this->wecha_id;
									$arr["expense"] = 0;
									$arr["time"] = $now;
									$arr["cat"] = 3;
									$arr["staffid"] = 0;
									$arr["notes"] = "开卡赠送积分";
									$arr["score"] = $value["item_value"];
									M("Member_card_use_record")->add($arr);
									M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->setInc("total_score", $arr["score"]);
								}
								else {
									$cinfo = M("Member_card_coupon")->where(array("token" => $this->token, "id" => $value["item_value"]))->find();

									if ($cinfo["is_weixin"] == 0) {
										$data["token"] = $this->token;
										$data["wecha_id"] = $this->wecha_id;
										$data["coupon_id"] = $value["item_value"];
										$data["is_use"] = "0";
										$data["cardid"] = $cardSet["id"];
										$data["add_time"] = $now;

										if ($cinfo["type"] == 1) {
											$data["coupon_attr"] = serialize(array("coupon_name" => $cinfo["title"]));
										}
										else if ($cinfo["type"] == 2) {
											$data["coupon_attr"] = serialize(array("coupon_name" => $cinfo["title"], "gift_name" => $cinfo["gift_name"], "integral" => $cinfo["integral"]));
										}
										else {
											$data["coupon_attr"] = serialize(array("coupon_name" => $cinfo["title"], "least_cost" => $cinfo["least_cost"], "reduce_cost" => $cinfo["reduce_cost"]));
										}

										if ($value["item_attr"] == 1) {
											$data["coupon_type"] = "1";
										}
										else if ($value["item_attr"] == 2) {
											$data["coupon_type"] = "3";
										}
										else {
											$data["coupon_type"] = "2";
										}

										$data["cancel_code"] = $this->_create_code(12);
										M("Member_card_coupon_record")->add($data);
									}
								}
							}
						}
					}
					else {
						M("Member_card_create")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->delete();
						M("Member_card_create")->where(array("id" => $card["id"]))->save(array("wecha_id" => $this->wecha_id));
					}
				}
			}
		}

		M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->setField("issub", "1");
	}

	public function _create_code($length, $type)
	{
		$array = array("number" => "0123456789", "string" => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "mixed" => "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ");
		$string = $array[$type];
		$count = strlen($string) - 1;
		$rand = "";

		for ($i = 0; $i < $length; $i++) {
			$rand .= $string[mt_rand(0, $count)];
		}

		return $rand;
	}

	public function unsub()
	{
		M("Userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->setField("issub", "-1");
	}

	public function curlGet($url, $method, $data)
	{
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
}


?>
