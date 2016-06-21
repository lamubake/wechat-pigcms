<?php

include_once "Hongbao_common.php";
class Hongbao extends Hongbao_common
{
	public $nick_name;
	public $send_name;
	public $wishing;
	public $act_name;
	public $remark;
	public $key;
	public $mchid;
	public $wxappid;
	public $parameters;
	public $openid;
	public $money;
	public $url;
	public $fissionurl;
	public $hbinfo;
	public $curl_timeout;
	public $token;
	public $info;
	public $weixin;
	public $total_num;
	public $mch_billno;

	public function __construct($config)
	{
		$this->token = $config["token"];
		$info = M("alipay_config")->where(array("token" => $this->token, "open" => 1))->find();
		$this->info = $info;
		$this->weixin = unserialize($this->info["info"]);
		$this->key = trim($this->weixin["weixin"]["key"]);
		$this->mchid = trim($this->weixin["weixin"]["mchid"]);
		$this->wxappid = trim($this->weixin["weixin"]["new_appid"]);
		$this->openid = $config["openid"];
		$this->money = (double) $config["money"];
		$this->total_num = $config["total_num"] ? (int) $config["total_num"] : 1;
		$this->nick_name = !empty($config["nick_name"]) ? $config["nick_name"] : "合体红包";
		$this->send_name = !empty($config["send_name"]) ? $config["send_name"] : "合体红包";
		$this->wishing = !empty($config["wishing"]) ? $config["wishing"] : "合体红包";
		$this->act_name = !empty($config["act_name"]) ? $config["act_name"] : "合体红包";
		$this->remark = !empty($config["remark"]) ? $config["remark"] : "合体红包";
		$this->mch_billno = $config["mch_billno"];
		$this->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
		$this->fissionurl = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
		$this->hbinfo = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo";
		$this->curl_timeout = 60;
	}

	public function send()
	{
		if (empty($this->token) || empty($this->openid) || empty($this->money)) {
			return json_encode(array("status" => "FAIL", "msg" => "Token,用户openid,金额不能为空"));
		}

		if (empty($this->info)) {
			return json_encode(array("status" => "FAIL", "msg" => "未获取到微信配置信息"));
		}

		if (empty($this->weixin["weixin"]["key"]) || empty($this->weixin["weixin"]["mchid"]) || empty($this->weixin["weixin"]["new_appid"])) {
			return json_encode(array("status" => "FAIL", "msg" => "密钥或商户号或公众号不能为空"));
		}

		$this->parameters = array();
		$this->setParameter("nonce_str", $this->createNoncestr());
		$this->setParameter("mch_id", $this->mchid);
		$this->setParameter("wxappid", $this->wxappid);
		$this->setParameter("nick_name", $this->nick_name);
		$this->setParameter("send_name", $this->send_name);
		$this->setParameter("total_num", 1);
		$this->setParameter("wishing", $this->wishing);
		$this->setParameter("act_name", $this->act_name);
		$this->setParameter("remark", $this->remark);
		$this->setParameter("mch_billno", (string) $this->mchid . date("YmdHis", time()) . rand(1000, 9999));
		$this->setParameter("min_value", $this->money * 100);
		$this->setParameter("max_value", $this->money * 100);
		$this->setParameter("re_openid", (string) $this->openid);
		$this->setParameter("total_amount", $this->money * 100);
		$this->setParameter("client_ip", get_client_ip());
		$this->setParameter("sign", $this->getSign($this->parameters));
		$xml = $this->createXml(1);
		$response_xml = $this->postXmlSSLCurl($xml, $this->url, $this->curl_timeout);
		$curl = json_decode($response_xml, true);

		if ($curl["status"] == "FAIL") {
			return $response_xml;
		}
		else {
			$respon_array = $this->xmlToarray($response_xml);

			if ($respon_array["return_code"] == "FAIL") {
				return json_encode(array("status" => "FAIL", "msg" => $respon_array["return_msg"]));
			}
			else {
				return json_encode(array("status" => "SUCCESS", "msg" => "领取成功", "mch_billno" => $this->parameters["mch_billno"]));
			}
		}
	}

	public function FissionSend()
	{
		if (empty($this->token) || empty($this->openid) || empty($this->money)) {
			return json_encode(array("status" => "FAIL", "msg" => "Token,用户openid,金额不能为空"));
		}

		if (empty($this->info)) {
			return json_encode(array("status" => "FAIL", "msg" => "未获取到微信配置信息"));
		}

		if (empty($this->weixin["weixin"]["key"]) || empty($this->weixin["weixin"]["mchid"]) || empty($this->weixin["weixin"]["new_appid"])) {
			return json_encode(array("status" => "FAIL", "msg" => "密钥或商户号或公众号不能为空"));
		}

		$this->parameters = array();
		$this->setParameter("mch_billno", (string) $this->mchid . date("YmdHis", time()) . rand(1000, 9999));
		$this->setParameter("mch_id", $this->mchid);
		$this->setParameter("wxappid", $this->wxappid);
		$this->setParameter("send_name", $this->send_name);
		$this->setParameter("re_openid", (string) $this->openid);
		$this->setParameter("total_amount", $this->money * 100);
		$this->setParameter("amt_type", "ALL_RAND");
		$this->setParameter("total_num", (string) $this->total_num);
		$this->setParameter("wishing", $this->wishing);
		$this->setParameter("act_name", $this->act_name);
		$this->setParameter("remark", $this->remark);
		$this->setParameter("nonce_str", $this->createNoncestr());
		$this->setParameter("sign", $this->getSign($this->parameters));
		$postxml = $this->createXml(2);
		if (is_array($postxml) && ($postxml["status"] == "fail")) {
			return json_encode(array("status" => "FAIL", "msg" => $postxml["msg"]));
		}
		else {
			$response_xml = $this->postXmlSSLCurl($postxml, $this->fissionurl, $this->curl_timeout);
			$curl = json_decode($response_xml, true);

			if ($curl["status"] == "FAIL") {
				return $response_xml;
			}
			else {
				$respon_array = $this->xmlToarray($response_xml);

				if ($respon_array["return_code"] == "FAIL") {
					return json_encode(array("status" => "FAIL", "msg" => $respon_array["return_msg"]));
				}
				else {
					return json_encode(array("status" => "SUCCESS", "msg" => "领取成功", "mch_billno" => $this->parameters["mch_billno"]));
				}
			}
		}
	}

