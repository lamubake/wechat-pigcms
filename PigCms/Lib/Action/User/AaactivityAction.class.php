<?php
class AaactivityAction extends UserAction
{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction("Aaactivity");
	}
	
	public function index(){
		$search     = $this->_post('search','trim');
		$where      = array('token'=>$this->token);
		//$where      = array('token'=>$this->token);
		if($search){
			$where['title|keyword']  = array('like','%'.$search.'%');
		}
		
		$count      = M('Aaactivity')->where($where)->count();
		$Page       = new Page($count,15);
		
		$list 	= M('Aaactivity')->where($where1)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		if($list['is_user']){
			$this->assign('is_user',1);
		}
		
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function set(){
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'id'=>$id);
		$Aaactivity_info   = M('Aaactivity')->where($where)->find();

		if(IS_POST){
			
			$_POST['token'] 	= $this->token; 
			$_POST['statdate'] 	= strtotime($_POST['statdate']);
			$_POST['enddate'] 		= strtotime($_POST['enddate']);
			$_POST['add_time'] 	= time(); 
			
			if(D('Aaactivity')->create()){
				if($Aaactivity_info){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('Aaactivity')->where($up_where)->save($_POST);
					
					$this->handleKeyword($this->_post('id','intval'),'Aaactivity',$this->_post('keyword','trim'));
					$this->success('修改成功',U('Aaactivity/index',array('token'=>$this->token)));
				}else{
					$id     = D('Aaactivity')->add($_POST);
					if($id){
						$this->handleKeyword($id,'Aaactivity',$this->_post('keyword','trim'));
						$this->success('添加成功',U('Aaactivity/index',array('token'=>$this->token)));
					}
				}	
				
			}else{			
                $this->error(D('Aaactivity')->getError());
            }
			
		}else{
			
			$this->assign('statdate',$statdate);
			$this->assign('enddate',$enddate);
			$this->assign('set',$Aaactivity_info);
			$this->display();
		}
	}
	
	public function rank(){
		$id 	= $this->_get('id','intval');
		$where  = array('token'=>$this->token,'aid'=>$id);
		
		$count  = M('Aaactivity_user')->where($where)->count();
		$Page   = new Page($count,30);
		$list 	= M('Aaactivity_user')->where($where)->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		foreach ($list as $key => $val) {
			$user_info = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();
			
			//邀请人查询开始
			$shareuserinfo = M('Aaactivity_record')->where(array('token'=>$this->token,'aid'=>$id,'wecha_id'=>$val['wecha_id']))->field('share_key')->find();
			$shareuser = M('Aaactivity_user')->where(array('token'=>$this->token,'aid'=>$id,'share_key'=>$shareuserinfo['share_key']))->find();
			$shareuser 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$shareuser['wecha_id']))->field('truename')->find();
			//邀请人查询END

			$list[$key]['sharename'] 	= $shareuser['truename'];
			$list[$key]['username'] 	= $user_info['truename']?$user_info['truename']:'匿名';
			$list[$key]['tel'] 		= $user_info['tel']?$user_info['tel']:'无';
		}
		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	public function del(){
		$id		= $this->_get('id','intval');
		if(M('Aaactivity')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			M('Aaactivity_user')->where(array('token'=>$this->token,'pid'=>$id))->delete();
			M('Aaactivity_record')->where(array('token'=>$this->token,'pid'=>$id))->delete();	
			$this->success('删除成功');
		}
	}
	public function rank_del(){
		$id		= $this->_get('id','intval');
		if(M('Aaactivity_user')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			M('Aaactivity_record')->where(array('token'=>$this->token,'id'=>$id))->delete();
			$this->success('删除成功');
		}
	}
}
?>