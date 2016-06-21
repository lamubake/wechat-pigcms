<?php
class ThirdPayGroupon
{	
	
	public function index($orderid,$paytype,$third_id){
		$product_cart_model=M('product_cart');
		$out_trade_no=$orderid;
		$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
		if (!$this->wecha_id){
			$this->wecha_id=$order['wecha_id'];
		}
		$sepOrder=0;
		if (!$order){
			$order=$product_cart_model->where(array('id'=>$out_trade_no))->find();
			$sepOrder=1;
		}
		if($order){
			if($order['paid']!=1){exit('该订单还未支付');}
			if (!empty($order['sn']) && empty($order['sn_content'])) {
				$where['sendstutas'] = 0;
				$where['order_id'] = 0;
				$where['token'] = $order['token'];
				$where['pid'] = $order['productid'];
				$productSn = M('ProductSn');
				$models = $productSn->where($where)->limit($order['total'])->order('id ASC')->select();
				foreach ($models as $key => $model) {
					$model['order_id'] = $order['id'];
					$model['sendstutas'] = 1; 
					$model['sendtime'] = time();
					$model['wecha_id'] = $order['wecha_id'];
					$updateWhere['id'] = $model['id'];
					$updateWhere['sendstutas'] = 0;
					unset($model['id']);
					$productSn->where($updateWhere)->save($model);
					$models[$key] = $model;
					$flag = 1;
				}
				$order['sent'] = 1;
				$order['handled'] = 1;
				$order['sn_content'] = serialize($models);
				$product_cart_model->save($order);								
			} else {
				M('Product')->where(array('id'=>$order['productid']))->setDec('groupon_num', $order['total']);
			}
			
			/************************************************/
			$params['token'] = $order['token'];
			$params['wecha_id'] = $order['wecha_id'];			
			$messageUrl = C('site_url').'/index.php?g=User&m=Product&a=orderInfo&type=groupon&token='.$order['token'].'&id='.$order['id'];
			$params['content'] = '您的微信里有团购订单已经付款，请注意查看。订单号：'.$order['orderid'].'。';
			$params['site'] = array('content'=>$params['content'].'　　　　<a href="'.$messageUrl.'">点我击查看订单详情</a>', 'from'=>'团购消息');	
			$template_data = array(
				'href'      	=> C('site_url').'/index.php?g=Wap&m=Groupon&a=myOrders&token='.$order['token'].'&wecha_id='.$order['wecha_id'],
				//'wecha_id'	=> $wecha_id,
				'first'		=> '您好，你已经支付成功。',
				'keyword1' 	=> $order['price'],
				'keyword2'	=> '团购 - '.M('Product')->where(array('id'=>$order['productid']))->getField('name'),
				'keyword3'	=> getPayType($order['paytype']),
				'keyword4'	=> $order['orderid'],
				'keyword5'	=> date('Y-m-d H:i:s'),
				'remark'	=> '团购'					
			);
			$params['template'] = array('template_id'=>'OPENTM202183094', 'template_data'=>$template_data);
			$params['sms'] = array('mobile'=>M('Company')->where(array('token'=>$order['token'], 'isbranch'=>'0'))->getField('mp'));
			$return = MessageFactory::method($params, array('SmsMessage', 'TemplateMessage', 'SiteMessage'));
			$flagContent = $flag ? '已经发货，' : '正在准备发货，';
			$params['sms'] = array(
					//'content' => '尊敬的客户，您的订单（'.$order['orderid'].'）'.$flagContent.'请您耐心等待。我们不会以订单无效为由主动要求您提供银行卡和账号信息操作退款，谨防诈骗。',
					'content' => "亲爱的 {$order['truename']},您购买的商品 已经付款成功,金额为{$order['price']},订单号为{$order['orderid']},感谢您惠顾!",
					'mobile' => $order['tel'],
			);
			//发给用户的			
			MessageFactory::method($params, array('SmsMessage'));
			//Sms::sendSms($this->token,'您的微信里有团购订单已经付款');
			/************************************************/
			header('Location:/index.php?g=Wap&m=Groupon&a=myOrders&token='.$order['token'].'&wecha_id='.$order['wecha_id']);
			
		}else{
			exit('订单不存在：'.$out_trade_no);
		}
	}
}
?>

