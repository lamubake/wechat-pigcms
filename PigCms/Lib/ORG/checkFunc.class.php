<?php

class checkFunc
{
	public $serverUrl;
	public $key;
	public $topdomain;

	public function __construct($token)
	{
		$this->serverUrl = "http://update.pigcms.cn/";
		$this->key = trim(C("server_key"));
		$this->topdomain = trim(C("server_topdomain"));

		if (!$this->topdomain) {
			$this->topdomain = $this->getTopDomain();
		}

		$this->token = $token;
	}

	public function cfdwdgfds3skgfds3szsd3idsj()
	{
		$url = $this->serverUrl . "func.php?key=" . $this->key . "&domain=" . $this->topdomain;
		$remoteStr = $this->api_notice_increment($url, "");
		$rt = json_decode($remoteStr, 1);

		/*if ($remoteStr != 1) {
			if (is_array($rt)) {
				exit("wow" . $rt["success"]);
			}
			else {
				exit("wow");
			}
		}*/
	}

	public function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);

		if ($errorno) {
			return $errorno;
		}
		else {
			return $tmpInfo;
		}
	}

	public function getTopDomain()
	{
		$host = $_SERVER["HTTP_HOST"];
		$host = strtolower($host);

		if (strpos($host, "/") !== false) {
			$parse = @parse_url($host);
			$host = $parse["host"];
		}

		$topleveldomaindb = array("com", "edu", "gov", "int", "mil", "net", "org", "biz", "info", "pro", "name", "museum", "coop", "aero", "xxx", "idv", "mobi", "cc", "me");
		$str = "";

		foreach ($topleveldomaindb as $v ) {
			$str .= ($str ? "|" : "") . $v;
		}

		$matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))\$";

		if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
			$domain = $matchs[0];
		}
		else {
			$domain = $host;
		}

		return $domain;
	}
}

function fdsrejsie3qklwewerzdagf4ds()
{
}


?>
