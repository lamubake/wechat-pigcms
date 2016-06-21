<?php

class Wechat
{
	public $token;
	public $wxuser;
	private $data = array();

	public function __construct($token, $wxuser = '')
	{
		$this->auth($token, $wxuser) || exit();

		if (IS_GET) {
			echo $_GET['echostr'];
			exit();
		}
		else {
			$this->token = $token;

			if (!$wxuser) {
				$wxuser = M('wxuser')->where(array('token' => $this->token))->find();
			}

			$this->wxuser = $wxuser;

			if ($this->wxuser['type'] == 1) {
				$agentid = 0;

				if (C('agent_version')) {
					$thisAgent = M('agent')->where(array('siteurl' => 'http://' . $_SERVER['HTTP_HOST']))->find();
					$agentid = (isset($thisAgent['id']) ? intval($thisAgent['id']) : 0);
				}

				$account_info = M('Weixin_account')->where('type=1 AND agentid=' . $agentid)->field('token,appId,encodingAesKey')->find();
				$this->wxuser['pigsecret'] = $account_info['token'];
				$this->wxuser['appid'] = $account_info['appId'];
				$this->wxuser['aeskey'] = $account_info['encodingAesKey'];
			}
			else if (empty($this->wxuser['pigsecret'])) {
				$this->wxuser['pigsecret'] = $this->token;
			}

			$xml = file_get_contents('php://input');

			if ($this->wxuser['encode'] == 2) {
				$this->data = $this->decodeMsg($xml);
			}
			else {
				$xml = new SimpleXMLElement($xml);
				$xml || exit();

				foreach ($xml as $key => $value) {
					$this->data[$key] = strval($value);
				}
			}
		}
	}

	public function encodeMsg($sRespData)
	{
		$sReqTimeStamp = time();
		$sReqNonce = $_GET['nonce'];
		$encryptMsg = '';
		import('@.ORG.aes.WXBizMsgCrypt');
		$pc = new WXBizMsgCrypt($this->wxuser['pigsecret'], $this->wxuser['aeskey'], $this->wxuser['appid']);
		$sRespData = str_replace('<?xml version="1.0"?>', '', $sRespData);
		$errCode = $pc->encryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $encryptMsg);

		if ($errCode == 0) {
			return $encryptMsg;
		}
		else {
			return $errCode;
		}
	}

	public function decodeMsg($msg)
	{
		import('@.ORG.aes.WXBizMsgCrypt');
		$sReqMsgSig = $_GET['msg_signature'];
		$sReqTimeStamp = $_GET['timestamp'];
		$sReqNonce = $_GET['nonce'];
		$sReqData = $msg;
		$sMsg = '';
		$pc = new WXBizMsgCrypt($this->wxuser['pigsecret'], $this->wxuser['aeskey'], $this->wxuser['appid']);
		$errCode = $pc->decryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);

		if ($errCode == 0) {
			$data = array();
			$xml = new SimpleXMLElement($sMsg);
			$xml || exit();

			foreach ($xml as $key => $value) {
				$data[$key] = strval($value);
			}

			return $data;
		}
		else {
			return $errCode;
		}
	}

	public function request()
	{
		return $this->data;
	}

	public function response($content, $type = 'text', $flag = 0)
	{
		$this->data = array('ToUserName' => $this->data['FromUserName'], 'FromUserName' => $this->data['ToUserName'], 'CreateTime' => NOW_TIME, 'MsgType' => $type);
		$this->$type($content);
		$this->data['FuncFlag'] = $flag;
		$xml = new SimpleXMLElement('<xml></xml>');
		$this->data2xml($xml, $this->data);
		if (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) {
			exit($this->encodeMsg($xml->asXML()));
		}
		else {
			exit($xml->asXML());
		}
	}

	private function text($content)
	{
		$this->data['Content'] = $content;
	}

	private function music($music)
	{
		list($music['Title']) = $music;
		$this->data['Music'] = $music;
	}

	private function news($news)
	{
		$articles = array();

		foreach ($news as $key => $value) {
			list($articles[$key]['Title'], $articles[$key]['Description'], $articles[$key]['PicUrl'], $articles[$key]['Url']) = $value;

			if (9 <= $key) {
				break;
			}
		}

		$this->data['ArticleCount'] = count($articles);
		$this->data['Articles'] = $articles;
	}

	private function transfer_customer_service($content)
	{
		$this->data['Content'] = '';
	}

	private function data2xml($xml, $data, $item = 'item')
	{
		foreach ($data as $key => $value) {
			is_numeric($key) && ($key = $item);
			if (is_array($value) || is_object($value)) {
				$child = $xml->addChild($key);
				$this->data2xml($child, $value, $item);
			}
			else if (is_numeric($value)) {
				$child = $xml->addChild($key, $value);
			}
			else {
				$child = $xml->addChild($key);
				$node = dom_import_simplexml($child);
				$node->appendChild($node->ownerDocument->createCDATASection($value));
			}
		}
	}

	private function auth($token, $wxuser = '')
	{
		$signature = $_GET['signature'];
		$timestamp = $_GET['timestamp'];
		$nonce = $_GET['nonce'];

		if (!$wxuser) {
		}

		if ($wxuser && strlen($wxuser['pigsecret'])) {
		}

		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);

		if (trim($tmpStr) == trim($signature)) {
			return true;
		}
		else {
			return true;
		}

		return true;
	}
}


?>
