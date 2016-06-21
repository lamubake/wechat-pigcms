<?php 
class WeizhaohuanAction extends WapAction{
	public $Weizhaohuan;
		
	public function _initialize(){
		parent::_initialize();
	
		$this->Weizhaohuan 	= M('Weizhaohuan')->where(array('token'=>$this->token,'id'=>$this->_get('id','intval'),'is_open'=>1))->find();

		if(empty($this->Weizhaohuan)){
			$this->error('活动可能还没开启');
		}
		
		$this->assign('info',$this->Weizhaohuan);
	}

	public function index(){
		$id 		= $this->_get('id','intval');
		$prize		= $this->get_prize($id);
		$share_code = $this->_get('share_code','trim');
		$is_see 	= $this->_get('is_see','intval');
		$now 		= time();
		if($this->Weizhaohuan['start']>$now){
			$is_over 	= 1;
		}else if($this->Weizhaohuan['end']<$now){
			$is_over 	= 2;
		}else{
			$is_over 	= 0;
		}
		
		if(!empty($this->fans) && empty($share_code) && empty($puser) && empty($is_see)){	
			$this->add_puser($this->wecha_id,$id);
		}

		if(($share_code && $is_see == 1) || ($share_code && empty($is_see))){
			$puser 	= M('Weizhaohuan_user')->where(array('token'=>$this->token,'share_key'=>$share_code,'pid'=>$this->Weizhaohuan['id']))->find();
		}else{
			$puser 	= M('Weizhaohuan_user')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'pid'=>$this->Weizhaohuan['id']))->find();
		}

		if($puser){
			$uinfo 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$puser['wecha_id']))->field('wechaname,portrait')->find();	

			$puser['username'] 		= $uinfo['wechaname'] ? $uinfo['wechaname'] : '神秘访客';
			$puser['portrait'] 		= $uinfo['portrait'] ? $uinfo['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';

			$puser['char_itme'] 	= $this->getCharItem($puser['share_count']);
			$share 	= $this->getShare($puser['share_key']);
		}	
	

		$count 	= M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id']))->count();

		if($this->Weizhaohuan['is_attention'] == 1 && !$this->isSubscribe()) {
			$this->memberNotice('',1);
		}else if(($this->Weizhaohuan['is_reg'] == 1 && empty($this->fans)) || ($this->Weizhaohuan['is_attention'] == 2 && !$this->isSubscribe())) {
			$this->memberNotice();
		}

		
		$this->assign('share_code',$share_code);
		$this->assign('fans',$this->fans);
		$this->assign('puser',$puser);
		$this->assign('share',$share);
		$this->assign('prize',$prize);
		$this->assign('count',$count);
		$this->assign('is_over',$is_over);
		$this->assign('is_see',$is_see);
		$this->display();
	}
	
	public function rank(){
		$id 	= $this->_get('id','intval');
		$uid 	= $this->_get('uid','intval');
		
		$where 	= array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id']);
		$count  = M('Weizhaohuan')->where($where)->count();
		$Page   = new Page($count,30);
		$rank 	= M('Weizhaohuan_user')->where($where)->order('share_count desc,add_time asc')->limit(30)->select();

		foreach($rank as $key=>$val){
			$user = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->field('wechaname,portrait')->find();
			$rank[$key]['username']		= $user['wechaname']?$user['wechaname'] : '神秘访客';
			$rank[$key]['portrait']		= $user['portrait']?$user['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
		}
		
		$urank 	= array();
		$uinfo 	= M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'id'=>$uid))->field('wecha_id,share_count')->find();
		$urank['count'] 	= (M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'is_real'=>1,'share_count'=>array('gt',$uinfo['share_count'])))->count())+1;
		$urank['username'] 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$uinfo['wecha_id']))->getField('wechaname');
		
		
		$allcount 	= M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id']))->count();
		$this->assign('allcount',$allcount);
		$this->assign('uinfo',$uinfo);
		$this->assign('urank',$urank);
		$this->assign('rank',$rank);
		$this->display();
	}
	

	public function add_share(){
		//取消COOKIE判断投票
		$cookie 	= cookie('pop_share');
		$cookie_arr = json_decode( json_encode($cookie),true);
		$now 		= time();
		$share 	= array(
				'token' 	=> $this->token,
				'pid'		=> $this->Weizhaohuan['id'],
				'share_key'	=> $this->_get('share_code','trim'),
				'add_time' 	=> time(),
		);

		$share_user 	= M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'share_key'=>$share['share_key']))->find();
		
		if(empty($share_user)){
			echo json_encode(array('err'=>1,'info'=>'分享参数错误'));
			exit;
		}
		
		if($this->Weizhaohuan['start']>$now){
			echo json_encode(array('err'=>3,'info'=>'活动还没开始'));
			exit;
		}
		
		if($this->Weizhaohuan['end']<$now){
			echo json_encode(array('err'=>6,'info'=>'活动已结束'));
			exit;
		}

		if($share_user['wecha_id'] == $this->wecha_id){
			//echo json_encode(array('err'=>4,'info'=>'不能给自己增加人气'));
			exit;
		}

		$is_share 	= M('Weizhaohuan_share')->where(array('token'=>$this->token,'pid'=>$share['pid'],'wecha_id'=>$this->wecha_id,'share_key'=>$share['share_key']))->count();
		
		//if(empty($cookie_arr[$this->Weizhaohuan['id']])){
		//	$cookie_arr[$this->Weizhaohuan['id']] 	= array();
		//	$is_share	= 0;
		//}
		
		//if(empty($this->wecha_id)){
		//	$is_share 	= M('Weizhaohuan_share')->where(array('token'=>$this->token,'pid'=>$share['pid'],'wecha_id'=>array('in',$cookie_arr[$share['pid']]),'share_key'=>$share['share_key']))->count();
		//}else{
		//	$is_share 	= M('Weizhaohuan_share')->where(array('token'=>$this->token,'pid'=>$share['pid'],'wecha_id'=>$this->wecha_id,'share_key'=>$share['share_key']))->count();
		//}


		if($is_share > 0) {
			//echo json_encode(array('err'=>2,'info'=>'请不要重复给好友加助力值'));
			exit;
		}else{

			if(empty($this->wecha_id)){
				$share['wecha_id'] 	= $this->getKey(32);
				$share['uid'] 		= $this->add_puser($share['wecha_id'],0);
			}else{
				$share['wecha_id'] 	= $this->wecha_id;
				$share['uid'] 		= $this->add_puser($this->wecha_id);
			}

			//array_push($cookie_arr[$this->Weizhaohuan['id']],$share['wecha_id']);
		}
		
		
		//随机积分范围
		
		$thisscore = M('Weizhaohuan')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id']))->find();
		$minscore = $thisscore['min'];
		$maxscore = $thisscore['max'];
		$randscore = rand($minscore,$maxscore);
		
		if(M('Weizhaohuan_share')->add($share)){
			M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'share_key'=>$share['share_key']))->setInc('score',$randscore);//增加当前会员ID显示积分
			M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'share_key'=>$share['share_key']))->setInc('share_count',1);//增加当前会员分享值
			M('Weizhaohuan_share')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'wecha_id'=>$this->wecha_id))->setInc('share_score',$randscore);//记录当前会员ID显示积分
			
			$share_user 	= M('Weizhaohuan_user')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'share_key'=>$share['share_key']))->find();
			M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$share_user['wecha_id']))->setInc('total_score',$randscore);//增加当前会员总积分
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
			//
			//记录cookie
			//cookie('pop_share',$cookie_arr,time()+3600*24*365);
			echo json_encode(array('err'=>0,'info'=>'恭喜你为好友增加 1 点人气值和 '.$randscore.' 点积分'));
		}
		
		
	}
	
	public function add_puser($wecha_id,$is_real=1,$id){
		$data 	= array(
			'pid' 			=> $this->Weizhaohuan['id'],
			'wecha_id'		=> $wecha_id, 
			'token'			=> $this->token,
			'add_time' 		=> time(),
			'share_count' 	=> 1,
			'share_key'		=> $this->getKey(),
			'is_real' 		=> 1
		);
		$user = M('Weizhaohuan_user')->where(array('token'=>$this->token,'wecha_id'=>$wecha_id,'is_real'=>1,'pid'=>$this->Weizhaohuan['id']))->find();
		if(empty($user)){
			return M('Weizhaohuan_user')->add($data);
		}else{
			return $user['id'];
		}
		
	}	

	public function maps(){
		$link=$this->amap->getPointMapLink($this->Weizhaohuan['longitude'],$this->Weizhaohuan['latitude'],$this->Weizhaohuan['title']);
		header('Location:'.$link);
	}
	
	
	//进度
	public function getCharItem($share_count){
		$arr 		= array();
		$count 		= M('Weizhaohuan_prize')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id']))->max('count');
		$prize		= M('Weizhaohuan_prize')->where(array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'count'=>array('gt',$share_count)))->order('count asc')->find();

		if(empty($prize)){
			$arr['is_max'] 	= 1;
		}else{
			$arr['name']	= $prize['name'];
			$arr['num']		= $prize['count']-$share_count;
			$arr['allnum']	= $prize['count'];
		}

		$number 		= ($share_count/$count)<1?ceil($share_count/$count*100):100;
		$arr['style'] 	=  'style="width:'.sprintf ("%s%%",$number).'"';
		return $arr;
	}

	
	//分享
	public function getShare($key=""){
		$where 	= array('token'=>$this->token,'pid'=>$this->Weizhaohuan['id'],'uid'=>array('neq',''));
		if($key){
			$where['share_key'] = $key;
		}
		$list = M('Weizhaohuan_share')->where($where)->limit(5)->order('add_time desc')->select();

		foreach($list as $key=>$val){
			$info = M('Weizhaohuan_user')->where(array('token'=>$this->token,'id'=>$val['uid'],'pid'=>$val['pid']))->field('wecha_id,share_key,share_count')->find();
			$user = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$info['wecha_id']))->field('wechaname,portrait')->find();
		
			$list[$key]['share_count']	= $info['share_count'];
			$list[$key]['share_key']	= $info['share_key'];

			if(empty($user)){
				$list[$key]['username']		= '神秘访客';
				$list[$key]['portrait']		= $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
			}else{
				$list[$key]['username']		= $user['wechaname'] ? $user['wechaname'] : '匿名';
				$list[$key]['portrait']		= $user['portrait'] ? $user['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
			}

		}
		
		return $list;
	}
	
	//分享key  最长32
	public function getKey($length=16){
		$str = substr(md5(time().mt_rand(1000,9999)),0,$length);
		return $str;
	}
	//记录奖品
	public function get_prize($id){
		$where 	= array('token'=>$this->token,'pid'=>$id,'name'=>array('neq',''),'img'=>array('neq',''),'num'=>array('neq',''),'count'=>array('neq',''));
		$data 	= M('Weizhaohuan_prize')->where($where)->order('count asc')->select();
		$sum 	= M('Weizhaohuan_prize')->where($where)->max('count');
		$count 	= count($data);
		foreach($data as $key=>$val){
			$data[$key]['unused']			= $val['num']-$val['use_num'];
			$data[$key]['list_style']		= 'style="width:'.(100/$count).'%"';
			//$data[$key]['bubble_style']		= ($key+1)==$count?'style="right:-1px"':'style="left:'.(($key+1)/$count*100-($key+1)*3).'%"';
			$data[$key]['bubble_style']		= $key<$count-1?'style="left:'.(intval($val['count']/$sum*100-($key+$count))).'%"':'style="right:-1px"';
		}
		return $data;
	}
}

?>