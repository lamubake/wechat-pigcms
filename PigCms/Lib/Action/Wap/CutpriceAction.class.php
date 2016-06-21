<?php
class CutpriceAction extends WapAction{
	public $m_cutprice;
	public $m_userinfo;
	public $m_order;
	public function _initialize(){
		parent::_initialize();
		
		
		//$this->wecha_id = "o6Jlyt-8jTEZKtpmN-5-DpmFB3cA";
		
		//加载数据库
		$this->m_cutprice = M("cutprice");
		$this->m_userinfo = M("userinfo");
		$this->m_order = M("cutprice_order");
		
		$this->inventory($this->token);
		
		if(in_array(ACTION_NAME,array("goods"))){
			$where['token'] = $this->token;
			$where['imicms_id'] = $_GET['id']*1;
			$cutprice = $this->m_cutprice->where($where)->find();
			if($cutprice['state_subscribe'] == '1' && !$this->isSubscribe()){
				$this->memberNotice('',1);
			}elseif($cutprice['state_userinfo'] == '1' && $this->fans['tel'] == ''){
				$this->memberNotice();
			}
		}
	}
	//拍卖首页
	public function index(){
		$where['token'] = $this->token;
		$cutprice_list = $this->m_cutprice->where($where)->order("addtime desc")->select();
		$this->assign("cutprice_list",$cutprice_list);
		$this->display();
	}
	//拍卖商品
	public function goods(){
		$where['token'] = $this->token;
		$where['imicms_id'] = intval($_GET['id']);
		$cutprice = $this->m_cutprice->where($where)->find();
		if($cutprice == ''){
			$this->error('此商品不存在');
			exit;
		}
		$cutprice['logourl1'] = $this->getLink($cutprice['logourl1']);
		if($cutprice['logourl2'] != ''){
			$cutprice['logourl2'] = $this->getLink($cutprice['logourl2']);
		}
		if($cutprice['logourl3'] != ''){
			$cutprice['logourl3'] = $this->getLink($cutprice['logourl3']);
		}
		$cha = time() - $cutprice['starttime'];
		if($cha < 0){
			$state = 'wait';
			$cutprice['nowprice'] = $cutprice['startprice'];
		}elseif($cha >= 0){
			$chaprice = (floor($cha/60/$cutprice['cuttime']))*$cutprice['cutprice'];
			if($cutprice['inventory'] > 0 && ($cutprice['startprice'] - $chaprice) > $cutprice['stopprice']){
				$state = 'start';
				$cutprice['nowprice'] = $cutprice['startprice'] - $chaprice;
				$cutprice['min'] = $cutprice['cuttime'] - 1 - ((floor($cha/60))%$cutprice['cuttime']);
				$cutprice['sec'] = 59 - ($cha%60);
			}else{
				$state = 'stop';
			}
		}
		$this->assign('state',$state);
		$this->assign('cutprice',$cutprice);
		$this->display();
	}
	//完善地址
	public function buyinfo(){
		$where['token'] = $this->token;
		$where['imicms_id'] = $_GET['id']*1;
		$cutprice = $this->m_cutprice->where($where)->find();
		if($cutprice['onebuynum']*1 > 0){
			if($_GET['num']*1 > $cutprice['onebuynum']*1){
				$this->error('超出每人购买限制',U("Wap/Cutprice/goods",array('token'=>$this->token,'id'=>$_GET['id'])));
			}
		}
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('userinfo',$userinfo);
		$this->display();
	}
	//执行购买
	public function dobuy(){
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$save_userinfo['wechaname'] = $_GET['name'];
		$save_userinfo['tel'] = $_GET['phone'];
		$save_userinfo['address'] = $_GET['address'];
		$update_userinfo = $this->m_userinfo->where($where_userinfo)->save($save_userinfo);
		$where['token'] = $this->token;
		$where['imicms_id'] = $_GET['id']*1;
		$cutprice = $this->m_cutprice->where($where)->find();
		if($cutprice['inventory'] < 1){
			$this->error("很遗憾，被别人拍完了。",U("Wap/Cutprice/goods",array("token"=>$this->token,"id"=>$_GET['id'])));
			exit;
		}
		if($_GET['num']*1 > $cutprice['inventory']){
			$_GET['num'] = $cutprice['inventory'];
		}
		$where_order_is['token'] = $this->token;
		$where_order_is['cid'] = $_GET['id']*1;
		$where_order_is['nowprice'] = $_GET['nowprice'];
		$order_is = $this->m_order->where($where_order_is)->find();
		if($order_is != ''){
			$this->error("很遗憾，这个价格被别人抢先了。",U("Wap/Cutprice/goods",array("token"=>$this->token,"id"=>$_GET['id'])));
			exit;
		}else{
			$where_order_is2['token'] = $this->token;
			$where_order_is2['cid'] = $_GET['id']*1;
			$where_order_is2['wecha_id'] = $this->wecha_id;
			$order_is2 = $this->m_order->where($where_order_is2)->find();
			if($order_is2 != ''){
				$this->error("您已经成功拍过价格为{$order_is2['nowprice']}元的此商品。",U("Wap/Cutprice/index",array("token"=>$this->token)));
				exit;
			}else{
				$add_order['token'] = $this->token;
				$add_order['cid'] = $_GET['id'];
				$add_order['num'] = $_GET['num'];
				$add_order['nowprice'] = $_GET['nowprice'];
				$add_order['price'] = $_GET['nowprice']*$_GET['num'];
				$add_order['tel'] = $_GET['phone'];
				$add_order['address'] = $_GET['address'];
				$add_order['endtime'] = 1800+time();
				$add_order['addtime'] = time();
				$add_order['wecha_id'] = $this->wecha_id;
				$id_order = $this->m_order->add($add_order);
				$randnum = rand(1000,9999);
				$orderid = $id_order."CUTPRICE".time().$randnum;
				$update_order = $this->m_order->where(array("token"=>$this->token,"imicms_id"=>$id_order))->save(array("orderid"=>$orderid));
				$save['inventory'] = $cutprice['inventory'] - $_GET['num'];
				$update = $this->m_cutprice->where($where)->save($save);
				$this->redirect("Alipay/pay",array("token"=>$this->token,"price"=>$add_order['price'],"wecha_id"=>$this->wecha_id,"from"=>"Cutprice","orderid"=>$orderid,'single_orderid'=>$orderid,'notOffline'=>1));
			}
		}
	}
	//支付成功
	public function payReturn(){
		$params['site'] = array('token'=>$this->token,'from'=>'降价拍消息','content'=>'您的降价拍有新的拍品被买下，请注意查看。订单号：'.$_GET['orderid']);
		$error = MessageFactory::method($params,'SiteMessage');
		$this->success("支付成功",U("Cutprice/my",array('token'=>$this->token)));
	}
	//个人中心
	public function my(){
		$where_order_nobuy['token'] = $this->token;
		$where_order_nobuy['wecha_id'] = $this->wecha_id;
		$where_order_nobuy['paid'] = 0;
		$nobuy_count = $this->m_order->where($where_order_nobuy)->count();
		$this->assign("nobuy_count",$nobuy_count);
		
		$where_order_wfahuo['token'] = $this->token;
		$where_order_wfahuo['wecha_id'] = $this->wecha_id;
		$where_order_wfahuo['paid'] = 1;
		$where_order_wfahuo['fahuo'] = 0;
		$wfahuo_count = $this->m_order->where($where_order_wfahuo)->count();
		$this->assign("wfahuo_count",$wfahuo_count);
		
		$where_order_yfahuo['token'] = $this->token;
		$where_order_yfahuo['wecha_id'] = $this->wecha_id;
		$where_order_yfahuo['paid'] = 1;
		$where_order_yfahuo['fahuo'] = 1;
		$yfahuo_count = $this->m_order->where($where_order_yfahuo)->count();
		$this->assign("yfahuo_count",$yfahuo_count);
		
		$where_order_over['token'] = $this->token;
		$where_order_over['wecha_id'] = $this->wecha_id;
		$where_order_over['paid'] = 1;
		$where_order_over['fahuo'] = 2;
		$over_count = $this->m_order->where($where_order_over)->count();
		$this->assign("over_count",$over_count);
		
		$this->display();
	}
	//我的订单
	public function myorder(){
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		switch($_GET['type']){
			case 'nobuy':
				$where_order['paid'] = 0;
			break;
			case 'wfahuo':
				$where_order['paid'] = 1;
				$where_order['fahuo'] = 0;
			break;
			case 'yfahuo':
				$where_order['paid'] = 1;
				$where_order['fahuo'] = 1;
			break;
			case 'over':
				$where_order['paid'] = 1;
				$where_order['fahuo'] = 2;
			break;
		}
		$order_list = $this->m_order->where($where_order)->order("addtime desc")->select();
		foreach($order_list as $k=>$v){
			$where['token'] = $this->token;
			$where['imicms_id'] = $v['cid'];
			$cutprice = $this->m_cutprice->where($where)->find();
			$order_list[$k]['goods_name'] = $cutprice['name'];
			$order_list[$k]['goods_img'] = $cutprice['logoimg1'];
			if($v['paid'] == 0){
				$order_list[$k]['type'] = 'nobuy';
			}elseif($v['fahuo'] == 0){
				$order_list[$k]['type'] = 'wfahuo';
			}elseif($v['fahuo'] == 1){
				$order_list[$k]['type'] = 'yfahuo';
			}elseif($v['fahuo'] == 2){
				$order_list[$k]['type'] = 'over';
			}
		}
		$this->assign("order_list",$order_list);
		$this->display();
	}
	//删除订单
	public function delorder(){
		$where_order['token'] = $this->token;
		$where_order['imicms_id'] = $_GET['orderid']*1;
		$order = $this->m_order->where($where_order)->find();
		$where['token'] = $this->token;
		$where['imicms_id'] = $_GET['id']*1;
		$cutprice = $this->m_cutprice->where($where)->find();
		$save['inventory'] = $cutprice['inventory'] + $order['num'];
		$update = $this->m_cutprice->where($where)->save($save);
		$delorder = $this->m_order->where($where_order)->delete();
		$this->success("删除成功",U("Wap/Cutprice/myorder",array("token"=>$this->token)));
	}
	//未支付订单支付
	public function dopay(){
		$where_order['token'] = $this->token;
		$where_order['imicms_id'] = $_GET['orderid']*1;
		$order = $this->m_order->where($where_order)->find();
		if($order == ''){
			$this->error("此订单已失效",U("Wap/Cutprice/my",array("token"=>$token)));
		}else{
			$this->redirect("Alipay/pay",array("token"=>$this->token,"price"=>$order['price'],"wecha_id"=>$this->wecha_id,"from"=>"Cutprice","orderid"=>$order['orderid'],'single_orderid'=>$order['orderid'],'notOffline'=>1));
		}
	}
	//确认收货
	public function shouhuo(){
		$where_order['token'] = $this->token;
		$where_order['imicms_id'] = $_GET['orderid']*1;
		$save_order['fahuo'] = 2;
		$update = $this->m_order->where($where_order)->save($save_order);
		$this->success("确认收货成功",U("Wap/Cutprice/myorder",array("token"=>$this->token)));
	}
	//ajax
	public function ajax(){
		$this->inventory($_POST['token']);
		switch($_POST['type']){
			case 'inventory':
				$where['token'] = $_POST['token'];
				$where['imicms_id'] = $_POST['id'];
				$cutprice = $this->m_cutprice->where($where)->find();
				$data['inventory'] = $cutprice['inventory'];
				$this->ajaxReturn($data,'JSON');
			break;
			case 'buyers':
				$where_order['token'] = $_POST['token'];
				$where_order['cid'] = $_POST['id'];
				$order_list = $this->m_order->where($where_order)->order("addtime desc")->select();
				$data['buyers'] = '';
				foreach($order_list as $vo){
					$where_userinfo['token'] = $_POST['token'];
					$where_userinfo['wecha_id'] = $vo['wecha_id'];
					$userinfo = $this->m_userinfo->where($where_userinfo)->find();
					$data['buyers'].='<div class=\'buyer\'><div class=\'buyerinfo\'>'.$userinfo['wechaname'].'</div><div class=\'buyerinfo\'>'.substr($userinfo['tel'],0,3)."****".substr($userinfo['tel'],7,11).'</div><div class=\'buyerinfo\'>￥'.$vo['nowprice'].' x '.$vo['num'].'</div></div>';
				}
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
	//整理库存
	public function inventory($token){
		$where_order['token'] = $token;
		$where_order['paid'] = 0;
		$where_order['endtime'] = array('lt',time());
		$order_list = $this->m_order->where($where_order)->select();
		foreach($order_list as $vo){
			$where['token'] = $token;
			$where['imicms_id'] = $vo['cid'];
			$cutprice = $this->m_cutprice->where($where)->find();
			$save['inventory'] = $cutprice['inventory'] + $vo['num'];
			$update = $this->m_cutprice->where($where)->save($save);
		}
		$del_order = $this->m_order->where($where_order)->delete();
	}
}
?>