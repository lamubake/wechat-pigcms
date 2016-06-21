<?php

class AlipaytypeAction extends WapAction
{
	protected $alipay_config = array();
	protected $alipay_save_config;
	public $base_path;

	public function _initialize()
	{
		parent::_initialize();
		$this->base_path = APP_PATH . 'Lib/ORG/WapAlipay/';
		if (($_GET['platform'] || $_GET['pl']) && C('platform_open') && C('platform_alipay_open')) {
			$this->alipay_save_config['pid'] = C('platform_alipay_pid');
			$this->alipay_save_config['name'] = C('platform_alipay_name');
			$this->alipay_save_config['key'] = C('platform_alipay_key');
		}
		else {
			$alipay_save_config = M('Alipay_config')->where(array('token' => $this->token))->find();
			$alipay_save_config = unserialize($alipay_save_config['info']);
			$this->alipay_save_config = $alipay_save_config['alipay'];
		}

		$this->alipay_config = array('partner' => $this->alipay_save_config['pid'], 'seller_email' => $this->alipay_save_config['name'], 'key' => $this->alipay_save_config['key'], 'private_key_path' => $this->base_path . 'key/rsa_private_key.pem', 'ali_public_key_path' => $this->base_path . 'key/alipay_public_key.pem', 'sign_type' => 'MD5', 'input_charset' => 'utf-8', 'cacert' => $this->base_path . 'cacert.pem', 'transport' => 'http');
	}

