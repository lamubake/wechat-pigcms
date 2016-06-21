<?php
class ImagesAction extends BackAction{
	public function index(){
		$db=D('Images');
		$where=array('agentid'=>'0');
		$list=$db->where($where)->find();
		$this->assign('list',$list);
		$pid = $this->_GET('pid','intval');
		$this->assign('pid',$pid);
		$this->display();
	}
	public function edit(){
		$id=$this->_get('id','intval');
		$pid=$this->_get('pid','intval');
		$db=D('Images');
		$where=array('agentid'=>'0');
		$list= $db->where($where)->find();
		switch($id){
			case 1:
				$title='功能介绍';
				$img=$list['fc'];
			break;
			case 2:
				$title='关于我们';
				$img=$list['about'];
			break;
			case 3:
				$title='资费说明';
				$img=$list['price'];
			break;
			case 4:
				$title='产品案例';
				$img=$list['common'];
			break;
			case 5:
				$title='管理中心';
				$img=$list['login'];
			break;
			case 6:
				$title='帮助中心';
				$img=$list['help'];
			break;
		}
		$this->assign('pid',$pid);
		$this->assign('img',$img);
		$this->assign('list',$list);
		$this->assign('id',$id);
		$this->assign('title',$title);
		$this->display('add');
	}

	public function insert(){
		$db=D('Images');
		$where=array('agentid'=>'0');
		$id=$this->_POST('mid','intval');
		$pid=$this->_POST('pid','intval');
		$img=$this->_POST('img');
		switch($id){
			case 1:
				$data['fc']=$img;
				$cc = $db->where($where)->add($data);
			break;
			case 2:
				$data['about']=$img;
				$cc = $db->where($where)->add($data);
			break;
			case 3:
				$data['price']=$img;
				$cc = $db->where($where)->add($data);
			break;
			case 4:
				$data['common']=$img;
				$cc = $db->where($where)->add($data);
			break;
			case 5:
				$data['login']=$img;
				$cc = $db->where($where)->add($data);
			break;
			case 6:
				$data['help']=$img;
				$cc = $db->where($where)->add($data);
			break;
		}
		if($cc){
			$this->success('操作成功',U('Images/index',array('pid'=>$pid,'level'=>3)));
		}else{
			$this->error('操作失败',U('Images/index',array('pid'=>$pid,'level'=>3)));
		}
	}

	public function upsave(){
		$db=D('Images');
		$where=array('agentid'=>'0');
		$id=$this->_POST('mid','intval');
		$pid=$this->_POST('pid','intval');
		$img=$this->_POST('img');
		switch($id){
			case 1:
				$data['fc']=$img;
				$cc = $db->where($where)->save($data);
			break;
			case 2:
				$data['about']=$img;
				$cc = $db->where($where)->save($data);
			break;
			case 3:
				$data['price']=$img;
				$cc = $db->where($where)->save($data);
			break;
			case 4:
				$data['common']=$img;
				$cc = $db->where($where)->save($data);
			break;
			case 5:
				$data['login']=$img;
				$cc = $db->where($where)->save($data);
			break;
			case 6:
				$data['help']=$img;
				$cc = $db->where($where)->save($data);
			break;
		}
		if($cc){
			$this->success('操作成功',U('Images/index',array('pid'=>$pid,'level'=>3)));
		}else{
			$this->error('操作失败',U('Images/index',array('pid'=>$pid,'level'=>3)));
		}
	}
}
?>