<?php 
class AdkanjiaAction extends WapAction{
	public $Adkanjia;
	public $isamap;
		
	public function _initialize(){
		parent::_initialize();
		$this->Adkanjia 	= M('Adkanjia')->where(array('token'=>$this->token,'id'=>$this->_get('id','intval'),'is_open'=>1))->find();
		if(empty($this->Adkanjia)){
			$this->error('活动可能还没开启');
		}
		D('Userinfo')->convertFake(D('Adkanjia_Record'), array('token'=>$this->token, 'fakeopenid'=>$this->fakeopenid, 'wecha_id'=>$this->wecha_id));
		D('Userinfo')->convertFake(D('Adkanjia_User'), array('token'=>$this->token, 'fakeopenid'=>$this->fakeopenid, 'wecha_id'=>$this->wecha_id));
		$this->assign('info',$this->Adkanjia);
	}

	public function index(){
		$id 		= $this->_get('id','intval');
		$share_key 	= $this->_get('share_key','trim');
		$now 		= time();

		if($this->Adkanjia['start']>$now){
			$is_over 	= 1;
		}else if($this->Adkanjia['end']<$now){
			$is_over 	= 2;
		}else{
			$is_over 	= 0;
		}

		if($this->fans){
			
			$us 		= M('Adkanjia_user')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'pid'=>$this->Adkanjia['id']))->find();
			
			if(!empty($this->wecha_id) && empty($us)) {
				$data 	= array(
					'pid' 			=> $this->Adkanjia['id'],
					'wecha_id'		=> $this->wecha_id,
					'token'			=> $this->token,
					'add_time' 		=> time(),
					'help_count' 	=> 1,
					'share_key'		=> $this->getKey(),
					'price'		=> $this->Adkanjia['price'],
				);	
				M('Adkanjia_user')->add($data);
			}
			
