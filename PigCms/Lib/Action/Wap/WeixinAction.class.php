<?php

class WeixinAction extends BaseAction
{
	public $token;
	public $wecha_id;
	public $payConfig;
	public $_issystem = 0;

	public function __construct()
	{
		parent::_initialize();
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

		if (empty($_GET['platform']) && empty($_GET['pl'])) {
			$payConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$this->payConfig = array_map('trim', $payConfigInfo['weixin']);

			if ($this->payConfig['open'] == 2) {
				$this->_issystem = 2;
			}
		}
		else {
			$payConfigInfo['new_appid'] = C('appid');
			$payConfigInfo['appsecret'] = C('secret');
			$payConfigInfo['mchid'] = C('platform_weixin_mchid');
			$payConfigInfo['key'] = C('platform_weixin_key');
			$this->payConfig = $payConfigInfo;
			$this->_issystem = 1;
		}

		$this->assign('issystem', $this->_issystem);
		if ((ACTION_NAME == 'pay') || (ACTION_NAME == 'new_pay')) {
			if (empty($this->payConfig['is_old'])) {
				$this->new_pay();
				exit();
			}
			else {
				$this->pay();
				exit();
			}
		}
	}

	public function qrcode()
	{
		include '../PigCms/Lib/ORG/phpqrcode.php';
		$url = urldecode(trim($_GET['url']));
		QRcode::png($url, false, 1, 8);
	}

	private function code($orderid, $price)
	{
		import('@.ORG.Weixinnewpay.WxPayPubHelper');
		$jsApi = new JsApi_pub($this->payConfig['new_appid'], $this->payConfig['mchid'], $this->payConfig['key'], $this->payConfig['appsecret']);
		$unifiedOrder = new UnifiedOrder_pub($this->payConfig['new_appid'], $this->payConfig['mchid'], $this->payConfig['key'], $this->payConfig['appsecret']);
		$unifiedOrder->setParameter('body', $orderid);

		if ($this->_issystem == 1) {
			$out_trade_no = $orderid . '_system';
		}
		else if ($this->_issystem == 2) {
			$out_trade_no = $orderid . '_other';
		}

		$unifiedOrder->setParameter('out_trade_no', $out_trade_no);
		$unifiedOrder->setParameter('total_fee', $price * 100);

		if (strpos(CONF_PATH, 'DataPig')) {
			$noticeFileName = 'notice_datapig.php';
		}
		else if (strpos(CONF_PATH, 'PigData')) {
			$noticeFileName = 'notice_pigdata.php';
		}
		else {
			$noticeFileName = 'notice.php';
		}

		$unifiedOrder->setParameter('notify_url', $this->siteUrl . '/wxpay/' . $noticeFileName);
		$unifiedOrder->setParameter('trade_type', 'NATIVE');
		$unifiedOrder->setParameter('attach', 'token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&from=' . $_GET['from'] . '&pl=' . $this->_issystem);
		$prepay_result = $unifiedOrder->getPrepayResult();

		if ($prepay_result['return_code'] == 'FAIL') {
			return array('error' => 1, 'msg' => '没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：' . $prepay_result['return_msg']);
		}

		if ($prepay_result['err_code']) {
			return array('error' => 1, 'msg' => '没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：' . $prepay_result['err_code_des']);
		}

		$jsApi->setPrepayId($prepay_result['prepay_id']);
		return $prepay_result['code_url'];
	}

