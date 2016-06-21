<?php
class user_get_cardEventReply {

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
		$card 	= array(
			'token' 	=> $this->token,
			'wecha_id' 	=> $this->data['FromUserName'],
			'card_id' 	=> $this->data['CardId'],	
			);
		
		$info 	= M('Member_card_coupon')->where(array('token'=>$this->token,'card_id'=>$this->data['CardId']))->find();
		if($info['type'] == 1){
			$card['coupon_type']	= '1';
		}else if($info['type'] == 2){
			$card['coupon_type'] 	= '3';
		}else{
			$card['coupon_type'] 	= '2';
		}
		$card['add_time'] 		= $this->data['CreateTime'];
		$card['coupon_id'] 		= $info['id'];
		$card['cardid'] 		= $info['cardid'];
		$card['token'] 			= $this->token;
		$card['wecha_id'] 		= $this->data['FromUserName'];
		$card['cancel_code'] 	= $this->data['UserCardCode'];
		$card['company_id'] 	= $info['company_id'];
		
		if($info['type'] == 1){
			$card['coupon_attr']    = serialize(array('coupon_name'=>$info['title']));
        }else if($info['type'] == 2){
			$card['coupon_attr']    = serialize(array('coupon_name'=>$info['title'],'gift_name'=>$info['gift_name'],'integral'=>$info['integral']));
        }else{
			$card['coupon_attr']    = serialize(array('coupon_name'=>$info['title'],'least_cost'=>$info['least_cost'],'reduce_cost'=>$info['reduce_cost']));
		}
		
		if(M('Member_card_coupon_record')->add($card)){
			if($info['type'] == 2){
				$arr['itemid']      = $info['id'];
                $arr['wecha_id']    = $this->data['FromUserName'];
                $arr['time']        = time();
                $arr['token']       = $this->token;
                $arr['cat']         = 2;
                $arr['staffid']     = 0;
                $arr['usecount']    = 1;
                $arr['score']       = $info['integral']*-1;
                $arr['notes']       = '积分兑换礼品券';
                $arr['company_id']  = $info['company_id'];
                $arr['cardid']      = $info['cardid'];
            	//更新记录
            	if(M('Member_card_use_record')->add($arr)){
					M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))->setDec('total_score',$info['integral']);
				}
			}
			M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$info['id']))->setDec('total',1);
		}
	}
}
