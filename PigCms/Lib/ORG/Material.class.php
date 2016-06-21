<?php

class Material
{
	const DEL = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token={ACCESS_TOKEN}";
	const GET = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token={ACCESS_TOKEN}";
	const UPLOAD_IMG = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token={ACCESS_TOKEN}";
	const ADD = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={ACCESS_TOKEN}";
	const BATCHGET = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token={ACCESS_TOKEN}";
	const ADD_NEWS = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token={ACCESS_TOKEN}";
	const UPDATE_NEWS = "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token={ACCESS_TOKEN}";

	public $http;
	public $token;

	public function __construct($wxuser)
	{
		$this->http = HttpClient::getInstance();
		$apiOauth = new apiOauth();

		if (D("Img")->isOpenSync($wxuser)) {
			$this->token = $apiOauth->authorizerToken($wxuser["appid"]);
		}
		else {
			$this->token = $apiOauth->update_authorizer_access_token($wxuser["appid"]);
		}
	}

	public function get($params)
	{
		$result = $this->http->post(strtr(self::GET, array("{ACCESS_TOKEN}" => $this->token)), $params);
		return json_decode($result);
	}

	public function add($params)
	{
		$result = $this->http->post(strtr(self::ADD, array("{ACCESS_TOKEN}" => $this->token)), $params);
		return json_decode($result);
	}

	public function del($params)
	{
		$result = $this->http->post(strtr(self::DEL, array("{ACCESS_TOKEN}" => $this->token)), $params);
		return json_decode($result);
	}

	public function batchget($params)
	{
		$result = $this->http->post(strtr(self::BATCHGET, array("{ACCESS_TOKEN}" => $this->token)), $params);
		return json_decode($result);
	}

	public function uploadImg($params)
	{
		$result = $this->http->post(strtr(self::UPLOAD_IMG, array("{ACCESS_TOKEN}" => $this->token)), $params);
		return json_decode($result);
	}

	public function addNews($params)
	{
		$result = $this->http->post(strtr(self::ADD_NEWS, array("{ACCESS_TOKEN}" => $this->token)), $params);
		return json_decode($result);
	}

	public function updateNews($params)
	{
		$result = $this->http->post(strtr(self::UPDATE_NEWS, array("{ACCESS_TOKEN}" => $this->token)), $params);
		return json_decode($result);
	}
}


?>
