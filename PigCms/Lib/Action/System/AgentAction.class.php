<?php
class AgentAction extends BackAction{
	public $agent_db;
	public function _initialize() {
		parent::_initialize();
		$this->agent_db=M('Agent');
	}
	public function index(){
		//
		$firstNode=M('Node')->where(array('pid'=>1,'title'=>'代理商管理'))->order('id ASC')->find();
		$nodeExist=M('Node')->where(array('pid'=>$firstNode['id'],'title'=>'优惠套餐包'))->find();
		if (!$nodeExist){
			$submenu=array(
			'name'=>'AgentPrice',
			'title'=>'优惠套餐包',
			'status'=>1,
			'remark'=>'0',
			'pid'=>$firstNode['id'],
			'level'=>2,
			'sort'=>0,
			'display'=>2
			);
			$submenuRowID=M('Node')->add($submenu);
			//
			$row2=array(
			'name'=>'add',
			'title'=>'添加',
			'status'=>1,
			'remark'=>'0',
			'pid'=>$submenuRowID,
			'level'=>3,
			'sort'=>0,
			'display'=>2
			);
			M('Node')->add($row2);
		}
		$buyRecordNode=M('Node')->where(array('pid'=>$firstNode['id'],'title'=>'消费记录'))->find();
		if (!$buyRecordNode){
			$submenu=array(
			'name'=>'AgentBuyRecords',
			'title'=>'消费记录',
			'status'=>1,
			'remark'=>'0',
			'pid'=>$firstNode['id'],
			'level'=>2,
			'sort'=>0,
			'display'=>2
			);
			$submenuRowID=M('Node')->add($submenu);
		}
		//
		$count=$this->agent_db->count();
		$page=new Page($count,20);
		$agentWhere = array('level'=>0);
		if ($_GET['agentid']){
			$agentWhere['level'] = (int)$_GET['agentid'];
		}
		
		$info=$this->agent_db->where($agentWhere)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$i=0;
		$version = updateSync::version();//获取版本号
		if ($info){
			foreach ($info as $item){
				$info[$i]['usercount']=M('Users')->where(array('agentid'=>intval($item['id'])))->count();
				$info[$i]['wxusercount']=M('Wxuser')->where(array('agentid'=>intval($item['id'])))->count();
				$info[$i]['level'] = $version == 5 ? $item['level'] : 1;
				$i++;
			}
		}
		$this->assign('info',$info);
		$this->assign('page',$page->show());
		$this->display();
	}
	public function add(){
		
		if (isset($_GET['id'])){
			$thisAgent=$this->agent_db->where(array('id'=>intval($_GET['id'])))->find();
		}
		if(isset($_POST['dosubmit'])) {
			$_POST['siteurl']=trim($_POST['siteurl']);
			if (!strpos($_POST['siteurl'],'ttp://')){
				$this->error('站点地址必须包含http://');
			}else {
				if (substr($_POST['siteurl'],-1,1)=='/'){
					$this->error('站点地址的最后一个字符不能有“/”');
				}
			}
			
			$agent = $this->agent_db->where(array('name' => htmlspecialchars($_POST['name'])))->find();
			if ($agent && $agent['id'] != intval($_GET['id'])) {
				$this->error('该名称已经存在，请更换其他名称');
			}
			
			if($_GET['id'] == ''){
				$ht=$this->agent_db->where(array('siteurl'=>$_POST['siteurl']))->find();
				if($ht != ''){
					$this->error('重复的代理商站点地址');
				}
			}else{
				$wheres['id'] = array('neq',$_GET['id']);
				$wheres['siteurl'] = $_POST['siteurl'];
				$ht=$this->agent_db->where($wheres)->count();
				if($ht != 0){
					$this->error('重复的代理商站点地址');
				}
			}
			if($_POST['siteurl'] == 'http://'.$_SERVER['SERVER_NAME']){
				$this->error('代理商站点地址不能与主站地址相同');
			}
			if (strlen($_POST['password'])){
				$password = trim($_POST['password']);
				$salt=rand(111111,999999);
				$_POST['salt']=$salt;
				$password=md5(md5($password).$salt);
				$_POST['password']=$password;
			}else {
				if ($thisAgent){
					unset($_POST['password']);
				}else {
					$this->error('请设置密码!');
				}
			}
			if (!$thisAgent){
				$_POST['time']=time();
			}
			$_POST['endtime']=strtotime($_POST['endtime']);
			if($this->agent_db->create()){
				if ($thisAgent){
					$this->agent_db->where(array('id'=>$thisAgent['id']))->save($_POST);
					$this->success('修改成功！',U('Agent/index'));
				}else {
					$agentid = $this->agent_db->add();
					if($agentid){
						$this->success('添加成功！',U('Agent/index'));
					}else{
						$this->error('添加失败!');
					}
				}
			}else{
				$this->error($this->agent_db->getError());
			}
		}else{
			if (!$thisAgent){
				$thisAgent['endtime']=time()+365*24*3600;
			}
			$this->assign('info',$thisAgent);
			$this->display();
		}
	}

	public function del(){
		$id=$this->_get('id','intval');
		if($this->agent_db->delete($id)){
			$this->success('操作成功',$_SERVER['HTTP_REFERER']);
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}

	public function deleteUser(){
		$id=intval($_GET['id']);
		$thisUser=M('Users')->where(array('agentid'=>$this->thisAgent['id'],'id'=>$id))->find();
		if (!$thisUser){
			$this->error('没有此用户');
		}
		$rt=M('Users')->where(array('id'=>$id))->delete();
		if ($rt){
			M('Agent')->where($this->agentWhere)->setDec('usercount');
			M('Wxuser')->where(array('uid'=>$id))->delete();
		}
		$this->success('删除成功！',U('Users/index'));
	}

	public function deleteWxUser(){
		$id=intval($_GET['id']);
		$thisUser=M('Wxuser')->where(array('agentid'=>$this->thisAgent['id'],'id'=>$id))->find();
		if (!$thisUser){
			$this->error('没有此公众号');
		}
		$rt=M('Wxuser')->where(array('id'=>$id))->delete();
		M('Agent')->where($this->agentWhere)->setDec('wxusercount');
		$this->success('删除成功！',U('Users/wxusers'));
	}
	
}