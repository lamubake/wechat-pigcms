<?php
class ThirdPayHotels
{	
	
	public function index($orderid, $paytype = '', $third_id = ''){
		
		if ($order = M('Hotels_order')->where(array('orderid' => $orderid))->find()) {
			//TODO 发货的短信提醒
			if ($order['paid']) {
				$userInfo = D('Userinfo')->where(array('token' => $order['token'], 'wecha_id' => $order['wecha_id']))->find();
				$sort = M('Hotels_house_sort')->where(array('id' => $order['sid'], 'token' => $order['token']))->find();
				$days = (strtotime($order['enddate']) - strtotime($order['startdate'])) / 86400;
				$price = $userInfo['getcardtime'] > 0 ? ($sort['vprice'] ? $sort['vprice'] : $sort['price']) : $sort['price'];
				$company = M('Company')->where(array('id' => $order['cid'], 'token' => $order['token']))->find();
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'companytel' => $company['tel'], 'truename' => $order['name'], 'tel' => $order['tel'], 'address' => '', 'buytime' => $order['time'], 'orderid' => $order['orderid'], 'sendtime' => '', 'price' => $order['price'], 'total' => $order['nums'], 'list' => array(array('name' => $sort['name'], 'day' => $days, 'price' => $price, 'num' => $order['nums'], 'startdate' => $order['startdate'], 'enddate' => $order['enddate'])));
				$msg = ArrayToStr::array_to_str($msg, 1);
				$op->printit($order['token'], $order['cid'], 'Hotel', $msg, 1);
		
				Sms::sendSms($order['token'] . "_" . $order['cid'], "顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理");
				$model = new templateNews();
				$href = C('site_url').'/index.php?g=Wap&m=Hotels&a=my&token=' . $order['token'] . '&wecha_id=' . $order['wecha_id'] . '&cid=' . $order['cid'];
				$model->sendTempMsg('OPENTM202521011', array('href' => $href, 'wecha_id' => $order['wecha_id'], 'first' => '预订房间提醒', 'keyword1' => $orderid, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '预订房间成功，感谢您的光临，欢迎下次再次光临！'));
				//给商家发站内信
				$params = array();
				$params['site'] = array('token'=>$order['token'], 'from'=>'酒店宾馆消息','content'=>"顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理");
				MessageFactory::method($params, 'SiteMessage');
		
			}
			header('Location:/index.php?g=Wap&m=Hotels&a=my&token='.$order['token'].'&wecha_id='.$order['wecha_id'].'&cid='.$order['cid']);
// 			$this->redirect(U('Hotels/my', array('token'=>$this->token, 'wecha_id' => $this->wecha_id)));
		}else{
			exit('订单不存在');
		}
		
		
// 		$product_cart_model=M('product_cart');
// 		$out_trade_no=$orderid;
// 		$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
// 		if (!$this->wecha_id){
// 			$this->wecha_id=$order['wecha_id'];
// 		}
// 		$sepOrder=0;
// 		if (!$order){
// 			$order=$product_cart_model->where(array('id'=>$out_trade_no))->find();
// 			$sepOrder=1;
// 		}
// 		if($order){
// 			if($order['paid']!=1){exit('该订单还未支付');}
// 			/************************************************/
// 			Sms::sendSms($this->token,'您的微信里有团购订单已经付款');
// 			/************************************************/
// 			header('Location:/index.php?g=Wap&m=Groupon&a=myOrders&token='.$order['token'].'&wecha_id='.$order['wecha_id']);
			
// 		}else{
// 			exit('订单不存在：'.$out_trade_no);
// 		}
	}
}
?>

