<?php
class PayAction extends AgentAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function recharge(){
		$amount=floatval($this->_post('amount'));
		if (!$amount){
			$amount=floatval($this->_get('amount'));
		}
		//
		$buyDiscount=0;
		if (isset($_GET['discountpriceid'])){
			$thisPrice=M('Agent_price')->where(array('id'=>intval($_GET['discountpriceid'])))->find();
			$buyDiscount=1;
			$amount=$thisPrice['price'];
		}
		if(!$amount)$this->error('请填写金额');
		import("@.ORG.Alipay.AlipaySubmit");
		//支付类型
		$payment_type = "1";
		//必填，不能修改
		//服务器异步通知页面路径
		$notify_url = C('site_url').U('Agent/Pay/notify');
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		if (isset($_GET['discountpriceid'])){
			$return_url = C('site_url').U('Agent/Pay/return_url',array('discountpriceid'=>intval($_GET['discountpriceid'])));
		}else {
			$return_url = C('site_url').U('Agent/Pay/return_url');
		}
		//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		//卖家支付宝帐户
		$seller_email =trim(C('alipay_name'));
		 //商户订单号
		$out_trade_no = $this->thisAgent['id'].'_'.time();
		//商户网站订单系统中唯一订单号，必填
		//订单名称
		if ($buyDiscount){
			$subject='购买优惠套餐'.$thisPrice['name'].'（ID：'.$thisPrice['id'].'）';
		}else {
			$subject ='充值'.$amount.'元';
		}
		//必填
		//付款金额
		$total_fee =$amount;

        $body = $subject;
        //商品展示地址
        $show_url = C('site_url').U('Agent/Basic/expenseRecords');
        //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
		$body = $subject;
		//exit(var_export(array('agentid'=>$this->thisAgent['id'],'des'=>$subject,'time'=>time(),'orderid'=>$out_trade_no,'amount'=>$amount)));
		$data=M('Agent_expenserecords')->add(array('agentid'=>$this->thisAgent['id'],'des'=>$subject,'time'=>time(),'orderid'=>$out_trade_no,'amount'=>$amount));
		$show_url = rtrim(C('site_url'),'/');

		//构造要请求的参数数组，无需改动
		$parameter = array(
		"service" => "create_direct_pay_by_user",
		"partner" =>trim(C('alipay_pid')),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"seller_email"	=> $seller_email,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		"body"	=> $body,
		"show_url"	=> $show_url,
		"anti_phishing_key"	=> $anti_phishing_key,
		"exter_invoke_ip"	=> $exter_invoke_ip,
		"_input_charset"	=>trim(strtolower('utf-8'))
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($this->setconfig());
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认支付");
		echo $html_text;
	}
	public function setconfig(){
		$alipay_config['partner']		= trim(C('alipay_pid'));
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= trim(C('alipay_key'));
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = getcwd().'\\PigCms\\Lib\\ORG\\Alipay\\cacert.pem';
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';		
		return $alipay_config;
	}
	//同步数据处理
	public function return_url (){
		import("@.ORG.Alipay.AlipayNotify");
		$alipayNotify = new AlipayNotify($this->setconfig());
		$verify_result = $alipayNotify->verifyReturn();	
		//if($verify_result) {
			$out_trade_no = $this->_get('out_trade_no');
			//支付宝交易号
			$trade_no =  $this->_get('trade_no');
			//交易状态
			$trade_status =  $this->_get('trade_status');
			if( $this->_get('trade_status') == 'TRADE_FINISHED' ||  $this->_get('trade_status') == 'TRADE_SUCCESS') {
				$indent=M('Agent_expenserecords')->where(array('orderid'=>$out_trade_no))->find();
				if($indent!=false){
					if($indent['status']==1){$this->error('该订单已经处理过,请勿重复操作');}
					//购买套餐，价格为优惠前价格
					if (isset($_GET['discountpriceid'])){
						$thisPrice=M('Agent_price')->where(array('id'=>intval($_GET['discountpriceid'])))->find();
						$indent['amount']=$thisPrice['maxaccount']*$this->thisAgent['wxacountprice'];
					}
					//
					M('Agent')->where(array('id'=>$indent['agentid']))->setInc('money',intval($indent['amount']));
					M('Agent')->where(array('id'=>$indent['agentid']))->setInc('moneybalance',intval($indent['amount']));
				
					$back=M('Agent_expenserecords')->where(array('id'=>$indent['id']))->setField('status',1);
					//
					if($back!=false){
						$this->success('充值成功',U('Agent/Basic/expenseRecords'));
					}else{
						$this->error('充值失败,请在线客服,为您处理',U('Agent/Basic/expenseRecords'));
					}
				}else{
					$this->error('订单不存在',U('Agent/Basic/expenseRecords'));

				}
			}else {
			  $this->error('充值失败，请联系官方客户');
			}
	//	}else {
		//	$this->error('不存在的订单');
		//}
	}
	public function notify(){
		import("@.ORG.Alipay.alipay_notify");
		$alipayNotify = new AlipayNotify($this->setconfig());
		$html_text = $alipaySubmit->buildRequestHttp($parameter);	
	}
	
	
	
