<?php
class user_del_cardEventReply {

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
		$where 	= array('card_id'=>$this->data['CardId'],'wecha_id'=>$this->data['FromUserName'],'cancel_code'=>$this->data['UserCardCode']);
		M('Member_card_coupon_record')->where($where)->delete();
	}
}