	public function new_pay()
	{
		$orderid = $_GET['single_orderid'];
		$payHandel = new payHandle($this->token, $_GET['from'], 'weixin');
		$orderInfo = $payHandel->beforePay($orderid);
		$price = $orderInfo['price'];
		$this->assign('price', $price);

		if ($orderInfo['paid']) {
			$returnUrl = $this->siteUrl . '/index.php?g=Wap&m=' . $_GET['from'] . '&a=payReturn&nohandle=1&token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&orderid=' . $orderid;
			$this->redirect($returnUrl);
			exit();
		}

		if ($this->_issystem) {
			$url = $this->code($orderid, $price);

			if (!is_string($url)) {
				$this->error($url['msg']);
			}

			$this->assign('url', urlencode($url));
		}
		else {
			import('@.ORG.Weixinnewpay.WxPayPubHelper');
			$jsApi = new JsApi_pub($this->payConfig['new_appid'], $this->payConfig['mchid'], $this->payConfig['key'], $this->payConfig['appsecret']);
			$unifiedOrder = new UnifiedOrder_pub($this->payConfig['new_appid'], $this->payConfig['mchid'], $this->payConfig['key'], $this->payConfig['appsecret']);
			$unifiedOrder->setParameter('openid', $this->wecha_id);
			$unifiedOrder->setParameter('body', $orderid);
			$unifiedOrder->setParameter('out_trade_no', $orderid);
			$unifiedOrder->setParameter('total_fee', $price * 100);

			if (strpos(CONF_PATH, 'DataPig')) {
				$noticeFileName = 'notice_datapig.php';
			}
			else if (strpos(CONF_PATH, 'PigData')) {
				$noticeFileName = 'notice_pigdata.php';
			}
			else {
				$noticeFileName = 'notice.php';
			}

			$unifiedOrder->setParameter('notify_url', $this->siteUrl . '/wxpay/' . $noticeFileName);
			$unifiedOrder->setParameter('trade_type', 'JSAPI');
			$unifiedOrder->setParameter('attach', 'token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&from=' . $_GET['from']);
			$prepay_id = $unifiedOrder->getPrepayId();

			if (empty($prepay_id)) {
				$this->error('没有获取到微信支付预支付ID，请管理员检查微信支付配置项！');
			}

			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
			$this->assign('jsApiParameters', $jsApiParameters);
		}

		$from = $_GET['from'];
		$from = ($from ? $from : 'Groupon');
		$from = ($from != 'groupon' ? $from : 'Groupon');
		$returnUrl = $this->siteUrl . '/index.php?g=Wap&m=' . $from . '&a=payReturn&nohandle=1&token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&orderid=' . $orderid;
		$this->assign('returnUrl', $returnUrl);
		$message = '';
		$message .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" /><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black" /><meta name="format-detection" content="telephone=no" /><link href="/tpl/Wap/default/common/css/style/css/hotels.css" rel="stylesheet" type="text/css" /><title>微信支付</title>';

		if (empty($this->_issystem)) {
			$message .= '<script language="javascript">function callpay(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $jsApiParameters . ',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg=="get_brand_wcpay_request:ok"){document.getElementById("payDom").style.display="none";document.getElementById("successDom").style.display="";setTimeout("window.location.href = \'' . $returnUrl . '\'",2000);}else{if(res.err_msg == "get_brand_wcpay_request:cancel"){var err_msg = "您取消了支付";}else if(res.err_msg == "get_brand_wcpay_request:fail"){var err_msg = "支付失败<br/>错误信息："+res.err_desc;}else{var err_msg = res.err_msg +"<br/>"+res.err_desc;}document.getElementById("payDom").style.display = "none";document.getElementById("failDom").style.display = "";document.getElementById("failRt").innerHTML = err_msg;}});}</script>';
		}

		$message .= '</head><body style="padding-top:20px;"><style>.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid #d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}</style><div id="payDom" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>金额</th><td>' . $price . '元</td></tr></table></li></ul>';

		if ($this->_issystem) {
			$message .= '<ul class="round" id="cross_pay"><li class="title mb" style="text-align:center"><span class="none">微信扫描支付</span></li><li class="nob" style="margin-bottom: 18px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><td style="text-align:center" ><img src="' . U('Weixin/qrcode', array('url' => $url)) . '" height="200" id="show_success"></td></tr><tr><td style="text-align:center">长按图片[识别二维码]付款</td></tr></table></li><li class="mb" style="text-align:center;margin-top: 20px;border-top: 1px solid #C6C6C6;" id="success"><span class="none"><a href="' . $returnUrl . '" style="color:#459ae9">我已经支付成功</a></span></li></ul>';
		}
		else {
			$message .= '<div class="footReturn" style="text-align:center" id="pay_div"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="点击进行微信支付" /></div>';
		}

		$message .= '</div><div id="failDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="重新进行支付" /></div></div><div id="successDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><td>您已支付成功，页面正在跳转...</td></tr></table><div id="failRt"></div></li></ul></div></body></html>';
		echo $message;
	}

	public function pay()
	{
		import('@.ORG.Weixinpay.CommonUtil');
		import('@.ORG.Weixinpay.WxPayHelper');
		$commonUtil = new CommonUtil();
		$orderid = $_GET['single_orderid'];
		$payHandel = new payHandle($this->token, $_GET['from'], 'weixin');
		$orderInfo = $payHandel->beforePay($orderid);
		$price = $orderInfo['price'];

		if ($orderInfo['paid']) {
			exit('您已经支付过此次订单！');
		}

		$wxPayHelper = new WxPayHelper($this->payConfig['appid'], $this->payConfig['paysignkey'], $this->payConfig['partnerkey']);
		$wxPayHelper->setParameter('bank_type', 'WX');
		$wxPayHelper->setParameter('body', $orderid);
		$wxPayHelper->setParameter('partner', $this->payConfig['partnerid']);
		$wxPayHelper->setParameter('out_trade_no', $orderid);
		$wxPayHelper->setParameter('total_fee', $price * 100);
		$wxPayHelper->setParameter('fee_type', '1');
		$wxPayHelper->setParameter('notify_url', $this->siteUrl . '/index.php?g=Wap&m=Weixin&nohandle=1&a=return_url&token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&from=' . $_GET['from']);
		$wxPayHelper->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
		$wxPayHelper->setParameter('input_charset', 'GBK');
		$url = $wxPayHelper->create_biz_package();
		$this->assign('url', $url);
		$from = $_GET['from'];
		$from = ($from ? $from : 'Groupon');
		$from = ($from != 'groupon' ? $from : 'Groupon');

		switch ($from) {
		default:
		case 'Groupon':
			break;
		}

		$returnUrl = $this->siteUrl . '/index.php?g=Wap&m=' . $from . '&a=payReturn&nohandle=1&token=' . $_GET['token'] . '&wecha_id=' . $_GET['wecha_id'] . '&orderid=' . $orderid;
		$this->assign('returnUrl', $returnUrl);
		$message = '';
		$message .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" /><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black" /><meta name="format-detection" content="telephone=no" /><link href="/tpl/Wap/default/common/css/style/css/hotels.css" rel="stylesheet" type="text/css" /><title>微信支付</title>';

		if (empty($this->_issystem)) {
			$message .= '<script language="javascript">function callpay(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $jsApiParameters . ',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg=="get_brand_wcpay_request:ok"){document.getElementById("payDom").style.display="none";document.getElementById("successDom").style.display="";setTimeout("window.location.href = \'' . $returnUrl . '\'",2000);}else{if(res.err_msg == "get_brand_wcpay_request:cancel"){var err_msg = "您取消了支付";}else if(res.err_msg == "get_brand_wcpay_request:fail"){var err_msg = "支付失败<br/>错误信息："+res.err_desc;}else{var err_msg = res.err_msg +"<br/>"+res.err_desc;}document.getElementById("payDom").style.display = "none";document.getElementById("failDom").style.display = "";document.getElementById("failRt").innerHTML = err_msg;}});}</script>';
		}

		$message .= '</head><body style="padding-top:20px;"><style>.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid #d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}</style><div id="payDom" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>金额</th><td>' . $price . '元</td></tr></table></li></ul>';

		if ($this->_issystem) {
			$message .= '<ul class="round" id="cross_pay"><li class="title mb" style="text-align:center"><span class="none">微信扫描支付</span></li><li class="nob" style="margin-bottom: 18px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><td style="text-align:center" ><img src="' . U('Weixin/qrcode', array('url' => $url)) . '" height="200" id="show_success"></td></tr><tr><td style="text-align:center">长按图片[识别二维码]付款</td></tr></table></li><li class="mb" style="text-align:center;margin-top: 20px;border-top: 1px solid #C6C6C6;" id="success"><span class="none"><a href="' . $returnUrl . '" style="color:#459ae9">我已经支付成功</a></span></li></ul>';
		}
		else {
			$message .= '<div class="footReturn" style="text-align:center" id="pay_div"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="点击进行微信支付" /></div>';
		}

		$message .= '</div><div id="failDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="重新进行支付" /></div></div><div id="successDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><td>您已支付成功，页面正在跳转...</td></tr></table><div id="failRt"></div></li></ul></div></body></html>';
		echo $message;
	}

	public function after_pay_auto($out_trade_no, $type, $transaction_id)
	{
		file_put_contents('../DataPig/test.txt', $out_trade_no . ';;;;' . $type . ';;;;' . $transaction_id);
	}

	public function new_return_url()
	{
		$out_trade_no = $this->_get('out_trade_no');
		$out_trade_no = str_replace(array('_system', '_other'), '', $out_trade_no);
		if (intval($_GET['total_fee']) && !intval($_GET['trade_state'])) {
			$payHandel = new payHandle($_GET['token'], $_GET['from'], 'weixin');
			$orderInfo = $payHandel->afterPay($out_trade_no, $_GET['transaction_id'], $_GET['transaction_id']);
			$className = 'ThirdPay' . $_GET['from'];

			if (class_exists('ThirdPay' . $_GET['from'])) {
				$className::index($out_trade_no, 'weixin', $_GET['transaction_id']);
			}

			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
			exit('SUCCESS');
		}
		else {
			exit('付款失败');
		}
	}

	public function return_url()
	{
		S('pay2' . $this->token, $_GET);
		F('pay2' . $this->token, $_GET);
		$out_trade_no = $this->_get('out_trade_no');
		$out_trade_no = str_replace(array('_system', '_other'), '', $out_trade_no);
		if (intval($_GET['total_fee']) && !intval($_GET['trade_state'])) {
			$payHandel = new payHandle($_GET['token'], $_GET['from'], 'weixin');
			$orderInfo = $payHandel->afterPay($out_trade_no, $_GET['transaction_id'], $_GET['transaction_id']);
			$className = 'ThirdPay' . $_GET['from'];

			if (class_exists('ThirdPay' . $_GET['from'])) {
				$className::index($out_trade_no, 'weixin', $_GET['transaction_id']);
			}

			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
			exit('SUCCESS');
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

	public function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);

		if ($errorno) {
			return array('rt' => false, 'errorno' => $errorno);
		}
		else {
			$js = json_decode($tmpInfo, 1);

			if ($js['errcode'] == '0') {
				return array('rt' => true, 'errorno' => 0);
			}
			else {
				$this->error('发生错误：错误代码' . $js['errcode'] . ',微信返回错误信息：' . $js['errmsg']);
			}
		}
	}
}

?>
