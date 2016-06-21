<?php
class BannersAction extends BackAction{
	public function index(){
		$db=D('Banners');
		$where='';
		S('banners',null);
		if (!C('agent_version')){
			$case=$db->where('status=1')->limit(32)->select();
		}else {
			$case=$db->where('status=1 AND agentid=0')->limit(32)->select();
			$where=array('agentid'=>0);
		}
		S('banners',$banners);
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id DESC')->select();
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$pid=$this->_get('pid','intval');
		$this->assign('pid',$pid);
		$this->display();
	}
	
	public function edit(){
		$id=$this->_get('id','intval');
		$pid=$this->_get('pid','intval');
		$info=D('Banners')->find($id);
		$this->assign('info',$info);
		$this->assign('pid',$pid);
		$this->display('add');
	}
	
	public function add(){
		$pid=$this->_get('pid','intval');
		$this->assign('pid',$pid);
		$this->display();
	}
	
	public function chk(){
		define('ROOT_PATH', dirname(__FILE__).'/../../../../');
		$item_path = './Conf/logs/Logs/update/';
	}
	
	public function insert(){
		$db=D('Banners');
		$pid=$this->_POST('pid','intval');
		if($db->create()){
			if($db->add()){
				$this->success('操作成功',U('Banners/index',array('pid'=>$pid,'level'=>3)));
			}else{
				$this->error('操作失败',U('Banners/index',array('pid'=>$pid,'level'=>3)));
			}
		}else{
			$this->error('操作失败',U('Banners/index',array('pid'=>$pid,'level'=>3)));
		}
	}
	
	public function upsave(){
		$db= D('Banners');
		$pid=$this->_POST('pid','intval');
		if($db->create()){
			if($db->save()){
				$this->success('操作成功',U('Banners/index',array('pid'=>$pid,'level'=>3)));
			}else{
				$this->error('操作失败',U('Banners/index',array('pid'=>$pid,'level'=>3)));
			}
		}else{
			$this->error('操作失败',U('Banners/index',array('pid'=>$pid,'level'=>3)));
		}
	}
	
	public function del(){
		$id=$this->_get('id','intval');
		$pid=$this->_get('pid','intval');
		$db=D('Banners');
		if($db->delete($id)){
			$this->success('操作成功',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
		}
	}
}