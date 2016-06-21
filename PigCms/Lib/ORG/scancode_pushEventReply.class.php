<?php
class scancode_pushEventReply {

	public $token;
	public $wecha_id;
	public $thisWxUser = array();
	public $data;

	public function __construct($token,$wecha_id,$data,$siteurl){
		
		$this->token      = $token;
		$this->wecha_id   = $wecha_id;
		$this->thisWxUser = M('Wxuser')->field('appid,appsecret')->where(array('token'=>$token))->find();
		$this->data=$data;
	}


	public function index(){
		return array('二维码扫描结果：'.$this->data['ScanResult'],'text');
	}
}
