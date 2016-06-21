<?php

class AaactivityAction extends WapAction{
	public $is_wechat;

	public function _initialize(){
		parent::_initialize();
		/*$checkFunc=new checkFunc();if (!function_exists('fdsrejsie3qklwewerzdagf4ds')){exit('error-4');}
        $checkFunc->cfdwdgfds3skgfds3szsd3idsj();*/
        
		$is_wechat 	= array(
			'baoming',
			'my_focus',
			'pay',
			'index',
			'home',
		);

		if(in_array(ACTION_NAME,$is_wechat)){
			if(empty($this->wecha_id)){
				$this->error('没有个人信息，无法操作');
			}
		}

		$copyright 	= M('Home')->where(array('token'=>$this->token))->getField('copyright');
		$this->assign('copyright',$copyright);
	}

	//项目详情
	public function index(){
		$id 	= $this->_get('id','intval');
		$share_key 	= $this->_get('share_key','trim');
		$where 	= array('token'=>$this->token,'id'=>$id,'is_open'=>1);
		$info 	= M('Aaactivity')->where($where)->find();
		
		if(empty($info)){
			$this->error('没找到项目');
		}

		if(empty($info['id'])){
			$this->error('参数错误');
		}

		if($info['enddate'] < time()){
			$this->assign('is_over',1);
		}

		if($info['is_sub'] == 1 && !$this->isSubscribe()) {
			$this->memberNotice('',1);
		}else if(($info['is_reg'] == 1 && empty($this->fans)) || ($info['is_reg'] == 1 && empty($this->fans['tel']))) {
			$this->memberNotice();
		}

		$reward 	= M('Aaactivity_user')->where(array('token'=>$this->token,'aid'=>$info['id'],'wecha_id'=>$this->fans['wecha_id']))->find();
		
		if($reward){
			$this->assign('is_ok',1);
		}
		
		$info['progress'] 		= round(($info['joinnum']/$info['minnums'])*100,2);
		$info['zanzhu']			=	($info['joinnum']*$info['backfeiyong']);
		$info['surplus']		=	($info['maxnums']-$info['joinnum']);
		
		$lucky_guys = M('Aaactivity_user')->where(array('token'=>$this->token,'aid'=>$info['id']))->order('id desc')->select();
		foreach ($lucky_guys as $key => $val) {
			$user = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();
			$lucky_guys[$key]['tel']  = $user['tel']?substr_replace($user['tel'],'****',3,4):'无';
			$lucky_guys[$key]['portrait']  = $user['portrait'] ? $user['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
		}
		$this->assign('lucky_guys',$lucky_guys);
		
		$this->assign('reward',$reward);
		$this->assign('share_key',$share_key);
		$this->assign('info',$info);
		$this->display();
	}
	//报名页面
	public function baoming(){
		
		$id 	= $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'id'=>$id,'is_open'=>1);
		$info 	= M('Aaactivity')->where($where)->find();
		$share_key	= $this->_get('share_key','trim');
		
		$where1 	= array('wecha_id'=>$this->wecha_id,'aid'=>$id,'token'=>$this->token);
		$userinfo 	= M('Aaactivity_user')->where($where1)->find();
		if(!empty($userinfo)){
			$this->error('你已经报过名了');
		}
		
		if($info['is_audit'] != 1){
			$this->error('未审核的项目');
		}
		
		if($info['joinnum'] == $info['maxnums']){
			$this->error('哎呀，报名者太多啦，已经满额了！请关注下期，你也可以发起活动哦！');
		}

		if(empty($info)){
			$this->error('没找到项目');
		}

		if(empty($info['id'])){
			$this->error('参数错误');
		}

		if($info['enddate'] < time()){
			$this->assign('is_over',1);
		}
		$info['surplus']		=	($info['maxnums']-$info['joinnum']);
		$this->assign('share_key',$share_key);
		$this->assign('info',$info);
		$this->display();
	}
	//我参与的活动
	public function my_focus(){
		$share_key	= $this->_get('share_key','trim');
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id);
		$count      = M('Aaactivity_user')->where($where)->count();
        $Page       = new Page($count,15);
		$Page->setConfig('theme', ' %upPage% %downPage% %prePage% %nextPage% ');
 		$focus      = M('Aaactivity_user')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		if(empty($focus)){
			$text='你还没有任何记录，多多参加活动，才有机会中大奖！';
			$this->assign('text',$text);
		}
 		foreach ($focus as $key => $val) {
			$focus[$key] = M('Aaactivity')->where(array('token'=>$this->token,'id'=>$val['aid'],'is_audit'=>'1'))->find();
			$focus[$key]['progress'] 		= round(($focus[$key]['joinnum']/$focus[$key]['minnums'])*100,2);
			$focus[$key]['zanzhu']			=	($focus[$key]['joinnum']*$focus[$key]['backfeiyong']);
			$focus[$key]['surplus']		=	($focus[$key]['maxnums']-$focus[$key]['joinnum']);
 		}
		
		if($focus['enddate'] < time()){
			$this->assign('is_over',1);
		}

