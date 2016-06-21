<?php 
class ThirdPaySeckill{
	public function index($order_id , $paytype='' , $third_id=''){
		$wecha_id = '';
		$token = '';
		$order = M('seckill_book')->where(array("orderid"=>$order_id))->find();
		if(!empty($order)){
			$wecha_id = $order['wecha_id'];
			$token = $order['token'];
			if($order['paid']){
				//给顾客发模板消息
				$model = new templateNews();
				$siteurl=$_SERVER['HTTP_HOST'];
				$siteurl=strtolower($siteurl);
				if(strpos($siteurl,"http:")===false && strpos($siteurl,"https:")===false) $siteurl='http://'.$siteurl;
				$siteurl=rtrim($siteurl,'/');
                $model->sendTempMsg('OPENTM202521011', array('href' =>$siteurl."/index.php?g=Wap&m=Seckill&a=my_cart&token={$token}&wecha_id={$wecha_id}&id={$order['book_aid']}", 'wecha_id' => $wecha_id, 'first' => '秒杀交易提醒', 'keyword1' => $order_id, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '订单完成！'));
				//给商家发站内信
				$params = array();
				$params['site'] = array('token'=>$token, 'from'=>'微秒杀消息','content'=>"顾客".$order['true_name']."刚刚对订单号：".$order_id."的订单进行了支付，请您注意查看并处理");
				MessageFactory::method($params, 'SiteMessage');
				//给商户发短信
				//Sms::sendSms($token, "顾客{$order['true_name']}刚刚对订单号：{$order_id}的订单进行了支付，请您注意查看并处理");
				$shop_info = M('seckill_base_shop')->where(array('shop_id'=>$order['book_sid']))->find();
				if($shop_info['shop_num'] >= 1){
					M('seckill_base_shop')->where(array('shop_id'=>$order['book_sid']))->save(array('shop_num'=>array('exp','shop_num-1')));
				}
				header('Location:'.U('Seckill/my_cart', array('token' => $token, 'wecha_id' => $wecha_id,'id'=>$order['book_aid'])));
			}else{
				header('Location:'.U('Seckill/my_cart', array('token' => $token, 'wecha_id' => $wecha_id,'id'=>$order['book_aid'])));
			}
		}else{
			exit('订单不存在：'.$order_id);
		}
	}
}