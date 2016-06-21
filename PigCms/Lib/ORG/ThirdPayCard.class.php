<?php

class ThirdPayCard
{

	public function index($orderid,$paytype='',$third_id=''){
		$where 	= array('orderid'=>$orderid);

		$order = M('Member_card_pay_record')->where($where)->find(); 
				
		if($order){
			$wecha_id 	= $order['wecha_id'];
			$token 		= $order['token'];

			if($order['paid'] == 1){
				M('Member_card_pay_record')->where($where)->setField('paytime',time());
				if($order['type'] == 1){
					M('Userinfo')->where("wecha_id = '$wecha_id' AND token = '$token'")->setInc('balance', self::_calculate($order['price'],$token,$order['cardid']));
				}else{
					$lastid = M('Member_card_use_record')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->order('id DESC')->getField('id');
					if($this->_get('type') == 'coupon'){
						M('Member_card_coupon')->where(array('token'=>$token,'id'=>(int)$this->_get('itemid')))->setInc('usetime',(int)$this->_get('usecount'));
						M('Member_card_use_record')->where(array('token'=>$token,'id'=>$lastid))->setField(array('usecount'=>(int)$this->_get('usecount'),'cat'=>6));
					}elseif($this->_get('type') == 'privelege'){
						M('Member_card_vip')->where(array('token'=>$token,'id'=>(int)$this->_get('itemid')))->setInc('usetime');
						M('Member_card_use_record')->where(array('token'=>$token,'id'=>$lastid))->setField('cat',4);
					}
					
				}
				/*
                if(empty($act)){
                	header('Location:'.U('Card/card',array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$order['cardid'])));
                }else{
                	header('Location:'.U('Card/'.$act,array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$order['cardid'])));
                }
                */
                $info       = M('Member_card_set')->where(array('token'=>$token,'cardid'=>$order['cardid']))->find();
                $cardinfo   = M('Member_card_create')->where(array('token'=>$token,'cardid'=>$order['cardid'],'wecha_id'=>$wecha_id))->find();
                
                $href = $this->siteUrl.'/index.php?'.http_build_query(array('g'=>'Wap', 'm'=>'Card', 'a'=>'card', 'token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$order['cardid']));
                //模板消息
				$model      = new templateNews();
				if ('CardPay' == $_GET['paytype']) { // 消费
					$dataKey    = 'OPENTM202183094';					
					$dataArr = array(
							'href'      	=> $href,
							'wecha_id'	=> $wecha_id,
							'first'		=> '您好，你已经支付成功。',
							'keyword1' 	=> $order['price'],
							'keyword2'	=> '会员卡消费',
							'keyword3'	=> '会员卡支付',
							'keyword4'	=>  $orderid,
							'keyword5'	=> date('Y-m-d H:i:s'),
							'remark'	=> '会员卡消费'
					);
				} else {
	                $dataKey    = 'TM00009';
	                $dataArr    = array(
	                	'href'      	=> $href,
	                    'wecha_id'  	=> $wecha_id,
	                    'first'         => '您好，你已经成功充值。',
	                    'accountType'   => $info['cardname'],
	                    'account'       => $cardinfo['number'],
	                    'amount'        => $order['price'],
	                    'result'        => '充值成功',
	                    'remark'        => '会员充值'
	                );
				}

                $model->sendTempMsg($dataKey,$dataArr);
                header('Location:'.U('Card/card',array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$order['cardid'])));
			}else{
				exit('支付未完成');
			}
		
		}else{
			exit('订单不存在');
		}

	}
	
	/**
	 * 计算充值赠送
	 * @param number $price
	 * @return number
	 */
	private function _calculate($price,$token,$cardid) {
		$where 	= "token='$token' AND cardid=$cardid AND is_open=1 AND ((min_price<=$price AND max_price>$price) OR (min_price<=$price AND max_price=0))";
		$models = M('MemberCardDonate')->where($where)->order('min_price DESC,max_price DESC')->find();
		if(empty($models)){
			return $price;
		}else{
			return $price+$models['donate_price'];
		}
	} 

}


?>