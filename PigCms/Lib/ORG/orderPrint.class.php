<?php
class orderPrint {

	public $serverUrl;
	public $key;
	public $topdomain;
	public $token;
	public function __construct($token){
		$this->serverUrl='http://my.feyin.net/';
		$this->sendMsg='api/sendMsg';
		//$this->key=trim(C('server_key'));
		//$this->topdomain=trim(C('server_topdomain'));
		//if (!$this->topdomain){
		//	$this->topdomain=$this->getTopDomain();
		//}
		$this->token=$token;
	}
	public function printit($token, $companyid=0, $ordertype='', $content = '', $paid=0, $qr, $number){
		if (C("emergent_mode")) {
			return "404";
		}
		$companyid=intval($companyid);
		$printers=M('Orderprinter')->where(array('token'=>$token))->select();
		
		/*
		if ($companyid){
			$printers=M('Orderprinter')->where(array('token'=>$token,'companyid'=>$companyid))->select();
		}else {
			$printers=M('Orderprinter')->where(array('token'=>$token))->select();
		}
		*/
		F('1',$printers);
		$usePrinters=array();
		if ($printers){
			foreach ($printers as $p){
				$ms=explode(',',$p['modules']);
				if (in_array($ordertype, $ms) && (!$companyid || ($p["companyid"] == $companyid) || !$p["companyid"])) {
					if ($number) {
						if ($p["number"] == $number) {
							array_push($usePrinters, $p);
						}
					}elseif(empty($p["number"])) {
						array_push($usePrinters, $p);
					}
				}
				/*if (in_array($ordertype,$ms)&&(!$companyid||$p['companyid']==$companyid||!$p['companyid'])){
					array_push($usePrinters,$p);
				}*/
			}
		}
		F('2',$usePrinters);
		if ($usePrinters){
			foreach ($usePrinters as $p){
				if (!$p['paid']||($p['paid']&&$paid)){
					for($i=0;$i<$p['count'];$i++){
						$content = str_replace("*******************************", "************************", $content);
						$content = str_replace("※※※※※※※※※※※※※※※※", "※※※※※※※※※※※※", $content);
					 if ($p['printtype'] == '0'){ //为0时用飞印打印机	
					    $msgNo=time()+1+$i;
					    $reqTime=number_format(1000*time(), 0, '', '');
					    $data=array(
						  'memberCode'=>$p['mc'],
						  'msgDetail'=>$content,
						  'deviceNo'=>$p['mcode'],
					      'msgNo'=>$msgNo,
						  'reqTime'=>$reqTime,
						  'securityCode'=>md5($p['mc'].$content.$p['mcode'].$msgNo.$reqTime.$p['mkey']),
						  'mode'=>2
					      );
					   $url=$this->serverUrl.$this->sendMsg;
					   $rt=$this->api_notice_increment($url,$data);
					  // echo $data['msgDetail'];
				       //dump($rt);
					 //var_dump($rtt);
					 //exit;
				      F('3',$rt);
					  usleep(10000);
				    }else{ //用易联云打印机
					  $msg          = $content; //打印内容
                      $apiKey       = $p['apikey'];//apiKey
                      $mKey         = $p['mkey'];//秘钥
                      $partner      = $p['mc'];//用户id
                      $machine_code = $p['mcode'];//打印机终端号
                       $ti = time()+1+$i;
                       $params = array(
                          'partner'=>$partner,
                          'machine_code'=>$machine_code,
                          'time'=>$ti
                           );
                       $sign=$this->generateSign($params,$apiKey,$mKey);
                       $params['sign'] = $sign;
                       $params['content'] = $msg;
                       $url = 'http://open.10ss.net:8888';//接口端点
                       $pb = '';
                       foreach ($params as $k => $v) {
                               $pb .= $k.'='.$v.'&';
                       }
                       $data = rtrim($pb, '&');
	                   $rt=$this->liansuo_post($url,$data);
                      F('3',$rt);
					  usleep(10000);
  
				     }//打印机选择结束
				   }//数量结束
			   }
			}
		}
	}
	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
	
			return $errorno;
		
		}else{
			return $tmpInfo;
		}
	}
	
	//易联云使用
	function liansuo_post($url,$data){ // 模拟提交数据函数      
    $curl = curl_init(); // 启动一个CURL会话      
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在      
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交     
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转      
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer      
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求      
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包      
    curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息      
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循     
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容      
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回 
           
    $tmpInfo = curl_exec($curl); // 执行操作  
	//echo '<br>提示信息'.$tmpInfo;
	//exit;    
    if (curl_errno($curl)) {      
      // echo 'Errno'.curl_error($curl);      
    }      
    curl_close($curl); // 关键CURL会话      
    return $tmpInfo; // 返回数据      
    }    
   
    //易联云使用
   function generateSign($params, $apiKey, $msign)
    {
    //所有请求参数按照字母先后顺序排
    ksort($params);
    //定义字符串开始所包括的字符串
    $stringToBeSigned = $apiKey;
    //把所有参数名和参数值串在一起
    foreach ($params as $k => $v)
      {
        $stringToBeSigned .= urldecode($k.$v);
      }
    unset($k, $v);
    //定义字符串结尾所包括的字符串
    $stringToBeSigned .= $msign;
    //使用MD5进行加密，再转化成大写
    return strtoupper(md5($stringToBeSigned));
   }
	
}
