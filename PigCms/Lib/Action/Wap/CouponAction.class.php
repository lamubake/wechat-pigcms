<?php
class CouponAction extends LotteryBaseMoreAction{
	public $token;
	public $wecha_id;
	public $lottory_record_db;
	public $lottory_db;

	public function index(){
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		$this->token=$this->_get('token');
		$this->wecha_id	= $this->wecha_id;
		$this->lottory_record_db=M('Lottery_record');
		$this->lottory_db=M('Lottery');
		if (!defined('RES')){
			define('RES',THEME_PATH.'common');
		}
		if (!defined('STATICS')){
			define('STATICS',TMPL_PATH.'static');
		}
		
		
		$token		= $this->token;
		$wecha_id	= $this->wecha_id;
		$id 		= $this->_get('id');
		$Lottery 	= $this->lottory_db->where(array('id'=>$id,'token'=>$token,'type'=>3,'status'=>1))->find();
		if(!($Lottery)){
			$this->error("不存在的活动!");
		}
		$Lottery['renametel']=$Lottery['renametel']?$Lottery['renametel']:'手机号';
		$Lottery['renamesn']=$Lottery['renamesn']?$Lottery['renamesn']:'SN码';
		$this->assign('lottery',$Lottery);
		$user_card = M("member_card_create")->where(array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$Lottery['cardid']))->count();
		$card_name = M("member_card_set")->where(array('token'=>$token,'id'=>$Lottery['cardid']))->getField('cardname');
		if($user_card < 1){
			$this->error('您未领取“'.$card_name.'”，请先到会员卡中心领取。',U("Wap/Card/index",array('token'=>$token,'wecha_id'=>$wecha_id)));
		}
		if ($Lottery['statdate']>time()){
			$data['usenums']=0;
		}else {
			
			$data=$this->prizeHandle($token,$wecha_id,$Lottery);
			//优惠券写入卡券信息
			if($data['prizetype'] != '' && $data['prizetype'] < 7){
				$add_coupon_record['use_time'] = '';
				$add_coupon_record['add_time'] = time();
				switch($data['prizetype']){
					case 1:
						$add_coupon_record['coupon_id'] = intval($Lottery['fist']);
					break;
					case 2:
						$add_coupon_record['coupon_id'] = intval($Lottery['second']);
					break;
					case 3:
						$add_coupon_record['coupon_id'] = intval($Lottery['third']);
					break;
					case 4:
						$add_coupon_record['coupon_id'] = intval($Lottery['four']);
					break;
					case 5:
						$add_coupon_record['coupon_id'] = intval($Lottery['five']);
					break;
					case 6:
						$add_coupon_record['coupon_id'] = intval($Lottery['six']);
					break;
				}
				$add_coupon_record['cardid'] = $Lottery['cardid'];
				$add_coupon_record['token'] = $token;
				$add_coupon_record['wecha_id'] = $wecha_id;
				$add_coupon_record['coupon_type'] = 1;
				$add_coupon_record['iswhere'] = 1;
				$add_coupon_record['whereid'] = $id;
				$add_coupon_record['cancel_code'] = $this->randStr(12);
				$coupon = M('Member_card_coupon')->where(array('token'=>$token,'cardid'=>$Lottery['cardid'],'id'=>$add_coupon_record['coupon_id']))->find();
				$add_coupon_record['company_id'] = $coupon['company_id'];
				$add_coupon_record['coupon_attr']    = serialize(array('coupon_name'=>$coupon['title']));
				$id_coupon_record = M('Member_card_coupon_record')->add($add_coupon_record);
			}
		}
		if($data['end'] == 4 || $data['end'] == 3){
			$data['winprize'] = $data['msg'];
			$data['zjl'] = 0;
		}
		$data['token'] 		= $token;
		$data['wecha_id']	= $wecha_id;		
		$data['lid']		= $Lottery['id'];
		$data['id']		= $Lottery['id'];
		$data['keyword']		= $Lottery['keyword'];
		$data['title']		= $Lottery['title'];
		$data['startpicurl']		= $Lottery['startpicurl'];
		
		$data['phone']		= $data['phone']; 
		$data['usenums'] = M('Lottery_record')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id))->getField('usenums');
		$data['usenums']	= $data['usenums'];
		$data['sendtime']	= $data['sendtime'];
		$data['canrqnums']	= $Lottery['canrqnums'];
		$data['fist'] 		= $Lottery['fist'];
		$data['second'] 	= $Lottery['second'];
		$data['third'] 		= $Lottery['third'];
		$data['fistnums'] 	= $Lottery['fistnums'];
		$data['secondnums'] = $Lottery['secondnums'];
		$data['thirdnums'] 	= $Lottery['thirdnums'];	
		$data['four'] 		= $Lottery['four'];
		$data['five'] 	= $Lottery['five'];
		$data['six'] 		= $Lottery['six'];
		$data['fournums'] 	= $Lottery['fournums'];
		$data['fivenums'] = $Lottery['fivenums'];
		$data['sixnums'] 	= $Lottery['sixnums'];	
		$data['info']		= $Lottery['info'];
		$data['aginfo']		= $Lottery['aginfo'];
		$data['txt']		= $Lottery['txt'];
		$data['sttxt']		= $Lottery['sttxt'];
		$data['title']		= $Lottery['title'];
		$data['statdate']	= $Lottery['statdate'];
		$data['enddate']	= $Lottery['enddate'];
		$data['bg']	= $Lottery['bg'];
		$data['bgtype']	= $Lottery['bgtype'];
		$data['info']=nl2br($data['info']);
		$data['aginfo']=nl2br($data['aginfo']);
		$data['endinfo']=nl2br($data['endinfo']);	
		$this->assign('Coupon',$data);
		$redata	= M('Lottery_record');
		$record = $redata->where(array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id,'islottery'=>1))->order('time desc')->select();
		$this->assign('lotterylist',$record);
		$lotteryname[1] = $Lottery['fist'];
		$lotteryname[2] = $Lottery['second'];
		$lotteryname[3] = $Lottery['third'];
		$lotteryname[4] = $Lottery['four'];
		$lotteryname[5] = $Lottery['five'];
		$lotteryname[6] = $Lottery['six'];
		$this->assign('lotteryname',$lotteryname);
		$Member_card_coupon_list = M("member_card_coupon")->where(array('token'=>$token,'attr'=>'0','is_delete'=>0,'type'=>1,'cardid'=>intval($Lottery['cardid'])))->field('id,title')->select();
		foreach($Member_card_coupon_list as $couponvo){
			$couponname[$couponvo['id']] = $couponvo['title'];
		}
		$this->assign('couponname',$couponname);
		$this->display();
	}
	function randStr($length=4,$type="number"){
        $array = array(
            'number' => '0123456789',
            'string' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'mixed' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        );
        $string = $array[$type];
        $count = strlen($string)-1;
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $string[mt_rand(0, $count)];
        }
        return $rand;
    }
}
	
?>