<?php 
/**
* 发红包接口
**/
include_once("Hongbao_common.php");
class Hongbaoqiye extends Hongbao_common{
	public $nick_name;
	public $send_name;
	public $wishing;
	public $act_name;
	public $remark;
	public $key;
	public $mchid;
	public $wxappid;
	public $parameters;
	public $openid;
	public $money;
	public $url;
	public $curl_timeout;
	public $token;
	public $info;
	public $weixin;
	const TOTAL_NUM = 1;
	function __construct($config) {
		$this->token     = $config['token'];
		//获取微信配置信息
		$info = M('alipay_config')->where(array('token'=>$this->token,'open'=>1))->find();
		$this->info 	 = $info;
		$this->weixin	 = unserialize($this->info['info']);
		$this->key 		 = trim($this->weixin['weixin']['key']);
		$this->mchid 	 = trim($this->weixin['weixin']['mchid']);
		$this->mch_appid 	 = trim($this->weixin['weixin']['new_appid']);
		$this->openid 	 = $config['openid'];
		$this->money  	 = $config['money'];
		$this->total_num  	 = $config['total_num'];
		$this->amount  	 = $config['money'];
	
		$this->desc = (!empty($config['desc'])) ? $config['desc'] : '微点红包';
		 
		$this->url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
		$this->curl_timeout = 60;
	}
	public function send(){
		
		if(empty($this->token) || empty($this->openid) || empty($this->money)){
			return json_encode(array('status'=>'FAIL','msg'=>'Token,用户openid,金额不能为空'));
		}
		if(empty($this->info)){
			return json_encode(array('status'=>'FAIL','msg'=>'未获取到微信配置信息'));
		}
		if(empty($this->weixin['weixin']['key']) || empty($this->weixin['weixin']['mchid']) || empty($this->weixin['weixin']['new_appid'])){
			return json_encode(array('status'=>'FAIL','msg'=>'密钥或商户号或公众号不能为空'));
		}
		
		$this->setParameter('amount',$this->money);//97
		$this->setParameter('desc',$this->desc);	//100
		$this->setParameter('mch_appid',$this->mch_appid);//109
		$this->setParameter('mchid',$this->mchid);//109
	    $this->setParameter('nonce_str',$this->createNoncestr());//110
		$this->setParameter('check_name','NO_CHECK');//111
		$this->setParameter('openid',(string)$this->openid);//111
		$this->setParameter('partner_trade_no',(string)$this->mchid .date("YmdHis",time()).rand(1000,9999));//112
		$this->setParameter('spbill_create_ip',get_client_ip());//115
		$this->setParameter('sign',$this->getSign($this->parameters));
		//dump($this->parameters);exit;
		$xml = $this->createXml();
		
		$response_xml = $this->postXmlSSLCurl($xml,$this->url,$this->curl_timeout);
		$curl = json_decode($response_xml,true);
		if($curl['status'] == 'FAIL'){
			return $response_xml;
		}else{
			$respon_array = $this->xmlToarray($response_xml);
			if($respon_array['return_code'] == 'FAIL'){
				return json_encode(array('status'=>'FAIL','msg'=>$respon_array['return_msg']));
			}else{
				return json_encode(array('status'=>'SUCCESS','msg'=>'领取成功'));
			}
		}
	}
	//生成xml
	public function createXml(){
		try
		{
			//检测参数
			if($this->money > 200 || $this->money < 1){
				throw new Exception("单个红包金额介于[1.00元，200.00元]之间"."<br>");
				
			}elseif($this->parameters["mchid"] == null) {
				throw new Exception("发红包接口中，缺少必填参数mchid！"."<br>");
				
			}elseif($this->parameters["mch_appid"] == null){
				throw new Exception("发红包接口中，缺少必填参数mch_appid！"."<br>");
				
			}
		    return $this->arrayToXml($this->parameters);
		}catch (Exception $e){
			die($e->getMessage());
		}
	}
	//设置键值对
	public function setParameter($parameter, $parameterValue) {
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
		
	}
	
	//根据参数生成签名
	public function getSign($array){
		$parames = array();
		foreach((array)$array as $k => $v){
			//将非空参数组成集合
			if(!empty($v)){
				$parames[$k] = $v;
				
			}
		}
		//降序排序数组
		ksort($parames);
		//dump($parames);exit;
		$temp_s = '';
		foreach((array)$parames as $key => $val){
			$temp_s .= $key.'='.$val.'&';
		}
		$reqPar;
		if (strlen($temp_s) > 0) {
			$reqPar = substr($temp_s, 0, strlen($temp_s)-1);
		}
		
		//参数集合组合商户密钥
		$string = $reqPar.'&key='.$this->key ;
		
		//MD5加密后转换为大写
		$signValue = strtoupper(md5($string));
		
		return $signValue;
	}
	
	/**
	 * 	作用：使用证书，以post方式提交xml到对应的接口url
	 */
	function postXmlSSLCurl($xml,$url,$second=30)
	{
		
		$cert = M('wxcert')->where(array('token'=>$this->token))->find();
		if(empty($cert['apiclient_cert']) || empty($cert['apiclient_key']) || empty($cert['rootca'])){
			return json_encode(array('status'=>'FAIL','msg'=>'商户未上传证书文件'));
		}else{
			$apiclient_cert = str_replace(array('http://',$_SERVER['HTTP_HOST']),'',$cert['apiclient_cert']);
			$apiclient_key = str_replace(array('http://',$_SERVER['HTTP_HOST']),'',$cert['apiclient_key']);
			$rootca =  str_replace(array('http://',$_SERVER['HTTP_HOST']),'',$cert['rootca']);
		}
		/* echo getcwd().$apiclient_cert;
		echo '<br />';
		echo getcwd().$apiclient_key;
		echo '<br />';
		echo getcwd().$rootca;
		exit; */
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		curl_setopt($ch,CURLOPT_SSLCERT,getcwd().$apiclient_cert);
 		curl_setopt($ch,CURLOPT_SSLKEY,getcwd().$apiclient_key);
 		curl_setopt($ch,CURLOPT_CAINFO,getcwd().$rootca);
		//post提交方式
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		

		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			curl_close($ch);
			return json_encode(array('status'=>'FAIL','msg'=>$error));
		}
	}
}