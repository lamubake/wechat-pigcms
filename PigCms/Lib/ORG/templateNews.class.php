<?php
class templateNews{
	public $thisWxUser;
	public $myToken;
	public function __construct(){
		$this->myToken = session('token') ? session('token') : session('wap_token');
		$this->myToken = empty($this->myToken) ? $_GET['token'] : $this->myToken;
		$where=array('token'=>$this->myToken);
		$this->thisWxUser=M('Wxuser')->field('appid,appsecret')->where($where)->find();

		
	}

	public function sendTempMsg($tempKey,$dataArr){
		

	/* //example
		$tempKey = 'TM00130';
		$dataArr = array('href' => 'http://www.baidu.com' , 'wecha_id' => 'oLA6VjgLrB3qPspOBRMYZZJpVkGQ' , 'first' => '您好，您已成功预约看房。' , 'apartmentName' => '丽景华庭' , 'roomNumber' => 'A栋534' , 'address' => '广州市微信路88号', 'time' => '2013年10月30日 15:32', 'remark' => '请您准时到达看房。');
	*/
	$open = M('Tempmsg')->where(array('token'=>$this->myToken,'tempkey'=>"$tempKey"))->getField('status');
	if($open){
		//S($this->thisWxUser['appid'],NULL);
	// 获取配置信息
		$dbinfo = M('Tempmsg')->where(array('token' => $this->myToken,'tempkey'=>"$tempKey"))->find();
		$apiOauth 	= new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token('',$this->thisWxUser);
	// 准备发送请求的数据 
		$requestUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		
		
		//$data = $this->getData($tempKey,$dataArr,$dbinfo['textcolor']);
		
		preg_match_all('{{(\w+)\.DATA}}', $dbinfo['content'], $preg);
		
		$content = $preg[1];
		$jsonData = '';
		foreach($dataArr as $k => $v){
			if(in_array($k, $content)){
				$jsonData .= '"'.$k.'":{"value":"'.$v.'","color":"'.$dbinfo['textcolor'].'"},';
			}
		}
		$jsonData = rtrim($jsonData,',');
		$data = "{".$jsonData."}";
		$sendData = '{"touser":"'.$dataArr["wecha_id"].'","template_id":"'.$dbinfo["tempid"].'","url":"'.$dataArr["href"].'","topcolor":"'.$dbinfo["topcolor"].'","data":'.$data.'}';
		return $this->postCurl($requestUrl,$sendData);
	}
}
// Get Data.data
	public function getData($key,$dataArr,$color){
		$tempsArr = $this->templates();
		$data = $tempsArr["$key"]['vars'];
		$data = array_flip($data);
        $jsonData = '';
		foreach($dataArr as $k => $v){
			if(in_array($k,array_flip($data))){
				$jsonData .= '"'.$k.'":{"value":"'.$v.'","color":"'.$color.'"},';
			}
		}
		$jsonData = rtrim($jsonData,',');
		return "{".$jsonData."}"; 
	}

