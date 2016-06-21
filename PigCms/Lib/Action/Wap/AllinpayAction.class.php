<?php

class AllinpayAction extends BaseAction
{
	public $token;
	public $wecha_id;
	public $payConfig;

	public function __construct()
	{
		parent::_initialize();

		if ($_GET['wid']) {
			$database_userinfo = D('Userinfo');
			$condition_userinfo['id'] = $_GET['wid'];
			$now_user_info = $database_userinfo->field('`wecha_id`,`token`')->where($condition_userinfo)->find();
			$this->wecha_id = $now_user_info['wecha_id'];
			$this->token = $now_user_info['token'];
		}
		else {
			$this->wecha_id = $this->_get('wecha_id');
			$this->token = $this->_get('token');
		}

		if (empty($_GET['platform'])) {
			$payConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$this->payConfig = $payConfigInfo['allinpay'];
		}
		else {
			$payConfigInfo['merchantId'] = C('platform_allinpay_merchantId');
			$payConfigInfo['merchantKey'] = C('platform_allinpay_merchantKey');
			$this->payConfig = $payConfigInfo;
		}
	}

	public function pay()
	{
		$orderName = $_GET['orderName'];

		if (!$orderName) {
			$orderName = microtime();
		}

		$orderid = $_GET['orderid'];

		if (!$orderid) {
			$orderid = $_GET['single_orderid'];
		}

		$payHandel = new payHandle($this->token, $_GET['from'], 'allinpay');
		$orderInfo = $payHandel->beforePay($orderid);

		if ($orderInfo['paid']) {
			exit('您已经支付过此次订单！');
		}

		if (!$orderInfo['price']) {
			exit('必须有价格才能支付');
		}

		$database_userinfo = D('Userinfo');
		$condition_userinfo['wecha_id'] = $this->wecha_id;
		$now_user_info = $database_userinfo->field('`id` `wid`')->where($condition_userinfo)->find();

		if (empty($now_user_info)) {
			$this->error('查询数据异常！请重试。');
		}

		if (empty($_GET['platform'])) {
			$return_url = $this->siteUrl . '/index.php?g=Wap&m=Allinpay&a=r_u&wid=' . $now_user_info['wid'] . '&from=' . $_GET['from'];
		}
		else {
			$return_url = $this->siteUrl . '/index.php?g=Wap&m=Allinpay&a=r_u&wid=' . $now_user_info['wid'] . '&from=' . $_GET['from'] . '&platform=1';
		}

		import('@.ORG.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$allinpayClass->setParameter('payUrl', 'https://service.allinpay.com/mobilepayment/mobile/SaveMchtOrderServlet.action');
		$allinpayClass->setParameter('pickupUrl', $return_url);
		$allinpayClass->setParameter('receiveUrl', $this->siteUrl . '/index.php?g=Wap&m=Allinpay&a=notify_url');
		$allinpayClass->setParameter('merchantId', $this->payConfig['merchantId']);
		$allinpayClass->setParameter('orderNo', $orderInfo['orderid']);
		$allinpayClass->setParameter('orderAmount', floatval($orderInfo['price']) * 100);
		$allinpayClass->setParameter('orderDatetime', date('YmdHis', $_SERVER['REQUEST_TIME']));
		$allinpayClass->setParameter('productName', $orderName);
		$allinpayClass->setParameter('payType', 0);
		$allinpayClass->setParameter('key', $this->payConfig['merchantKey']);
		$allinpayClass->sendRequestForm();
	}

	public function r_u()
	{
		import('@.ORG.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$verify_result = $allinpayClass->verify_pay($this->payConfig['merchantKey']);

		if (!$verify_result['error']) {
			$payHandel = new payHandle($this->token, $_GET['from'], 'allinpay');
			$orderInfo = $payHandel->afterPay($verify_result['order_id'], $verify_result['paymentOrderId']);
			$from = $payHandel->getFrom();
			$this->redirect('/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&orderid=' . $verify_result['order_id']);
		}
		else {
			$this->error($verify_result['msg']);
		}
	}

	public function notify_url()
	{
		echo 'SUCCESS';
	}
}

?>
