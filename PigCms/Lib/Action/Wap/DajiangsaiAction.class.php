<?php 
class DajiangsaiAction extends WapAction{
	public $Dajiangsai;
	public $isamap;
		
	public function _initialize(){
		parent::_initialize();

		$this->Dajiangsai 	= M('Dajiangsai')->where(array('token'=>$this->token,'id'=>$this->_get('id','intval'),'is_open'=>1))->find();

		if(empty($this->Dajiangsai)){
			$this->error('活动可能还没开启');
		}

		$this->assign('info',$this->Dajiangsai);
	}

	public function index(){
		$id 		= $this->_get('id','intval');
		$share_key 	= $this->_get('share_key','trim');
		$now 		= time();

		if($this->Dajiangsai['start']>$now){
			$is_over 	= 1;
		}else if($this->Dajiangsai['end']<$now){
			$is_over 	= 2;
		}else{
			$is_over 	= 0;
		}

		if($this->fans){
			
			$us 		= M('Dajiangsai_user')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'pid'=>$this->Dajiangsai['id']))->find();
			
			//判断关注
			//if($this->isSubscribe()){
				if(!empty($this->wecha_id) && empty($us)) {
					$data 	= array(
						'pid' 			=> $this->Dajiangsai['id'],
						'wecha_id'		=> $this->wecha_id,
						'token'			=> $this->token,
						'add_time' 		=> time(),
						'help_count' 	=> 1,
						'share_key'		=> $this->getKey(),
					);	
					M('Dajiangsai_user')->add($data);
				}
			//}else{
			//		$issub = 0;
			//}

