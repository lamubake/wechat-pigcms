<?php

class AdkanjiaAction extends UserAction{
	
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction("Adkanjia");
	}
	
	public function index(){
		$search     = $this->_post('search','trim');
		$where      = array('token'=>$this->token);
		if($search){
			$where['title|keyword']  = array('like','%'.$search.'%');
		}
		
		$count      = M('Adkanjia')->where($where)->count();
		$Page       = new Page($count,15);
		
		$list 	= M('Adkanjia')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function set(){
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'id'=>$id);
		$help_info   = M('Adkanjia')->where($where)->find();

		if(IS_POST){
			
			$_POST['token'] 	= $this->token; 
			$_POST['start'] 	= strtotime($_POST['start']);
			$_POST['end'] 		= strtotime($_POST['end']);
			$_POST['add_time'] 	= time(); 
			
			if(D('Adkanjia')->create()){
				if($help_info){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('Adkanjia')->where($up_where)->save($_POST);
					
					$this->handleKeyword($this->_post('id','intval'),'Adkanjia',$this->_post('keyword','trim'));
					$this->success('修改成功',U('Adkanjia/index',array('token'=>$this->token)));
				}else{
					$id     = D('Adkanjia')->add($_POST);
					if($id){
						$this->handleKeyword($id,'Adkanjia',$this->_post('keyword','trim'));
						$this->success('添加成功',U('Adkanjia/index',array('token'=>$this->token)));
					}
				}	
				
			}else{			
                $this->error(D('Adkanjia')->getError());
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

		$count  = M('Adkanjia_user')->where($where)->count();
		$Page   = new Page($count,15);
		$list 	= M('Adkanjia_user')->where($where)->order('help_count desc,add_time asc')->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($list as $key => $val) {
			$user_info = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();

			$list[$key]['nickname'] 	= $user_info['wechaname']?$user_info['wechaname']:'无';
			$list[$key]['username'] 	= $user_info['truename']?$user_info['truename']:'匿名';
			$list[$key]['mobile'] 		= $user_info['tel']?$user_info['tel']:'无';
		}

		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	public function del(){
		$id		= $this->_get('id','intval');
		if(M('Adkanjia')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			M('Adkanjia_user')->where(array('token'=>$this->token,'pid'=>$id))->delete();	
			$this->success('删除成功');
		}
	}
	public function rank_del(){
		$id		= $this->_get('id','intval');
		if(M('Adkanjia_user')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			$this->success('删除成功');
		}
	}
}