			if($share_key){
				$user 	= M('Adkanjia_user')->where(array('token'=>$this->token,'share_key'=>$share_key,'pid'=>$this->Adkanjia['id']))->find();
				$uinfo 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$user['wecha_id']))->field('wechaname,portrait')->find();
				$user['help_count'] 		= $user['help_count'] ? $user['help_count'] : '0';				
				$user['username'] 		= $uinfo['wechaname'] ? $uinfo['wechaname'] : '神秘访客';
				$user['portrait'] 		= $uinfo['portrait'] ? $uinfo['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
			}else{
				$user 	= M('Adkanjia_user')->where(array('token'=>$this->token,'wecha_id'=>$this->fans['wecha_id'],'pid'=>$this->Adkanjia['id']))->find();
				$uinfo 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$user['wecha_id']))->field('wechaname,portrait')->find();	
				$user['help_count'] 		= $user['help_count'] ? $user['help_count'] : '0';
				$user['username'] 		= $uinfo['wechaname'] ? $uinfo['wechaname'] : '神秘访客';
				$user['portrait'] 		= $uinfo['portrait'] ? $uinfo['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
			}

			$rank 	= M('Adkanjia_user')->where(array('token'=>$this->token,'pid'=>$this->Adkanjia['id']))->order('help_count desc,add_time asc')->select();
			$i = 0;
			foreach($rank as $v){
				$i++;
				$paiming[$v['wecha_id']] = $i;
			}
			$user['help_rank'] 	= $paiming[$user['wecha_id']];
		}



		$count 	= M('Adkanjia_user')->where(array('token'=>$this->token,'pid'=>$this->Adkanjia['id']))->count();

		if($this->Adkanjia['is_attention'] == 2 && !$this->isSubscribe()) {
			$this->memberNotice('',1);
		}else if(($this->Adkanjia['is_reg'] == 1 && empty($this->fans)) || ($this->Adkanjia['is_reg'] == 1 && empty($this->fans['tel']))) {
			$this->memberNotice();
		}

		$this->assign('share_key',$share_key);
		$this->assign('share',$share);
		$this->assign('user',$user);
		$this->assign('p',$this->Adkanjia);
		$this->assign('fans',$this->fans);		
		$this->assign('count',$count);
		$this->assign('is_over',$is_over);

		$this->display();
	}
	

	public function add_share(){
		$now 		= time();
		$share_key 	= $this->_get('share_key','trim');
		$cookie 	= cookie('Adkanjia_share');			
		$cookie_arr = json_decode( json_encode( $cookie),true);
		$share 		= M('Adkanjia_user')->where(array('token'=>$this->token,'share_key'=>$share_key))->find();
		$product = M('Adkanjia')->where(array('token'=>$this->token,'id'=>$share['pid'],'is_open'=>1))->find();
		$price = rand(1,500)/(10*2);
		
		$pricea = $price+$share['price'];
		
		if($pricea < $product['okprice'] || $pricea == $product['okprice'] ){
			$priceb = rand($product['okprice'],99);
			$price = $priceb+1;
			$is_price = 1;
		}
		
		$pricec = $price+$share['price'];
		if($product['okprice']==$pricec){
			$price = $price+1;
		}

		if($pricec < 0){
			$price = abs($price);
		}

		if($is_price == 1){
			$price = 0-$price;
		}

		$record 	= array(
			'token' 	=> $this->token,
			'pid' 		=> $share['pid'],
			'share_key' => $share_key,
			'addtime' 	=> time(),
			'wecha_id' 	=> $this->wecha_id,
			'price' 	=> $price,
		);

		if(empty($share)) {
			echo json_encode(array('err'=>2,'info'=>'分享参数错误'));
			exit;
		}

		if($this->Adkanjia['start']>$now){
			echo json_encode(array('err'=>3,'info'=>'砍价还没开始'));
			exit;
		}
		
		if($this->Adkanjia['end']<$now){
			echo json_encode(array('err'=>4,'info'=>'本次砍价活动已结束'));
			exit;
		}

		if($share['wecha_id'] == $this->wecha_id){
			//echo json_encode(array('err'=>4,'info'=>'不能给自己砍价噢！'));
		exit;
		}

		$is_share 	= M('Adkanjia_record')->where(array('token'=>$this->token,'pid'=>$share['pid'],'wecha_id'=>$this->wecha_id,'share_key'=>$share_key))->count();

		if(in_array($share_key, $cookie_arr[$this->Adkanjia['id']]) || $is_share) {
			//echo json_encode(array('err'=>1,'info'=>'请不要重复帮好友砍价'));
			exit;
		}else{
			if(M('Adkanjia_record')->add($record)){
				M('Adkanjia_user')->where(array('token'=>$this->token,'pid'=>$this->Adkanjia['id'],'share_key'=>$share_key))->setDEC('price',$price);
				M('Adkanjia')->where(array('token'=>$this->token,'id'=>$this->Adkanjia['id']))->setInc('shownum',1);
				//记录cookie
				$cookie_arr[$this->Adkanjia['id']][] = $share_key;
				if(empty($cookie_arr[$this->Adkanjia['id']])){
					$cookie_arr[$this->Adkanjia['id']] 	= array();
				}
				array_push($cookie_arr[$this->Adkanjia['id']],$share_key);
				cookie('Adkanjia_share',$cookie_arr,time()+3600*24*30);
				echo json_encode(array('err'=>0,'info'=>'成功为好友砍价 '.$price.' 元'));
				exit;
			}
		}	

	}
	public function adlink(){
		$id 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'id'=>$id);
		$link 	= M('Adkanjia')->where($where)->find();
		//dump($link);
		//die;
		M('Adkanjia')->where(array('token'=>$this->token,'id'=>$id))->setInc('clicknum',1);
		header('Location: '.$link['adurl'].'');
	}

	

	//分享key  最长32
	public function getKey($length=16){
		$str = substr(md5(time().mt_rand(1000,9999)),0,$length);
		return $str;
	}


}

?>