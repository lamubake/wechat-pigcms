<?php
class card_pass_checkEventReply {

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
		$where 	= array('token'=>$this->token,'card_id'=>$this->data['CardId']);
		M('Member_card_coupon')->where($where)->save(array('is_check'=>1));
	}
}