	public function hongbao_record()
	{
		if (empty($this->token) || empty($this->mch_billno)) {
			return json_encode(array("status" => "FAIL", "msg" => "Token,商户订单号不能为空"));
		}

		if (empty($this->info)) {
			return json_encode(array("status" => "FAIL", "msg" => "未获取到微信配置信息"));
		}

		if (empty($this->weixin["weixin"]["key"]) || empty($this->weixin["weixin"]["mchid"]) || empty($this->weixin["weixin"]["new_appid"])) {
			return json_encode(array("status" => "FAIL", "msg" => "密钥或商户号或公众号不能为空"));
		}

		$this->parameters = array();
		$this->setParameter("nonce_str", $this->createNoncestr());
		$this->setParameter("mch_id", $this->mchid);
		$this->setParameter("appid", $this->wxappid);
		$this->setParameter("mch_billno", $this->mch_billno);
		$this->setParameter("bill_type", "MCHT");
		$this->setParameter("sign", $this->getSign($this->parameters));
		$xml = $this->arrayToXml($this->parameters);
		$response_xml = $this->postXmlSSLCurl($xml, $this->hbinfo, $this->curl_timeout);
		$curl = json_decode($response_xml, true);

		if ($curl["status"] == "FAIL") {
			return $response_xml;
		}
		else {
			$respon_array = $this->xmlToarray($response_xml);

			if ($respon_array["return_code"] == "FAIL") {
				return json_encode(array("status" => "FAIL", "msg" => $respon_array["return_msg"]));
			}
			else {
				return json_encode(array("status" => "SUCCESS", "msg" => $respon_array));
			}
		}
	}

	public function createXml($type)
	{
		$msg = "";
		if ((200 < $this->money) || ($this->money < 1)) {
			$msg = "单个红包金额介于[1.00元，200.00元]之间";
		}
		else if ($this->parameters["mch_id"] == NULL) {
			$msg = "发红包接口中，缺少必填参数mch_id";
		}
		else if ($this->parameters["wxappid"] == NULL) {
			$msg = "发红包接口中，缺少必填参数wxappid";
		}
		else {
			if (($this->parameters["nick_name"] == NULL) && ($type == 1)) {
				$msg = "发红包接口中，缺少必填参数nick_name";
			}
			else if ($this->parameters["send_name"] == NULL) {
				$msg = "发红包接口中，缺少必填参数send_name";
			}
			else if ($this->parameters["wishing"] == NULL) {
				$msg = "发红包接口中，缺少必填参数wishing";
			}
			else if ($this->parameters["total_num"] == NULL) {
				$msg = "发红包接口中，缺少必填参数total_num";
			}
			else if ($this->parameters["act_name"] == NULL) {
				$msg = "发红包接口中，缺少必填参数act_name";
			}
			else if ($this->parameters["remark"] == NULL) {
				$msg = "发红包接口中，缺少必填参数remark";
			}
		}

		if ($msg != "") {
			return array("status" => "fail", "msg" => $msg);
		}

		return $this->arrayToXml($this->parameters);
	}

	public function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}

	public function getSign($array)
	{
		$parames = array();

		foreach ((array) $array as $k => $v ) {
			if (!empty($v)) {
				$parames[$k] = $v;
			}
		}

		ksort($parames);
		$temp_s = "";

		foreach ((array) $parames as $key => $val ) {
			$temp_s .= $key . "=" . $val . "&";
		}

		if (0 < strlen($temp_s)) {
			$reqPar = substr($temp_s, 0, strlen($temp_s) - 1);
		}

		$string = $reqPar . "&key=" . $this->key;
		$signValue = strtoupper(md5($string));
		return $signValue;
	}

	public function postXmlSSLCurl($xml, $url, $second)
	{
		$cert = M("wxcert")->where(array("token" => $this->token))->find();
		if (empty($cert["apiclient_cert"]) || empty($cert["apiclient_key"]) || empty($cert["rootca"])) {
			return json_encode(array("status" => "FAIL", "msg" => "商户未上传证书文件"));
		}
		else {
			$apiclient_cert = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $cert["apiclient_cert"]);
			$apiclient_key = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $cert["apiclient_key"]);
			$rootca = str_replace(array("http://", $_SERVER["HTTP_HOST"]), "", $cert["rootca"]);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . $apiclient_cert);
		curl_setopt($ch, CURLOPT_SSLKEY, getcwd() . $apiclient_key);
		curl_setopt($ch, CURLOPT_CAINFO, getcwd() . $rootca);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		$data = curl_exec($ch);

		if ($data) {
			curl_close($ch);
			return $data;
		}
		else {
			$error = curl_error($ch);
			curl_close($ch);
			return json_encode(array("status" => "FAIL", "msg" => $error));
		}
	}
}


?>
