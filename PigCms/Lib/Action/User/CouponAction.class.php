<?php
class CouponAction extends LotteryBaseAction {
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('choujiang');
	}
	public function index(){
		parent::index(3);
		$this->display();
	}
	public function sn(){
		parent::sn(3);
		$this->display('Lottery:sn');
	}
	public function add(){
		$Member_card_coupon_list = M("member_card_coupon")->where(array('token'=>$this->token,'attr'=>'0','is_delete'=>0,'type'=>1,'is_weixin'=>0))->count();
		if($Member_card_coupon_list < 1){
			$this->error('请先在会员卡中添加非微信优惠券！',U("User/Member_card/coupons"));
		}
		$Member_card_set_list = M("member_card_set")->where(array('token'=>$this->token))->field('id,cardname')->select();
		$this->assign("Member_card_set_list",$Member_card_set_list);
		if(IS_POST){
			$where_card_coupon['cardid'] = intval($_POST['cardid']);
			$where_card_coupon['token'] = $this->token;
			$save_card_coupon['is_huodong'] = 1;
			if($_POST['fist'] != ''){
				$where_card_coupon['id'] = intval($_POST['fist']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['second'] != ''){
				$where_card_coupon['id'] = intval($_POST['second']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['third'] != ''){
				$where_card_coupon['id'] = intval($_POST['third']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['four'] != ''){
				$where_card_coupon['id'] = intval($_POST['four']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['five'] != ''){
				$where_card_coupon['id'] = intval($_POST['five']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['six'] != ''){
				$where_card_coupon['id'] = intval($_POST['six']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			
		}
		parent::add(3);
	}
	public function edit(){
		$cardid = M('Lottery')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->getField('cardid');
		$this->assign('cardid',$cardid);
		$Member_card_set_list = M("member_card_set")->where(array('token'=>$this->token))->field('id,cardname')->select();
		$this->assign("Member_card_set_list",$Member_card_set_list);
		$Member_card_coupon_list = M("member_card_coupon")->where(array('token'=>$this->token,'attr'=>'0','is_delete'=>0,'is_weixin'=>0,'type'=>1,'cardid'=>intval($cardid)))->field('id,title,total')->select();
		$this->assign("Member_card_coupon_list",$Member_card_coupon_list);
		if(IS_POST){
			$thisLottery = M('lottery')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->find();
			if($thisLottery['fist'] != ''){
				$lottery_card_coupon_count1 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'fist'=>$thisLottery['fist']))->count();
				$lottery_card_coupon_count2 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'second'=>$thisLottery['fist']))->count();
				$lottery_card_coupon_count3 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'third'=>$thisLottery['fist']))->count();
				$lottery_card_coupon_count4 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'four'=>$thisLottery['fist']))->count();
				$lottery_card_coupon_count5 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'five'=>$thisLottery['fist']))->count();
				$lottery_card_coupon_count6 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'six'=>$thisLottery['fist']))->count();
				$lottery_card_coupon_count = $lottery_card_coupon_count1+$lottery_card_coupon_count2+$lottery_card_coupon_count3+$lottery_card_coupon_count4+$lottery_card_coupon_count5+$lottery_card_coupon_count6;
				if($lottery_card_coupon_count < 2){
					$up_card_coupon_0 = M("member_card_coupon")->where(array('token'=>$this->token,'cardid'=>$thisLottery['cardid'],'id'=>intval($thisLottery['fist'])))->save(array('is_huodong'=>0));
				}
			}
			if($thisLottery['second'] != ''){
				$lottery_card_coupon_count1 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'fist'=>$thisLottery['second']))->count();
				$lottery_card_coupon_count2 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'second'=>$thisLottery['second']))->count();
				$lottery_card_coupon_count3 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'third'=>$thisLottery['second']))->count();
				$lottery_card_coupon_count4 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'four'=>$thisLottery['second']))->count();
				$lottery_card_coupon_count5 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'five'=>$thisLottery['second']))->count();
				$lottery_card_coupon_count6 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'six'=>$thisLottery['second']))->count();
				$lottery_card_coupon_count = $lottery_card_coupon_count1+$lottery_card_coupon_count2+$lottery_card_coupon_count3+$lottery_card_coupon_count4+$lottery_card_coupon_count5+$lottery_card_coupon_count6;
				if($lottery_card_coupon_count < 2){
					$up_card_coupon_0 = M("member_card_coupon")->where(array('token'=>$this->token,'cardid'=>$thisLottery['cardid'],'id'=>intval($thisLottery['second'])))->save(array('is_huodong'=>0));
				}
			}
			if($thisLottery['third'] != ''){
				$lottery_card_coupon_count1 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'fist'=>$thisLottery['third']))->count();
				$lottery_card_coupon_count2 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'second'=>$thisLottery['third']))->count();
				$lottery_card_coupon_count3 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'third'=>$thisLottery['third']))->count();
				$lottery_card_coupon_count4 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'four'=>$thisLottery['third']))->count();
				$lottery_card_coupon_count5 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'five'=>$thisLottery['third']))->count();
				$lottery_card_coupon_count6 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'six'=>$thisLottery['third']))->count();
				$lottery_card_coupon_count = $lottery_card_coupon_count1+$lottery_card_coupon_count2+$lottery_card_coupon_count3+$lottery_card_coupon_count4+$lottery_card_coupon_count5+$lottery_card_coupon_count6;
				if($lottery_card_coupon_count < 2){
					$up_card_coupon_0 = M("member_card_coupon")->where(array('token'=>$this->token,'cardid'=>$thisLottery['cardid'],'id'=>intval($thisLottery['third'])))->save(array('is_huodong'=>0));
				}
			}
			if($thisLottery['four'] != ''){
				$lottery_card_coupon_count1 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'fist'=>$thisLottery['four']))->count();
				$lottery_card_coupon_count2 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'second'=>$thisLottery['four']))->count();
				$lottery_card_coupon_count3 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'third'=>$thisLottery['four']))->count();
				$lottery_card_coupon_count4 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'four'=>$thisLottery['four']))->count();
				$lottery_card_coupon_count5 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'five'=>$thisLottery['four']))->count();
				$lottery_card_coupon_count6 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'six'=>$thisLottery['four']))->count();
				$lottery_card_coupon_count = $lottery_card_coupon_count1+$lottery_card_coupon_count2+$lottery_card_coupon_count3+$lottery_card_coupon_count4+$lottery_card_coupon_count5+$lottery_card_coupon_count6;
				if($lottery_card_coupon_count < 2){
					$up_card_coupon_0 = M("member_card_coupon")->where(array('token'=>$this->token,'cardid'=>$thisLottery['cardid'],'id'=>intval($thisLottery['four'])))->save(array('is_huodong'=>0));
				}
			}
			if($thisLottery['five'] != ''){
				$lottery_card_coupon_count1 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'fist'=>$thisLottery['five']))->count();
				$lottery_card_coupon_count2 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'second'=>$thisLottery['five']))->count();
				$lottery_card_coupon_count3 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'third'=>$thisLottery['five']))->count();
				$lottery_card_coupon_count4 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'four'=>$thisLottery['five']))->count();
				$lottery_card_coupon_count5 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'five'=>$thisLottery['five']))->count();
				$lottery_card_coupon_count6 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'six'=>$thisLottery['five']))->count();
				$lottery_card_coupon_count = $lottery_card_coupon_count1+$lottery_card_coupon_count2+$lottery_card_coupon_count3+$lottery_card_coupon_count4+$lottery_card_coupon_count5+$lottery_card_coupon_count6;
				if($lottery_card_coupon_count < 2){
					$up_card_coupon_0 = M("member_card_coupon")->where(array('token'=>$this->token,'cardid'=>$thisLottery['cardid'],'id'=>intval($thisLottery['five'])))->save(array('is_huodong'=>0));
				}
			}
			if($thisLottery['six'] != ''){
				$lottery_card_coupon_count1 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'fist'=>$thisLottery['six']))->count();
				$lottery_card_coupon_count2 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'second'=>$thisLottery['six']))->count();
				$lottery_card_coupon_count3 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'third'=>$thisLottery['six']))->count();
				$lottery_card_coupon_count4 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'four'=>$thisLottery['six']))->count();
				$lottery_card_coupon_count5 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'five'=>$thisLottery['six']))->count();
				$lottery_card_coupon_count6 = M('lottery')->where(array('token'=>$this->token,'type'=>3,'six'=>$thisLottery['six']))->count();
				$lottery_card_coupon_count = $lottery_card_coupon_count1+$lottery_card_coupon_count2+$lottery_card_coupon_count3+$lottery_card_coupon_count4+$lottery_card_coupon_count5+$lottery_card_coupon_count6;
				if($lottery_card_coupon_count < 2){
					$up_card_coupon_0 = M("member_card_coupon")->where(array('token'=>$this->token,'cardid'=>$thisLottery['cardid'],'id'=>intval($thisLottery['six'])))->save(array('is_huodong'=>0));
				}
			}
			//--------------
			$where_card_coupon['cardid'] = intval($_POST['cardid']);
			$where_card_coupon['token'] = $this->token;
			$save_card_coupon['is_huodong'] = 1;
			if($_POST['fist'] != ''){
				$where_card_coupon['id'] = intval($_POST['fist']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['second'] != ''){
				$where_card_coupon['id'] = intval($_POST['second']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['third'] != ''){
				$where_card_coupon['id'] = intval($_POST['third']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['four'] != ''){
				$where_card_coupon['id'] = intval($_POST['four']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['five'] != ''){
				$where_card_coupon['id'] = intval($_POST['five']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
			if($_POST['six'] != ''){
				$where_card_coupon['id'] = intval($_POST['six']);
				$up_card_coupon = M("member_card_coupon")->where($where_card_coupon)->save($save_card_coupon);
			}
		}
		parent::edit(3);
	}
	public function ajax(){
		switch($_POST['type']){
			case 'couponlist':
				$Member_card_coupon_list = M("member_card_coupon")->where(array('token'=>$this->token,'attr'=>'0','type'=>1,'is_delete'=>0,'is_weixin'=>0,'cardid'=>intval($_POST['cardid'])))->field('id,title,total')->select();
				$data['couponlist'] = '<option value=\'\'>请选择优惠券</option>';
				foreach($Member_card_coupon_list as $vo){
					$data['couponlist'] .= '<option value=\''.$vo['id'].'\' num=\''.$vo['total'].'\'>'.$vo['title'].'</option>';
				}
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
}


?>