<?php
class FunctionAction extends BackAction{
	public function index(){
		$map = array();
		$UserDB = D('Function');
		if(!ALI_FUWU_GROUP){
			$map['funname']  = array('neq','Fuwu');
		}
		$count = $UserDB->where($map)->count();
		$Page       = new Page($count,30);// 实例化分页类 传入总记录数
		// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$show       = $Page->show();// 分页显示输出
		$list = $UserDB->where($map)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
		
		
	}
	public function add(){
		if(IS_POST){

			$this->all_insert();
		}else{
			$map=array('status'=>1);
			if (C('agent_version')){
				$map['agentid']=array('lt',1);
			}
			$group=D('User_group')->getAllGroup($map);
			$this->assign('group',$group);
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$this->all_save();
		}else{
			$map=array('status'=>1);
			if (C('agent_version')){
				$map['agentid']=array('lt',1);
			}
			$id=$this->_get('id','intval',0);
			if($id==0)$this->error('非法操作');
			$this->assign('tpltitle','编辑');
			$fun=M('Function')->where(array('id'=>$id))->find();
			$this->assign('info',$fun);
			$group=D('User_group')->getAllGroup($map);
			$this->assign('group',$group);
			$this->display('add');
		}
	}	
	public function del(){
		if(IS_POST){
			$this->all_save();
		}else{
			$id=$this->_get('id','intval',0);
			if($id==0)$this->error('非法操作');
			$this->assign('tpltitle','编辑');
			$fun=M('Function')->where(array('id'=>$id))->delete();
			if($fun==false){
				$this->error('删除失败');
			}else{
				$this->success('删除成功');
			}
		}
	}
	public function search(){
		$name=htmlspecialchars(strip_tags($_POST['name']));
		$where=array();
		$where['name']=array("like","%$name%");
		$where['funname']=array("like","%$name%");
		$where['info']=array("like","%$name%");
		$where['_logic']='or';
		$count=M('Function')->where($where)->count();
		$Page  = new Page($count,30);
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$show       = $Page->show();// 分页显示输出
		$list = M('Function')->where($where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		$this->display('index');
	}
}
?>