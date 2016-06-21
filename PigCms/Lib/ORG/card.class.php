<?php
class card{
	public $name;
	public $serverUrl;
	public $token;
	public function __construct($token){
		
		$this->token = $token;
		$this->serverUrl = 'http://www.weihubao.com/';
		//$this->serverUrl='http://www.youxi.com/';
		$this->key=trim(C('server_key'));
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
		$this->token=$token;
	}
	public function cardCats(){
		$url=$this->serverUrl.'index.php?m=Index&c=api&a=cardCats';
		$rt=$this->api_notice_increment($url);
		return json_decode($rt,1);
	}
	public function cardList($catid){
		$url=$this->serverUrl.'index.php?m=Index&c=api&a=cardList&catid='.$catid;
		$rt=$this->api_notice_increment($url);
		return json_decode($rt,1);
	}
	public function getCard($id){
		$url=$this->serverUrl.'index.php?m=Index&c=api&a=card&id='.$id;
		$rt=$this->api_notice_increment($url);
		return json_decode($rt,1);
	}
	public function cardSelfs($id){
		$url=$this->serverUrl.'index.php?m=Index&c=api&a=cardSelfs&cardid='.$id;
		$rt=$this->api_notice_increment($url);
		return json_decode($rt,1);
	}
	public function cardSelfSet($cardid,$userCardid,$data,$delete=0){
		$unique = base64_encode($_SERVER['SERVER_NAME'].'_pigcms_'.$this->token);
		$url=$this->serverUrl.'index.php?m=Index&c=api&a=cardSelfSet&cardid='.$cardid.'&usercardid='.$userCardid.'&unique='.$unique.'&delete='.$delete;
		$rt=$this->api_notice_increment($url,$data);
		return json_decode($rt,1);
	}
	public function config($token,$wxname,$wxid){
		$data=array(
			'username'=>$this->topdomain.'_'.$token,
			'wxname'=>$wxname,
			'domain'=>$_SERVER['HTTP_HOST'],
			'wxid'=>$wxid,
		);
		$url = $this->serverUrl.'index.php?m=Index&c=api&a=newUser';
		$rt = $this->api_notice_increment($url,$data);
		return json_decode($rt,1);
	}
	function api_notice_increment($url,$data,$method='POST'){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
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
			return Http::fsockopenDownload($url);
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			//$js=json_decode($tmpInfo,1);
			//return $js;
			return $tmpInfo;
		}
	}
}