		$this->assign('page',$Page->show());
		$this->assign('share_key',$share_key);
		$this->assign('focus',$focus);
		$this->display();
	}
	//报名成功处理
	public function pay(){
		
		$where 	= array('wecha_id'=>$this->wecha_id,'id'=>$_POST['aid'],'token'=>$this->token);
		$userinfo 	= M('Aaactivity_user')->where($where)->find();
		$share_key	= $this->_get('share_key','trim');

		if(empty($userinfo)){
			//积分扣除
			/*$where1 	= array('wecha_id'=>$this->wecha_id,'token'=>$this->token);
			$userinfo1 	= M('Userinfo')->where($where1)->find();
			if($userinfo1['total_score'] > $_POST['score']){
				M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$_POST['wecha_id']))->setDec('total_score',$_POST['score']);//扣除会员总积分-真实积分
				//记录分享积分
				$row2=array();
				$row2['token']=$this->token;
				$row2['wecha_id']=$_POST['wecha_id'];
				$row2['expense']=0;
				$row2['time']=time();
				$row2['cat']=4;
				$row2['staffid']=0;
				$row2['score']=$_POST['score'];
				M('Member_card_use_record')->add($row2);
			}else{
				$this->error('积分不足');
			}*/
			
			$_POST['wecha_id'] 	= $this->wecha_id;
			$_POST['token'] 	= $this->token;
			$_POST['add_time'] 	= time();
			$_POST['score'] 	= $_POST['score'];
			$_POST['aid'] 		= $_POST['aid'] ;
			//die;
				if(M('Aaactivity_record')->add($_POST)){
						$_POST['share_key']		=	$this->getKey();
						M('Aaactivity_user')->add($_POST);
						M('Aaactivity')->where(array('id'=>$_POST['aid'],'token'=>$this->token))->setInc('joinnum',1);
					$this->success('提交成功，正在跳转，请稍候..',U('Aaactivity/my_focus',array('token'=>$_POST['token'],'wecha_id'=>$_POST['wecha_id'],'share_key'=>$_POST['share_key'])));
				}else{
					$this->error('未知错误，请稍后再试');
				}
		}else{
			$this->error('您已经报过名了！');
		}

	}
	//活动列表页面
	public function home(){
		$share_key	= $this->_get('share_key','trim');
		$where 		= array('token'=>$this->token,'is_open'=>array('eq',1),'is_audit'=>'1');
		$list_type 	= $_GET['list_type']?$this->_get('list_type','trim'):'add';
		$limit 		= '';
		$page 		= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$pageSize 	= 5;
		$count 		= M('Aaactivity')->where($where)->count();
		$first 		= ($page-1)*$pageSize;
		$last 		= $first + $pageSize;
		$limit 		= $first.','.$last;

		switch ($list_type) {
			case 'join':
				$order 	= 'joinnum desc,id desc';
				break;
			case 'add':
				$order 	= 'add_time desc,id desc';
				break;								
			default:
				$order 	= 'id desc';
				break;
		}

		$list 	= M('Aaactivity')->where($where)->order($order)->limit($limit)->select();
		foreach ($list as $key => $val) {
			$list[$key]['progress'] 		= round(($info[$key]['joinnum']/$info[$key]['minnums'])*100,2);
			$list[$key]['zanzhu']			=	($info[$key]['joinnum']*$info[$key]['backfeiyong']);
			$list[$key]['surplus']		=	($val['maxnums']-$val['joinnum']);
		}
		

		if($this->_get('ajax','intval') == ''){
			$solid 	= M('Aaactivity')->where($where)->limit(5)->field('id,pic')->select();

			$this->assign('solid',$solid);
			$this->assign('list',$list);
			$this->assign('share_key',$share_key);
			$this->display();
		}else{
			if(!empty($list)){
				echo json_encode($list);
			}else{
				echo 'undefined';
			}
		}
	}
	//我发起的活动列表
	public function my(){
		$share_key	= $this->_get('share_key','trim');
		$audit	= $this->_get('audit','trim');
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'is_user'=>'1','is_audit'=>$audit);

		$count      = M('Aaactivity')->where($where)->count();
        $Page       = new Page($count,15);
		$Page->setConfig('theme', ' %upPage% %downPage% %prePage% %nextPage% ');
 		$focus      = M('Aaactivity')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
 		foreach ($focus as $key => $val) {
			$focus[$key]['progress'] 		= round(($focus[$key]['joinnum']/$focus[$key]['minnums'])*100,2);
			$focus[$key]['zanzhu']			=	($focus[$key]['joinnum']*$focus[$key]['backfeiyong']);
			$focus[$key]['surplus']		=	($focus[$key]['maxnums']-$focus[$key]['joinnum']);
 		}
		
		if($focus['enddate'] < time()){
			$this->assign('is_over',1);
		}

		$this->assign('page',$Page->show());
		$this->assign('share_key',$share_key);
		$this->assign('focus',$focus);
		$this->display(my_sub);
	}



	//分享key  最长32
	public function getKey($length=16){
		$str = substr(md5(time().mt_rand(1000,9999)),0,$length);
		return $str;
	}
	
	
	public function footer(){

		$this->display();
	}
	
	//用户发起活动处理
	public function add(){
		
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'is_user'=>'1','is_audit'=>'0');
		$myadd      = M('Aaactivity')->where($where)->find();
		if($myadd){
			$this->error('您提交发起过活动，目前未审核完毕，请等待');
		}
		if(IS_POST){
			$_POST['token'] 	= $this->token;
			$_POST['wecha_id'] 	= $this->wecha_id; 
			$_POST['title']		= $this->_post('title','trim');
			$_POST['intro']		= $this->_post('intro','trim');
			$_POST['txtaudit']		= $this->_post('txtaudit','trim');
			$_POST['add_time'] 	= time();
			$_POST['is_user'] 	= 1;
			$_POST['is_open'] 	= 0;
			$_POST['is_audit'] 	= 0;
			$_POST['maxnums'] 	= $this->_post('maxnums','trim');
			if(D('Aaactivity')->create()){
					$id     = D('Aaactivity')->add($_POST);
					if($id){
						$this->success('添加成功',U('Aaactivity/my',array('token'=>$this->token,'wecha_id'=>$this->wecha_id)));
				}
				
			}else{			
				$this->error(D('Aaactivity')->getError());
			}
		}
		$this->display(my_add);
	}
}



?>