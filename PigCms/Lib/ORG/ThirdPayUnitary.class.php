<?php
class ThirdPayUnitary
{	
	
	public function index($orderid,$paytype,$third_id){
		$this->m_unitary = M("unitary");
		$this->m_cart = M("unitary_cart");
		$this->m_order = M("unitary_order");
		$this->m_lucknum = M("unitary_lucknum");
		$this->m_user = M("unitary_user");
		$this->m_userinfo = M("userinfo");
		$where_order['orderid'] = $orderid;
		$order = $this->m_order->where($where_order)->find();
		$this->wecha_id = $order['wecha_id'];
		$this->token = $order['token'];
		if(!$order){
			$where_cart['order_id'] = $order['imicms_id'];
			$save_cart['state'] = 0;
			$save_cart['order_id'] = 0;
			$update_cart = $this->m_cart->where($where_cart)->save($save_cart);
			$del_order = $this->m_order->where($where_order)->delete();
			exit('订单不存在：'.$order['imicms_id']);
		}
	}
}
?>

