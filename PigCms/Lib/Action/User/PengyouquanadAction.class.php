<?php

class PengyouquanadAction extends UserAction{
	
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction("Pengyouquanad");
	}

	
	public function index(){
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token);
		$help_info   = M('Pengyouquanad')->where($where)->find();

		if(IS_POST){
			$_POST['token'] 	= $this->token;
			if(D('Pengyouquanad')->create()!=false){
				if($help_info){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('Pengyouquanad')->where($up_where)->save($_POST);
					$this->handleKeyword($this->_post('id','intval'),'Pengyouquanad',$this->_post('keyword','trim'));
					$this->success('修改成功',U('Pengyouquanad/index',array('token'=>$this->token)));
				}else{
					$id     = D('Pengyouquanad')->add($_POST);
					if($id){
						$this->handleKeyword($id,'Pengyouquanad',$this->_post('keyword','trim'));
						$this->success('添加成功',U('Pengyouquanad/index',array('token'=>$this->token)));
					}
				}
			}else{
                $this->error('创建失败');
            }
			
		}else{
			$this->assign('set',$help_info);
			$this->display();
		}
	}
	
}