<?php

class TenpayComputerAction extends BaseAction
{
	public $token;
	public $wecha_id;
	public $payConfig;

	public function __construct()
	{
		$this->token = $this->_get('token');
		$this->wecha_id = $this->_get('wecha_id');

		if (!$this->token) {
			$product_cart_model = M('product_cart');
			$out_trade_no = $this->_get('out_trade_no');
			$order = $product_cart_model->where(array('orderid' => $out_trade_no))->find();

			if (!$order) {
				$order = $product_cart_model->where(array('id' => intval($this->_get('out_trade_no'))))->find();
			}

			$this->token = $order['token'];
		}

		if (empty($_GET['platform'])) {
			$payConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$this->payConfig = $payConfigInfo['tenpayComputer'];
		}
		else {
			$payConfigInfo['partnerid'] = C('platform_tenpayComputer_partnerid');
			$payConfigInfo['partnerkey'] = C('platform_tenpayComputer_partnerkey');
			$this->payConfig = $payConfigInfo;
		}
	}

	public function pay()
	{
		$orderid = $_GET['orderid'];

		if (!$orderid) {
			$orderid = $_GET['single_orderid'];
		}

		$payHandel = new payHandle($this->token, $_GET['from'], 'tenpayComputer');
		$orderInfo = $payHandel->beforePay($orderid);
		$price = $orderInfo['price'];
		$orderName = $_GET['orderName'];
		$notify_url = C('site_url') . '/index.php?g=Wap&m=TenpayComputer&a=notify_url';

		if ($_GET['platform']) {
			$return_url = C('site_url') . '/index.php?g=Wap&m=TenpayComputer&a=return_url&token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&from=' . $_GET['from'] . '&pl=1';
		}
		else {
			$return_url = C('site_url') . '/index.php?g=Wap&m=TenpayComputer&a=return_url&token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&from=' . $_GET['from'];
		}

		if ($orderInfo['paid']) {
			exit('您已经支付过此次订单！');
		}

		if (!$price) {
			exit('必须有价格才能支付');
		}

		$total_fee = floatval($price) * 100;
		import('@.ORG.TenpayComputer.RequestHandler');
		$out_trade_no = $orderid;
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$key = $this->payConfig['partnerkey'];
		$partner = $this->payConfig['partnerid'];
		$reqHandler->setKey($key);
		$reqHandler->setGateUrl('https://gw.tenpay.com/gateway/pay.htm');
		$reqHandler->setParameter('partner', $partner);
		$reqHandler->setParameter('out_trade_no', $out_trade_no);
		$reqHandler->setParameter('total_fee', $total_fee);
		$reqHandler->setParameter('return_url', $return_url);
		$reqHandler->setParameter('notify_url', $notify_url);
		$reqHandler->setParameter('body', '财付通在线支付');
		$reqHandler->setParameter('bank_type', 'DEFAULT');
		$reqHandler->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
		$reqHandler->setParameter('fee_type', '1');
		$reqHandler->setParameter('subject', 'weixin');
		$reqHandler->setParameter('sign_type', 'MD5');
		$reqHandler->setParameter('service_version', '1.0');
		$reqHandler->setParameter('input_charset', 'utf-8');
		$reqHandler->setParameter('sign_key_index', '1');
		$reqHandler->setParameter('attach', '');
		$reqHandler->setParameter('product_fee', '');
		$reqHandler->setParameter('transport_fee', '0');
		$reqHandler->setParameter('time_start', date('YmdHis'));
		$reqHandler->setParameter('time_expire', '');
		$reqHandler->setParameter('buyer_id', '');
		$reqHandler->setParameter('goods_tag', '');
		$reqHandler->setParameter('trade_mode', 1);
		$reqHandler->setParameter('transport_desc', '');
		$reqHandler->setParameter('trans_type', '1');
		$reqHandler->setParameter('agentid', '');
		$reqHandler->setParameter('agent_type', '');
		$reqHandler->setParameter('seller_id', '');
		$reqUrl = $reqHandler->getRequestURL();
		$debugInfo = $reqHandler->getDebugInfo();
		header('Location:' . $reqUrl);
	}

	public function return_url()
	{
		import('@.ORG.TenpayComputer.ResponseHandler');
		$resHandler = new ResponseHandler();
		$key = $this->payConfig['partnerkey'];
		$resHandler->setKey($key);
		$out_trade_no = $this->_get('out_trade_no');
		$notify_id = $resHandler->getParameter('notify_id');
		$out_trade_no = $resHandler->getParameter('out_trade_no');
		$transaction_id = $resHandler->getParameter('transaction_id');
		$total_fee = $resHandler->getParameter('total_fee');
		$discount = $resHandler->getParameter('discount');
		$trade_state = $resHandler->getParameter('trade_state');
		$trade_mode = $resHandler->getParameter('trade_mode');

		if ('0' == $trade_state) {
			$payHandel = new payHandle($_GET['token'], $_GET['from'], 'tenpayComputer');
			$orderInfo = $payHandel->afterPay($out_trade_no, $_GET['transaction_id']);
			$from = $payHandel->getFrom();
			$this->redirect('/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&orderid=' . $out_trade_no);
		}
		else {
			exit('付款失败');
		}
	}

	public function notify_url()
	{
		echo 'success';
		eixt();
	}
}

?>
