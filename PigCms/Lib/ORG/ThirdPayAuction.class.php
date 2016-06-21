<?php
class ThirdPayAuction{	
	public function index($orderid,$paytype,$third_id){
		$order = M('auction_order')->where(array('orderid'=>$orderid))->find();
		if($order){
			if($order['paid']!=1){
				exit('该订单还未支付');
			}
			M('auction_order')->where(array('orderid'=>$orderid))->save(array('thirdpay'=>1));
			$auction = M('auction')->where(array('token'=>$order['token'],'id'=>$order['auctionid']))->find();
			$params['site'] = array('token'=>$order['token'],'from'=>'微拍卖消息','content'=>'您的微拍卖有新的拍品被买下，请注意查看。拍品名称：'.$auction['name'].'，拍品ID：'.$auction['id']);
			$error = MessageFactory::method($params,'SiteMessage');
		}else{
			exit('订单不存在：'.$orderid);
		}
	}
}