	//tenpay
	
	
	public function tenpay_recharge(){	
		$amount=floatval($this->_post('amount'));
		if (!$amount){
			$amount=floatval($this->_get('amount'));
		}
		//
		$buyDiscount=0;
		if (isset($_GET['discountpriceid'])){
			$thisPrice=M('Agent_price')->where(array('id'=>intval($_GET['discountpriceid'])))->find();
			$buyDiscount=1;
			$amount=$thisPrice['price'];
		}
		if(!$amount)$this->error('请填写金额');
		////////////////////////////////////
		//参数数据
		$total_fee =floatval($amount);
		$total_fee1=$total_fee;
		$total_fee =floatval($total_fee)*100;
		$body = '充值';
		$orderName=$body;
		$out_trade_no = $this->thisAgent['id'].'_'.time();

		$notify_url =  C('site_url').U('Agent/Pay/tenpay_recharge_notify');
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		if (isset($_GET['discountpriceid'])){
			$return_url = C('site_url').U('Agent/Pay/tenpay_recharge_return',array('discountpriceid'=>intval($_GET['discountpriceid'])));
		}else {
			$return_url = C('site_url').U('Agent/Pay/tenpay_recharge_return');
		}
		
		//
		if(!$total_fee)exit('必须有价格才能支付');
		
		import("@.ORG.TenpayComputer.RequestHandler");
		
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$key=trim(C('tenpay_partnerkey'));
		$partner=trim(C('tenpay_partnerid'));
		$reqHandler->setKey($key);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		$reqHandler->setParameter("partner", $partner);
		$reqHandler->setParameter("out_trade_no", $out_trade_no);
		$reqHandler->setParameter("total_fee", $total_fee);  //总金额
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("body", '财付通在线支付');
		$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
		$reqHandler->setParameter("fee_type", "1");               //币种
		$reqHandler->setParameter("subject",'member recharge');          //商品名称，（中介交易时必填）

		//系统可选参数
		$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
		$reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
		$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

		//业务可选参数
		$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
		$reqHandler->setParameter("product_fee", "");        	  //商品费用
		$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
		$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
		$reqHandler->setParameter("time_expire", "");             //订单失效时间
		$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
		$reqHandler->setParameter("goods_tag", "");               //商品标记
		$reqHandler->setParameter("trade_mode",1);              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter("transport_desc","");              //物流说明
		$reqHandler->setParameter("trans_type","1");              //交易类型
		$reqHandler->setParameter("agentid","");                  //平台ID
		$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		$reqHandler->setParameter("seller_id","");                //卖家的商户号



		//请求的URL
		$reqUrl = $reqHandler->getRequestURL();

		//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
		/**/
		$debugInfo = $reqHandler->getDebugInfo();
		//
		$data=M('Agent_expenserecords')->add(array('agentid'=>$this->thisAgent['id'],'des'=>$subject,'time'=>time(),'orderid'=>$out_trade_no,'amount'=>$amount));
		//
		header('Location:'.$reqUrl);
	}
	public function tenpay_recharge_return (){		
		
		import("@.ORG.TenpayComputer.ResponseHandler");
		$resHandler = new ResponseHandler();
		$key=trim(C('tenpay_partnerkey'));
		$resHandler->setKey($key);
		$out_trade_no = $this->_get('out_trade_no');
		//if($resHandler->isTenpaySign()) {
		$notify_id = $resHandler->getParameter("notify_id");
		//商户订单号
		$out_trade_no = $resHandler->getParameter("out_trade_no");
		//财付通订单号
		$transaction_id = $resHandler->getParameter("transaction_id");
		//金额,以分为单位
		$total_fee = $resHandler->getParameter("total_fee");
		//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
		$discount = $resHandler->getParameter("discount");
		//支付结果
		$trade_state = $resHandler->getParameter("trade_state");
		//交易模式,1即时到账
		$trade_mode = $resHandler->getParameter("trade_mode");

		if("0" == $trade_state) {
			$total_fee=$total_fee/100;
			$indent=M('Agent_expenserecords')->where(array('orderid'=>$out_trade_no))->find();
			if($indent!=false){
				if($indent['status']==1){$this->error('该订单已经处理过,请勿重复操作');}
				//购买套餐，价格为优惠前价格
				if (isset($_GET['discountpriceid'])){
					$thisPrice=M('Agent_price')->where(array('id'=>intval($_GET['discountpriceid'])))->find();
					$indent['amount']=$thisPrice['maxaccount']*$this->thisAgent['wxacountprice'];
				}
				//
				M('Agent')->where(array('id'=>$indent['agentid']))->setInc('money',intval($indent['amount']));
				M('Agent')->where(array('id'=>$indent['agentid']))->setInc('moneybalance',intval($indent['amount']));

				$back=M('Agent_expenserecords')->where(array('id'=>$indent['id']))->setField('status',1);
				//
				if($back!=false){
					$this->success('充值成功',U('Agent/Basic/expenseRecords'));
				}else{
					$this->error('充值失败,请在线客服,为您处理',U('Agent/Basic/expenseRecords'));
				}
			}else{
				$this->error('订单不存在',U('Agent/Basic/expenseRecords'));


			}
		}else {
			exit('付款失败');
		}
	}
	public function tenpay_recharge_notify(){
		exit('success');
				
	}
	
}

?>