			if($share_key){
				$user 	= M('Dajiangsai_user')->where(array('token'=>$this->token,'share_key'=>$share_key,'pid'=>$this->Dajiangsai['id']))->find();
				$uinfo 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$user['wecha_id']))->field('wechaname,portrait,tel')->find();
				$user['help_count'] 		= $user['help_count'] ? $user['help_count'] : '0';				
				$user['username'] 		= $uinfo['wechaname'] ? $uinfo['wechaname'] : '神秘访客';
				$user['portrait'] 		= $uinfo['portrait'] ? $uinfo['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
				$user['tel'] 			= $uinfo['tel']?substr_replace($uinfo['tel'],'****',3,4):'无';
				$user['sharecount']  	= M('Share')->where(array('token'=>$this->token,'wecha_id'=>$user['wecha_id'],'moduleid'=>$this->Dajiangsai['id'],'module'=>'Dajiangsai'))->count();
			}else{
				$user 	= M('Dajiangsai_user')->where(array('token'=>$this->token,'wecha_id'=>$this->fans['wecha_id'],'pid'=>$this->Dajiangsai['id']))->find();
				$uinfo 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$user['wecha_id']))->field('wechaname,portrait,tel')->find();	
				$user['help_count'] 		= $user['help_count'] ? $user['help_count'] : '0';
				$user['username'] 		= $uinfo['wechaname'] ? $uinfo['wechaname'] : '神秘访客';
				$user['portrait'] 		= $uinfo['portrait'] ? $uinfo['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
				$user['tel'] 			= $uinfo['tel']?substr_replace($uinfo['tel'],'****',3,4):'无';
				$user['sharecount']  	= M('Share')->where(array('token'=>$this->token,'wecha_id'=>$this->fans['wecha_id'],'moduleid'=>$this->Dajiangsai['id'],'module'=>'Dajiangsai'))->count();
				$user['number']   =	round((($user['help_count']-1)/$user['sharecount']*100),2);
			}
			
			$rank 	= M('Dajiangsai_user')->where(array('token'=>$this->token,'help_count'=>array('egt',$user['help_count']),'add_time'=>array('lt',$user['add_time']),'pid'=>$this->Dajiangsai['id']))->count();
			$user['help_rank'] 	= $rank+1;
			
		}



		$count 	= M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$this->Dajiangsai['id']))->count();

		if($this->Dajiangsai['is_attention'] == 1 && !$this->isSubscribe()) {
			$this->memberNotice('',1);
		}else if(($this->Dajiangsai['is_reg'] == 1 && empty($this->fans)) || ($this->Dajiangsai['is_attention'] == 2 && !$this->isSubscribe())) {
			$this->memberNotice();
		}
		
		$this->assign('share_key',$share_key);
		$this->assign('issub',$issub);
		$this->assign('share',$share);
		$this->assign('rank',$this->get_rank());
		$this->assign('user',$user);
		$this->assign('fans',$this->fans);		
		$this->assign('count',$count);
		$this->assign('is_over',$is_over);

		$this->display();
	}
	

	public function add_share(){
		$now 		= time();
		$share_key 	= $this->_get('share_key','trim');
		//$cookie 	= cookie('Dajiangsai_share');			
		//$cookie_arr = json_decode( json_encode( $cookie),true);
		$share 		= M('Dajiangsai_user')->where(array('token'=>$this->token,'share_key'=>$share_key))->find();

		$record 	= array(
			'token' 	=> $this->token,
			'pid' 		=> $share['pid'],
			'share_key' => $share_key,
			'addtime' 	=> time(),
			'wecha_id' 	=> $this->wecha_id,
		);

		if(empty($share)) {
			echo json_encode(array('err'=>2,'info'=>'分享参数错误'));
			exit;
		}


		if($this->Dajiangsai['start']>$now){
			echo json_encode(array('err'=>3,'info'=>'活动还没开始'));
			exit;
		}
		
		if($this->Dajiangsai['end']<$now){
			echo json_encode(array('err'=>4,'info'=>'活动已结束'));
			exit;
		}

		if($share['wecha_id'] == $this->wecha_id){
			//echo json_encode(array('err'=>4,'info'=>'不能给自己增加助力值'));
			exit;
		}

		$is_share 	= M('Dajiangsai_record')->where(array('token'=>$this->token,'pid'=>$share['pid'],'wecha_id'=>$this->wecha_id,'share_key'=>$share_key))->count();

		//if(in_array($share_key, $cookie_arr[$this->Dajiangsai['id']]) || $is_share) {
			//上面是根据COOKIE来判断是否投票，改为下面读取数据库返回0是没有
		if($is_share > 0) {
			echo json_encode(array('err'=>1,'info'=>'请不要重复给好友加助力值'));
			exit;
		}else{
			if(M('Dajiangsai_record')->add($record)){
				M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$this->Dajiangsai['id'],'share_key'=>$share_key))->setInc('help_count',1);	
				//是否开启积分判断开始
				$thisscore = M('Dajiangsai')->where(array('token'=>$this->token,'id'=>$this->Dajiangsai['id']))->find();
				$minscore = $thisscore['minscore'];
				$maxscore = $thisscore['maxscore'];
				$randscore = rand($minscore,$maxscore);
				if($thisscore['is_score'] != 2 ){
					M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$this->Dajiangsai['id'],'share_key'=>$share_key))->setInc('help_score',$randscore);//增加分享会员ID积分
					M('Dajiangsai_record')->where(array('token'=>$this->token,'pid'=>$this->Dajiangsai['id'],'wecha_id'=>$this->wecha_id))->setInc('score',$randscore);//记录当前会员ID显示增加积分
					
					$share_user 	= M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$this->Dajiangsai['id'],'share_key'=>$share_key))->find();
					M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$share_user['wecha_id']))->setInc('total_score',$randscore);//增加分享会员总积分-真实增加积分
					
					//记录分享积分
					$row2=array();
					$row2['token']=$this->token;
					$row2['wecha_id']=$share_user['wecha_id'];
					$row2['expense']=0;
					$row2['time']=$now;
					$row2['cat']=98;
					$row2['staffid']=0;
					$row2['score']=$randscore;
					M('Member_card_use_record')->add($row2);
					
				}
				
				//记录cookie
				//$cookie_arr[$this->Dajiangsai['id']][] = $share_key;
				//if(empty($cookie_arr[$this->Dajiangsai['id']])){
				//	$cookie_arr[$this->Dajiangsai['id']] 	= array();
				//}
				//array_push($cookie_arr[$this->Dajiangsai['id']],$share_key);
				//cookie('Dajiangsai_share',$cookie_arr,time()+3600*24*30);
				
				echo json_encode(array('err'=>0,'info'=>'你的好友成功增加了1点助力值'));
				exit;
			}
		}	

	}
	public function get_rank(){
		$where 	= array('token'=>$this->token,'pid'=>$this->Dajiangsai['id']);
		$rank 	= M('Dajiangsai_user')->where($where)->order('help_count desc,add_time asc')->limit(20)->select();
		foreach($rank as $key=>$val){
			$user_info = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();
			$user['sharecount']  	= M('Share')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id'],'pid'=>$this->Dajiangsai['id'],'module'=>'Dajiangsai'))->count();
			$rank[$key]['nums']		=	round((($val['help_count']-1)/$user['sharecount']*100),2);
			$rank[$key]['username'] 	= $user_info['wechaname']?$user_info['wechaname']:'匿名';
			$rank[$key]['tel'] 			= $user_info['tel']?substr_replace($user_info['tel'],'****',3,4):'无';
		}
		return $rank;
	}
	
	//统计当前ID的分享次数
	public function tongji(){

		$pid = $this->_get('id','intval');
		$wecha_id = $this -> wecha_id;
		$token= $this -> token;

		$user 	= M('Dajiangsai_user')->where(array('token'=>$this->token,'wecha_id'=>$wecha_id,'pid'=>$this->Dajiangsai['id']))->find();
		$uinfo 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$user['wecha_id']))->field('wechaname,portrait')->find();	
		$user['username'] 		= $uinfo['wechaname'] ? $uinfo['wechaname'] : '神秘访客';
		$user['portrait'] 		= $uinfo['portrait'] ? $uinfo['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
		
		$where 	= array('token'=>$this->token,'pid'=>$this->Dajiangsai['id']);
		$rank 	= M('Dajiangsai_user')->where($where)->order('help_count desc,add_time asc')->limit(30)->select();
		foreach($rank as $key=>$val){
			$user_info = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();
			$user['sharecount']  	= M('Share')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id'],'pid'=>$this->Dajiangsai['id'],'module'=>'Dajiangsai'))->count();
			$rank[$key]['nums']		=	round((($val['help_count']-1)/$user['sharecount']*100),2);
			$rank[$key]['username'] 	= $user_info['wechaname']?$user_info['wechaname']:'匿名';
			$rank[$key]['tel'] 			= $user_info['tel']?substr_replace($user_info['tel'],'****',3,4):'无';
		}
		
		$count 	= M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$this->Dajiangsai['id']))->count();
		
		$this->assign('rank',$rank);
		$this->assign('user',$user);
		$this->assign('fans',$this->fans);		
		$this->assign('count',$count);
		
        $this -> display();
    }
	//统计结束


	//分享key  最长32
	public function getKey($length=16){
		$str = substr(md5(time().mt_rand(1000,9999)),0,$length);
		return $str;
	}


}

?>