	public function templates(){
		return array(
		'OPENTM203605740' =>
			array(
				'name'=>'预约看房提醒',
				'vars'=>array('first','keyword1','keyword2','keyword3','keyword4','remark'),
				'industry'=>'IT科技_互联网|电子商务',
				'content'=>	
'{{first.DATA}}
看房时间：{{keyword1.DATA}}
房屋地址：{{keyword2.DATA}}
房屋信息：{{keyword3.DATA}}
客服电话：{{keyword4.DATA}}
{{remark.DATA}}'
				),
		'TM00695' =>
			array(
				'name'=>'中奖结果通知',
				'vars'=>array('title','headinfo','program','result','remark'),
				'industry'=>'IT科技_互联网|电子商务',
				'content'=>'
{{title.DATA}}	
{{headinfo.DATA}}
彩票名称：{{program.DATA}}
开奖结果：{{result.DATA}}
{{remark.DATA}}'
			),
		'TM00499' =>
			array(
				'name'=>'服务完成通知',
				'vars'=>array('first','Content1','Good','contentType','price','menu','remark'),
				'industry'=>'IT科技_互联网|电子商务',
				'content'=>'
{{first.DATA}}
{{Content1.DATA}}
商品名称：{{Good.DATA}}
服务措施：{{contentType.DATA}}
收费金额：{{price.DATA}}
收费标准：{{menu.DATA}}
{{remark.DATA}}'
			),
            'TM00459' => array(
                'name'    => '预订成功',
                'vars'    => array('first', 'keynote1', 'keynote2', 'keynote3', 'keynote4', 'remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
商户：{{keynote1.DATA}}
时间：{{keynote2.DATA}}
人数：{{keynote3.DATA}}
类型：{{keynote4.DATA}}
{{remark.DATA}}   '
            ),
            'OPENTM202183094' => array(
                'name'    => '商品支付成功通知',
                'vars'    => array('first', 'keyword1', 'keyword2','keyword3','keyword4','keyword5', 'remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
付款金额：{{keyword1.DATA}}
商品详情：{{keyword2.DATA}}
支付方式：{{keyword3.DATA}}
交易单号：{{keyword4.DATA}}
交易时间：{{keyword5.DATA}}
{{remark.DATA}}'
            ),
            'TM00009' => array(
                'name'    => '会员充值通知',
                'vars'    => array('first', 'accountType', 'account', 'amount', 'result', 'remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
{{accountType.DATA}}:{{account.DATA}}
充值金额：{{amount.DATA}}
充值状态：{{result.DATA}}
{{remark.DATA}}'
            ),
            'TM00017' => array(
                'name'    => '订单状态更新',
                'vars'    => array('first', 'OrderSn', 'OrderStatus','remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
订单编号: {{OrderSn.DATA}}
订单状态: {{OrderStatus.DATA}}
{{remark.DATA}}'
            ),
            'OPENTM202521011' => array(
                'name'    => '订单完成通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
订单号：{{keyword1.DATA}}
完成时间：{{keyword2.DATA}}
{{remark.DATA}}'
            ),
            'TM00184' => array(
                'name'    => '订单未支付通知',
                'vars'    => array('first', 'ordertape', 'ordeID','remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
下单时间：{{ordertape.DATA}}
订单号：{{ordeID.DATA}}
{{remark.DATA}}'
            ),
			'OPENTM200681790' => array(
                'name'    => '领取红包通知',
                'vars'    => array('first', 'keyword1', 'keyword2','remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
成功领取：{{keyword1.DATA}}
红包金额：{{keyword2.DATA}}
{{remark.DATA}}'
            ),
			'OPENTM200565259' => array(
                'name'    => '订单发货提醒',
                'vars'    => array('first', 'keyword1', 'keyword2','keyword3','remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
订单编号：{{keyword1.DATA}}
物流公司：{{keyword2.DATA}}
物流单号：{{keyword3.DATA}}
{{remark.DATA}}'
			),
			'OPENTM200869995' => array(
                'name'    => '排队提醒通知',
                'vars'    => array('first', 'keyword1', 'keyword2','keyword3','keyword4','remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
队列号：{{keyword1.DATA}}
取号时间：{{keyword2.DATA}}
排队时长：{{keyword3.DATA}}
等待人数：{{keyword4.DATA}}
{{remark.DATA}}'
			),
			'OPENTM201812627' => array(
                'name'    => '佣金提醒',
                'vars'    => array('first', 'keyword1', 'keyword2','remark'),
				'industry'=>'IT科技_互联网|电子商务',
                'content' => '
{{first.DATA}}
佣金金额：{{keyword1.DATA}}
时间：{{keyword2.DATA}}
{{remark.DATA}}'
			),
		);
	}

// Post Request
	function postCurl($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				
				return array('rt'=>true,'errorno'=>0);
			}else {
				//exit('模板消息发送失败。错误代码'.$js['errcode'].',错误信息：'.$js['errmsg']);
				return array('rt'=>false,'errorno'=>$js['errcode'],'errmsg'=>$js['errmsg']);

			}
		}
	}

// Get Access_token Request
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
}