	public function pay()
	{
		$orderName = htmlentities($_GET['orderName']);
		$orderid = htmlentities($_GET['orderid']);
		$from = htmlentities($_GET['from']);

		if (!$orderName) {
			$orderName = microtime();
		}

		if (!$orderid) {
			$orderid = htmlentities($_GET['single_orderid']);
		}

		$payHandel = new payHandle($this->token, $from, 'alipay');
		$orderInfo = $payHandel->beforePay($orderid);
		$price = $orderInfo['price'];

		if ($orderInfo['paid']) {
			exit('您已经支付过此次订单！');
		}

		if (!$price) {
			exit('必须有价格才能支付');
		}

		require_once $this->base_path . 'lib/alipay_submit.class.php';
		$format = 'xml';
		$v = '2.0';
		$req_id = date('Ymdhis');
		if (($_GET['platform'] || $_GET['pl']) && C('platform_open') && C('platform_alipay_open')) {
			$query_string_base = 'token=' . $this->token . '|wecha_id=' . $this->wecha_id . '|from=' . $from . '|pl=1';
			$query_string_base_notify = 'token||' . $this->token . '|wecha_id||' . $this->wecha_id . '|from||' . $from . '|pl||1';
		}
		else {
			$query_string_base = 'token=' . $this->token . '|wecha_id=' . $this->wecha_id . '|from=' . $from;
			$query_string_base_notify = 'token||' . $this->token . '|wecha_id||' . $this->wecha_id . '|from||' . $from;
		}

		$notify_url = C('site_url') . '/wxpay/alipaytype_notify_url.php?user_params=' . $query_string_base_notify;
		$call_back_url = C('site_url') . '/wxpay/alipaytype_call_back_url.php?user_params=' . $query_string_base;
		$merchant_url = C('site_url') . '/wxpay/alipaytype_break.php';
		$out_trade_no = $orderid;
		$subject = $orderName;
		$total_fee = $price;
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . trim($this->alipay_config['seller_email']) . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		$para_token = array('service' => 'alipay.wap.trade.create.direct', 'partner' => trim($this->alipay_config['partner']), 'sec_id' => trim($this->alipay_config['sign_type']), 'format' => $format, 'v' => $v, 'req_id' => $req_id, 'req_data' => $req_data, '_input_charset' => trim(strtolower($this->alipay_config['input_charset'])));
		$alipaySubmit = new AlipaySubmit($this->alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		$html_text = urldecode($html_text);
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		$request_token = $para_html_text['request_token'];
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		$parameter = array('service' => 'alipay.wap.auth.authAndExecute', 'partner' => trim($this->alipay_config['partner']), 'sec_id' => trim($this->alipay_config['sign_type']), 'format' => $format, 'v' => $v, 'req_id' => $req_id, 'req_data' => $req_data, '_input_charset' => trim(strtolower($this->alipay_config['input_charset'])));
		$alipaySubmit = new AlipaySubmit($this->alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
		header('Content-type: text/html; charset=utf-8');
		echo $html_text;
	}

	public function notify_url()
	{
		require_once $this->base_path . 'lib/alipay_notify.class.php';
		$_POST['notify_data'] = htmlspecialchars_decode($_POST['notify_data']);
		$from = htmlentities($_GET['from']);
		$alipayNotify = new AlipayNotify($this->alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if ($verify_result) {
			$doc = new DOMDocument();

			if ($this->alipay_config['sign_type'] == 'MD5') {
				$doc->loadXML($_POST['notify_data']);
			}

			if ($this->alipay_config['sign_type'] == '0001') {
				$doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
			}

			if (!empty($doc->getElementsByTagName('notify')->item(0)->nodeValue)) {
				$out_trade_no = $doc->getElementsByTagName('out_trade_no')->item(0)->nodeValue;
				$trade_no = $doc->getElementsByTagName('trade_no')->item(0)->nodeValue;
				$trade_status = $doc->getElementsByTagName('trade_status')->item(0)->nodeValue;

				if ($trade_status == 'TRADE_FINISHED') {
					echo 'success';
				}
				else if ($trade_status == 'TRADE_SUCCESS') {
					$payHandel = new payHandle($this->token, $from, 'alipay');
					$orderInfo = $payHandel->beforePay($out_trade_no);

					if (empty($orderInfo['paid'])) {
						$orderInfo = $payHandel->afterPay($out_trade_no, $trade_no);
						$price = $orderInfo['price'];//自行增加&price
						$url = C('site_url') . '/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&rget=1&orderid=' . $out_trade_no.'&price=' . $price;//自行增加&price
						file_get_contents($url);
					}

					echo 'success';
				}
			}
		}
		else {
			echo 'fail';
		}
	}

	public function call_back_url()
	{
		require_once $this->base_path . 'lib/alipay_notify.class.php';
		$from = htmlentities($_GET['from']);
		$pl = $_GET['pl'];
		unset($_GET['g']);
		unset($_GET['m']);
		unset($_GET['a']);
		unset($_GET['token']);
		unset($_GET['wecha_id']);
		unset($_GET['from']);
		unset($_GET['rget']);
		unset($_GET['user_params']);
		unset($_GET['pl']);
		$alipayNotify = new AlipayNotify($this->alipay_config);
		$verify_result = $alipayNotify->verifyReturn();

		if ($verify_result) {
			$out_trade_no = $_GET['out_trade_no'];
			$trade_no = $_GET['trade_no'];
			$result = $_GET['result'];

			if ($result == 'success') {
				if (!empty($pl)) {
					$_GET['pl'] = 1;
				}

				$payHandel = new payHandle($this->token, $from, 'alipay');
				$orderInfo = $payHandel->beforePay($out_trade_no);
				$price = $orderInfo['price'];//自行增加&price
				$nohandle = '';

				if (empty($orderInfo['paid'])) {
					$orderInfo = $payHandel->afterPay($out_trade_no, $trade_no);
				}
				else {
					$nohandle = '&nohandle=1';
				}

				$url = '/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&orderid=' . $out_trade_no . $nohandle.'&price=' . $price;//自行增加&price
				echo '<script type="text/javascript">if (window.parent.breakIframe) {window.parent.breakIframe("' . $url . '");}else{window.location.href="' . $url . '"}</script>';
			}
			else {
				exit('付款失败');
			}
		}
		else {
			echo '验证失败';
			exit();
		}
	}
}

?>
