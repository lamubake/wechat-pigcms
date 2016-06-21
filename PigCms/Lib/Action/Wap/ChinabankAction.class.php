<?php
/*
 * 网银在线
 * Build	   By PigCms.XiaoHei 2014/09/24
 * Last Modify By PigCms.XiaoHei 2014/09/24
 */
include dirname(__FILE__)."/cbjpay/common/SignUtil.php";					
include dirname(__FILE__)."/cbjpay/common/DesUtils.php";				
include dirname(__FILE__)."/cbjpay/common/ConfigUtil.php";					
include dirname(__FILE__)."/cbjpay/api/cbjpay_submit.class.php";				
//System::load_sys_fun('global');

class ChinabankAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct(){
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->token){
		
		}
		//读取网银在线配置
		if(empty($_GET['platform'])){
			$payConfig = M('Alipay_config')->where(array('token'=>$this->token))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$this->payConfig = $payConfigInfo['chinabank'];
		}else{
			$payConfigInfo['chinabank_account'] = C('platform_chinabank_account');
			$payConfigInfo['chinabank_key'] = C('platform_chinabank_key');
			$this->payConfig = $payConfigInfo;
		}
	}
	public function pay(){
		//得到GET传参的订单名称，若为空则使用系统时间
		$orderName = $_GET['orderName'];
		if (!$orderName){
			$orderName = microtime();
		}
		
		//得到GET传参的系统唯一订单号
		$orderid = $_GET['orderid'];
		if (!$orderid){
			$orderid = $_GET['single_orderid']; //单个订单
		}
		
		//惯例，获取此订单号的信息
		$payHandel = new payHandle($this->token,$_GET['from'],'chinabank');
		$orderInfo = $payHandel->beforePay($orderid);
		
		//判断是否已经支付过
		if($orderInfo['paid']) exit('您已经支付过此次订单！');
		
		//判断价格是否为空。此做法可顺带查出是否是不存的订单号
		if(!$orderInfo['price']) exit('必须有价格才能支付！');
		
		//创建支付表单并显示
		//$data_vid           = trim($this->payConfig['chinabank_account']);
        //$data_orderid       = $orderid;
        //$data_vamount       = $orderInfo['price'];
        //$data_vmoneytype    = 'CNY';
        //$data_vpaykey       = trim($this->payConfig['chinabank_key']);
        $data_vreturnurl    = C('site_url').'/index.php?g=Wap&m=Chinabank&a=return_url&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&from='.$_GET['from'];
		$data_vnotifyurl    = C('site_url').'/index.php?g=Wap&m=Chinabank&a=notify_url&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&from='.$_GET['from'];

		$param = array(
			"version" 			 => "1.0",
			"token" 			 => "", 		
			"merchantNum" 		 => ConfigUtil::get_val_by_key('merchantNum'),
			"merchantRemark" 	 => $orderName,
			"tradeNum" 			 => $orderid,
			"tradeName" 		 => $orderName,
			"tradeDescription" 	 => $orderName,
			"tradeTime" 		 => date('Y-m-d H:i:s', time()),
			"tradeAmount" 		 => $orderInfo['price'] * 100,
			"currency" 			 => "CNY",
			"notifyUrl" 		 => $data_vnotifyurl,
			"successCallbackUrl" => $data_vreturnurl,
			"failCallbackUrl" 	 => $data_vreturnurl
	    );
		
		$sign = SignUtil::sign($param);
		$param["merchantSign"] = $sign;
		
		if ($param["version"] == "1.0") {
			//敏感信息未加密
		} else if ($param["version"] == "2.0") {
			//敏感信息加密
			//获取商户 DESkey
			//对敏感信息进行 DES加密
			$desUtils  = new DesUtils();
			$key = ConfigUtil::get_val_by_key("desKey");
			print_r($key);exit;
			$param["merchantRemark"] 	 = $desUtils->encrypt($param["merchantRemark"],$key);
			$param["tradeNum"] 			 = $desUtils->encrypt($param["tradeNum"],$key);
			$param["tradeName"] 		 = $desUtils->encrypt($param["tradeName"],$key);
			$param["tradeDescription"] 	 = $desUtils->encrypt($param["tradeDescription"],$key);
			$param["tradeTime"] 		 = $desUtils->encrypt($param["tradeTime"],$key);
			$param["tradeAmount"] 		 = $desUtils->encrypt($param["tradeAmount"],$key);
			$param["currency"] 			 = $desUtils->encrypt($param["currency"],$key);
			$param["notifyUrl"] 		 = $desUtils->encrypt($param["notifyUrl"],$key);
			$param["successCallbackUrl"] = $desUtils->encrypt($param["successCallbackUrl"],$key);
			$param["failCallbackUrl"] 	 = $desUtils->encrypt($param["failCallbackUrl"],$key);
			
		}
		//dump($param);
		//die;
		$cbjpaySubmit = new CbjpaySubmit($param);
		$this->url = $cbjpaySubmit->buildRequestForm($param,'POST','submit');
	}
	
	public function notify_url(){
		$resp = $_REQUEST['resp'];
 		if (null == $resp) {
			return;
		}
		
		$desKey = ConfigUtil::get_val_by_key ( "desKey" );
		$md5Key = ConfigUtil::get_val_by_key ( "md5Key" );
		
		$params = $this->xml_to_array ( base64_decode ( $resp ) );

		$ownSign = $this->generateSign ( $params, $md5Key );
		$params_json = json_encode ( $params );
		
		if ($params ['SIGN'] [0] == $ownSign) {
			// 验签正确
			//echo "签名验证正确!" . "\n";
			$des = new DesUtils (); // （秘钥向量，混淆向量）
			$decryptArr = $des->decrypt ( $params ['DATA'] [0], $desKey ); // 加密字符串
			$params ['data'] = $decryptArr;
			$respone = $this->xml_to_array($decryptArr);	
			
			//数据库操作
			if($respone['RETURN']['code'] == '0000'|| $respone['RETURN']['DESC'] == '成功'){ 
			    //订单号
			    $order_id = $respone['TRADE']['ID']; 
				$payHandel = new payHandle($_GET['token'],$_GET['from'],'chinabank');
				//$orderInfo = $payHandel->afterPay($order_id,$_POST['v_idx']);
				$orderInfo = $payHandel->afterPay($order_id,'');

				$from = $payHandel->getFrom();
				$this->redirect('/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$orderInfo['token'].'&wecha_id='.$orderInfo['wecha_id'].'&orderid='.$order_id);
				
			}
			
		}else{
			return;
        } 
	}
	
	public function return_url(){
		$order_id = $_GET['tradeNum'];//print_r($_REQUEST);exit;
		if($order_id > 0)
		{
			$payHandel = new payHandle($_GET['token'],$_GET['from'],'chinabank');
			$orderInfo = $payHandel->afterPay($order_id,'');
			$from = $payHandel->getFrom();
			$this->redirect('/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$orderInfo['token'].'&wecha_id='.$orderInfo['wecha_id'].'&orderid='.$order_id);
		}
		else
		{
			$this->error('支付时发生错误！请检查。');
		}
	}
	
}
?>