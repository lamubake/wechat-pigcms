<?php

define('FUWU_PATH', './PigCms/Lib/ORG/Fuwu/');
class FuwuAction extends BaseAction
{
	protected $FuwuToken;

	protected function _initialize()
	{
		parent::_initialize();
		$this->FuwuToken = strip_tags($this->_get('token'));
		$appid = M('Wxuser')->where(array('token' => $this->FuwuToken))->getField('fuwuappid');
		define('FUWU_APPID', $appid);
		$this->canUseFunction('Fuwu');
	}

	public function api()
	{
		require_once FUWU_PATH . 'aop/AopClient.php';
		require_once FUWU_PATH . 'HttpRequst.php';
		$serviceType = HttpRequest::getRequest('service');
		$biz_content = HttpRequest::getRequest('biz_content');

		switch ($serviceType) {
		case 'alipay.service.check':
			$success = '<success>true</success>';
			$biz_content = '<biz_content>MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDVwtjFJVYyf4/sZY+GE3FSeLx7RyOmt+KoWnLi9XsRpQdaXRd+X7mO8kr8Yw6KN9TwgZV8o7iVi3OsuuCD/hgua4Go2oyIWG/NjcaqM3nXOYripfV+BlOdslKBVyAhY6SNuavLt97CVpAe2bIcZH/heNQnHoMQtb/X+KoC6kwouQIDAQAB</biz_content>';
			$tmpArr = array($biz_content, $success);
			$aop = new AopClient();
			$sign = $aop->rsaSign($tmpArr);
			$xmlTmp = '<?xml version="1.0" encoding="GBK"?><alipay><response><success>true</success>' . $biz_content . '</response><sign>' . $sign . '</sign><sign_type>RSA</sign_type></alipay>';
			echo $xmlTmp;
			break;

		case 'alipay.mobile.public.message.notify':
			require_once FUWU_PATH . 'Message.php';
			$post = file_get_contents('php://input');
			$str = urldecode($post);
			$arr = explode('&', $str);
			$arr = explode('=', $arr[1]);
			$msg = new Message($arr[1], $this->FuwuToken);
			break;
		}
	}

	public function canUseFunction($funname)
	{
		if (C('agent_version') && $this->agentid) {
			$func_where = array('agentid' => $this->agentid, 'status' => 1);
			$function_db = M('Agent_function');
		}
		else {
			$func_where = array('status' => 1);
			$function_db = M('Function');
		}

		$func_where['funname'] = $funname;
		$allow = $function_db->where($func_where)->getField('id');
		function map_tolower($v)
		{
			return strtolower($v);
		}
		$uid = M('Wxuser')->where(array('token' => $this->FuwuToken))->getField('uid');
		$gid = M('Users')->where(array('id' => intval($uid)))->getField('gid');
		$user_group = M('User_group')->where(array('id' => intval($gid)))->getField('func');
		$user_group = explode(',', $user_group);
		$user_group = array_map('map_tolower', $user_group);
		if ((in_array(strtolower($funname), $user_group) === false) || !$allow) {
			$this->error('抱歉，您还没有这个功能的使用权限', U('User/Function/index', array('token' => $this->FuwuToken)));
		}
	}
}

?>
