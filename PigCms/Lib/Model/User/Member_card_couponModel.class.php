<?php
class Member_card_couponModel extends Model{
	protected $_validate = array(
			array('title','require','优惠券名称不能为空',1),
			array('type','require','优惠券类型必须选择',1),
			array('cardid','require','卡券所属会员卡必须选择',1),
			array('info','require','优惠券介绍不能为空',1),
			array('people','require','领券次数不能为空',1),
			array('total','require','库存必须填写',1),
			array('enddate', 'checkdate', '结束时间不能小于开始时间',Model::MUST_VALIDATE,'callback',3),
			array('price', 'checktype', '选择现金抵用券,必须输入金额',Model::MUST_VALIDATE,'callback',3),
			array('gift_name', 'checkGift', '请填写礼品名称',Model::MUST_VALIDATE,'callback',3),
	 );
	protected $_auto = array (    
		array('statdate','strtotime',3,'function'), 
		array('enddate','strtotime',3,'function'), 
		array('token','getToken',Model:: MODEL_BOTH,'callback'), 
		//array('is_weixin','wxCoupons',Model:: MODEL_BOTH,'callback'),
		array('create_time','time',1,'function'),
		array('id','getid',Model:: MODEL_BOTH,'callback'),
	);
	function getid(){
		return $_GET['itemid'];
	}
	function getToken(){	
		return $_SESSION['token'];
	}
	function checkdate(){	
		if(strtotime($_POST['enddate'])<strtotime($_POST['statdate'])){
			return false;
		}else{
			return true;
		}
	}
	function wxCoupons(){
		$is_weixin 	= $this->where("token='{$_SESSION['token']}'")->getField('is_weixin');
		return $is_weixin;
	}
	function checktype(){	
		 if($_POST['type']==0){
			 if(empty($_POST['least_cost']) || empty($_POST['reduce_cost'])){
				return false;	
			 }else{
				return true;
			 }
		}else{
			return true;
		}
	}

	function checkGift(){	
		 if($_POST['type']==2){
			 if(empty($_POST['gift_name'])){
				return false;	
			 }else{
				return true;
			 }
		}else{
			return true;
		}
	}

}

?>
