<?php

class Person_cardAction extends WapAction
{
	public $iframeUrl = 'http://www.meihua.com';

	public function _initialize()
	{
		parent::_initialize();
	}

	public function index()
	{
		$agent = $_SERVER['HTTP_USER_AGENT'];

		if (!strpos($agent, 'icroMessenger')) {
		}

		$token = filter_var($this->_get('token'), FILTER_SANITIZE_STRING);
		$uid = $this->_get('uid', 'intval');
		$id = $this->_get('id', 'intval');
		$url = $this->iframeUrl . '/index.php?m=Wap&c=card&a=index&token=' . $token . '&uid=' . $uid . '&id=' . $id;
		redirect($url);
	}
}

?>
