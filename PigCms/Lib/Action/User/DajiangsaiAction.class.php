<?php

class DajiangsaiAction extends UserAction{
	
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction("Dajiangsai");
	}
	
	public function index(){
		$search     = $this->_post('search','trim');
		$where      = array('token'=>$this->token);
		if($search){
			$where['title|keyword']  = array('like','%'.$search.'%');
		}
		
		$count      = M('Dajiangsai')->where($where)->count();
		$Page       = new Page($count,15);
		
		$list 	= M('Dajiangsai')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function set(){
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'id'=>$id);
		$help_info   = M('Dajiangsai')->where($where)->find();

		if(IS_POST){
			
			$_POST['token'] 	= $this->token; 
			$_POST['start'] 	= strtotime($_POST['start']);
			$_POST['end'] 		= strtotime($_POST['end']);
			$_POST['add_time'] 	= time(); 
			
			if(D('Dajiangsai')->create()){
				if($help_info){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('Dajiangsai')->where($up_where)->save($_POST);
					
					$this->handleKeyword($this->_post('id','intval'),'Dajiangsai',$this->_post('keyword','trim'));
					$this->success('修改成功',U('Dajiangsai/index',array('token'=>$this->token)));
				}else{
					$id     = D('Dajiangsai')->add($_POST);
					if($id){
						$this->handleKeyword($id,'Dajiangsai',$this->_post('keyword','trim'));
						$this->success('添加成功',U('Dajiangsai/index',array('token'=>$this->token)));
					}
				}	
				
			}else{			
                $this->error(D('Dajiangsai')->getError());
            }
			
		}else{
			
			$this->assign('start_date',date('Y-m-d',time()));
			$this->assign('end_date',date('Y-m-d',strtotime('+1 month')));
			$this->assign('set',$help_info);
			$this->display();
		}
	}
	
	
	public function rank(){
		$id 	= $this->_get('id','intval');
		$where  = array('token'=>$this->token,'pid'=>$id);
		
		$count  = M('Dajiangsai_user')->where($where)->count();
		$Page   = new Page($count,30);
		$list 	= M('Dajiangsai_user')->where($where)->order('help_count desc,add_time asc')->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($list as $key => $val) {
			$user_info = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();
			//判断是否关注
			$issub = M('Wechat_group_list')->where(array('token'=>$this->token,'openid'=>$val['wecha_id']))->find();
			if($issub){
				$list[$key]['sub'] 		= '1';
			}
				
			//邀请人查询开始
			$shareuserinfo = M('Dajiangsai_record')->where(array('token'=>$this->token,'pid'=>$id,'wecha_id'=>$val['wecha_id']))->field('share_key')->find();
			$shareuser = M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$id,'share_key'=>$shareuserinfo['share_key']))->find();
			$shareuser 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$shareuser['wecha_id']))->field('wechaname')->find();
			//邀请人查询END

			$list[$key]['sharename'] 	= $shareuser['wechaname'];
			$list[$key]['nickname'] 	= $user_info['wechaname']?$user_info['wechaname']:'无';
			$list[$key]['username'] 	= $user_info['truename']?$user_info['truename']:'匿名';
			$list[$key]['mobile'] 		= $user_info['tel']?$user_info['tel']:'无';
			$list[$key]['help_count'] 	= $val['help_count'];
			$list[$key]['sharecount']  	= M('Share')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id'],'moduleid'=>$id,'module'=>'Dajiangsai'))->count();
			$list[$key]['number'] 	= round((($list[$key]['help_count']-1)/$list[$key]['sharecount']*100),2);
		}
		$this->assign('pid',$id);
		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	public function rankid(){
		$pid 	= $this->_get('pid','intval');
		$share_key 	= $this->_get('share_key','trim');
		$where  = array('token'=>$this->token,'pid'=>$pid,'share_key'=>$share_key);
		
		//查询当前用户名
		$shareuser = M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$pid,'share_key'=>$share_key))->find();
		$sharename = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$shareuser['wecha_id']))->find();
		$sharename 	= $sharename['truename']?$sharename['truename']:'匿名';
		$this->assign('sharename',$sharename);
		
		$count  = M('Dajiangsai_record')->where($where)->count();
		$Page   = new Page($count,30);
		$list 	= M('Dajiangsai_record')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($list as $key => $val) {
			//判断关注
			$issub = M('Wechat_group_list')->where(array('token'=>$this->token,'openid'=>$val['wecha_id']))->find();
			
			//查询为他助理名单信息
			$user_info = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();
			$list[$key]['username'] 	= $user_info['truename']?$user_info['truename']:'匿名';
			$list[$key]['mobile'] 		= $user_info['tel']?$user_info['tel']:'无';
			$list[$key]['time'] 		= $val['addtime'];
			$list[$key]['userid'] 		= $user_info['id'];
				if($issub){
				$list[$key]['sub'] 		= '1';
				}
	
		}
		
		//dump($sharename);
		//die;
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	public function del(){
		$id		= $this->_get('id','intval');
		if(M('Dajiangsai')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			M('Dajiangsai_user')->where(array('token'=>$this->token,'pid'=>$id))->delete();	
			$this->success('删除成功');
		}
	}
	public function rank_del(){
		$id		= $this->_get('id','intval');
		if(M('Dajiangsai_user')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			$this->success('删除成功');
		}
	}
}