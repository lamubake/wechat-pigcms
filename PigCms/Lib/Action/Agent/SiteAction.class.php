<?php
class SiteAction extends AgentAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		if (IS_POST){
			if (isset($_POST['statisticcode'])){
				$_POST['statisticcode']=base64_encode($_POST['statisticcode']);
			}
			if($this->agent_db->create()){
				$this->agent_db->where(array('id'=>$this->thisAgent['id']))->save($_POST);
				$this->success('修改成功！',U('Site/'.ACTION_NAME));
			}else{
				$this->error($this->agent_db->getError());
			}
		}else {
			$this->display();
		}
	}
	public function regConfig(){
		if (IS_POST){
			if($this->agent_db->create()){
				$this->agent_db->where(array('id'=>$this->thisAgent['id']))->save($_POST);

				$this->success('修改成功！');
			}else{
				$this->error($this->agent_db->getError());
			}
		}else {
			$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups',$groups);
			$this->display();
		}
	}
	public function functions(){
		$db=M('Function');
		$agent_function_db=M('Agent_function');
		//初始化
		$baseFunctions=$db->select();
		$functions=$agent_function_db->where($this->agentWhere)->order('id ASC')->select();
		$baseFunctionsByFunname=array();
		if ($baseFunctions){
			foreach ($baseFunctions as $bf){
				$baseFunctionsByFunname[trim($bf['funname'])]=$bf;
			}
		}
		$functionsByFunname=array();
		//
		$existFunName=array();
		
		$functionNames=array();
		if ($functions){
			foreach ($functions as $f){
				array_push($functionNames,$f['funname']);
			}
		}
		if ($functions){
			foreach ($functions as $f){
				$f['funname']=trim($f['funname']);
				$functionsByFunname[$f['funname']]=$f;
				//
				if (!key_exists($f['funname'],$baseFunctionsByFunname)||in_array($f['funname'],$existFunName)){
					$agent_function_db->where(array('funname'=>$f['funname']))->delete();
				}
				array_push($existFunName,$f['funname']);
			}
		}
		
		if ($baseFunctions){
			foreach ($baseFunctions as $bf2){
				if (!in_array($bf2['funname'],$functionNames)){
					$bf2['agentid']=$this->thisAgent['id'];
					unset($bf2['id']);
					$agent_function_db->add($bf2);
				}
			}
		}
		//
		$count      = $agent_function_db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$agent_function_db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		//
		$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
		$groupsByID=array();
		if ($groups){
			foreach ($groups as $g){
				$groupsByID[$g['id']]=$g;
			}
		}
		if ($list){
			$i=0;
			foreach ($list as $item){
				$list[$i]['groupName']=$groupsByID[$item['gid']]['name'];
				$list[$i]['info']=str_replace('pigcms','',$item['info']);
				$i++;
			}
		}
		//
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function funSet(){
		$thisFunction=M('Agent_function')->where(array('id'=>intval($_GET['id'])))->find();
		$db=M('Agent_function');
		$thisFun=$db->where(array('funname'=>$thisFunction['funname'],'agentid'=>$this->thisAgent['id']))->find();
		if (IS_POST){
			if($db->create()){
				$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
				$this->success('修改成功！',U('Site/functions'));
			}else{
				$this->error($this->agent_db->getError());
			}
		}else {
			$groups=M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups',$groups);
			$this->assign('info',$thisFun);
			$this->display();
		}
	}
	public function links(){
		$db=M('Links');
		//
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		//
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function setLink(){
		$db=M('Links');
		if (IS_POST){
			if (isset($_POST['id'])&&intval($_POST['id'])){
				if($db->create()){
					$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！',U('Site/links'));
				}
			}else {
				if($db->create()){
					$db->add($_POST);
					$this->success('添加成功！',U('Site/links'));
				}
			}
		}else {
			if (isset($_GET['id'])){
				$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
				if (!$thisItem){
					$this->error('记录不存在');
				}
				$this->assign('info',$thisItem);
			}
			$this->display();
		}
	}
	public function deleteLink(){
		$db=M('Links');
		$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
		if (!$thisItem){
			$this->error('记录不存在');
		}
		$db->where(array('id'=>$thisItem['id']))->delete();
		$this->success('删除成功！',U('Site/links'));
	}
	public function cases(){
		$db=M('case');
		//
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		//
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function setCase(){
		$db=M('case');
		$dbs=M('caseclass');
		if (IS_POST){
			$cid = $this->_POST('class');
			$_POST['classid']=$dbs->where(array('id'=>$cid))->getField('name');
			if (isset($_POST['id'])&&intval($_POST['id'])){
				if($db->create()){
					$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！',U('Site/cases'));
				}
			}else {
				if($db->create()){
					$db->add($_POST);
					$this->success('添加成功！',U('Site/cases'));
				}
			}
		}else {
			if (isset($_GET['id'])){
				$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
				if (!$thisItem){
					$this->error('记录不存在');
				}
				$this->assign('info',$thisItem);
			}
			$list=$dbs->where(array('agentid'=>$this->thisAgent['id']))->select();
			$this->assign('list',$list);
			$this->display();
		}
	}
	public function deleteCase(){
		$db=M('case');
		$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
		if (!$thisItem){
			$this->error('记录不存在');
		}
		$db->where(array('id'=>$thisItem['id']))->delete();
		$this->success('删除成功！',U('Site/cases'));
	}
	public function addclass(){
		$db=M('caseclass');
		$dbs=M('case');
		if (IS_POST){
			if (isset($_POST['id'])&&intval($_POST['id'])){
				$data['classid']=$this->_post('name');
				$dbs->where(array('class'=>intval($_POST['id'])))->save($data);
				if($db->create()){
					$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！',U('Site/caseclass'));
				}
			}else {
				if($db->create()){
					$db->add($_POST);
					$this->success('添加成功！',U('Site/caseclass'));
				}
			}
		}else {
			if (isset($_GET['id'])){
				$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
				if (!$thisItem){
					$this->error('记录不存在');
				}
				$this->assign('info',$thisItem);
			}
			$this->display();
		}
	}
	public function caseclass(){
		$db=M('caseclass');
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function deleteclass(){
		$db=M('caseclass');
		$dbs=M('case');
		$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
		if (!$thisItem){
			$this->error('记录不存在');
		}
		$db->where(array('id'=>$thisItem['id']))->delete();
		$data['classid']='';
		$dbs->where(array('class'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->save($data);
		$this->success('删除成功！',U('Site/caseclass'));
	}
	public function news(){
		$db=D('News');
		$agentid=$this->thisAgent['id'];
		$info=$db->where(array('agentid'=>$agentid))->find();
		$wxname=$info['wxname'];
		$this->assign('wxname',$wxname);
		$this->assign('info',$info);
		$wx = D('Wxuser');
		$tok = $wx->where(array('weixin'=>$wxname))->getField('token');
		$cla = D('Classify');
		$class = $cla->where(array('token'=>$tok))->select();
		$this->assign('tok',$tok);
		$this->assign('class',$class);
		$this->assign('agentid',$agentid);
		$this->display();
	}
	public function newsset(){
		$db=D('News');
		$wxname = $this->_post('new');
		$agentid=$this->_post('agentid','intval');
		$db1 = D('Wxuser');
		$token = $db1->where(array('weixin'=>$wxname,'agentid'=>$agentid))->getField('token');
		if(!$token){
			$this->error('请设置正确公众号',U('Site/news'));
		}
		$data['wxname'] = $wxname;
		$data['token'] = $token;
		$data['agentid']=$agentid;
		if($db->add($data)){
			$this->success('公众号设置成功',U('Site/news'));
		}
	}
	public function newsdel(){
		$wxname=$this->_get('wxname');
		$agentid=$this->_get('agentid','intval');
		$db=D('News');
		$list = $db->where(array('wxname'=>$wxname,'agentid'=>$agentid))->delete();
		if($list){
			$this->success('公众号已退出',U('Site/news'));
		}else{
			$this->error('公众号退出失败',U('Site/news'));
		}
	}
	public function newsup(){
		$db = M('Classify');
		$agentid = $this->_POST('agentid','intval');
		$wx = $this->_post('name');
		$class1 = $data['class1'] = $this->_post('class1','int');
		$class2 = $data['class2'] = $this->_post('class2','int');
		$class3 = $data['class3'] = $this->_post('class3','int');
		$token =$this->_post('tok');
		$data['name1']=$db->where(array('id'=>$class1))->getField('name');
		$data['name2']=$db->where(array('id'=>$class2))->getField('name');
		$data['name3']=$db->where(array('id'=>$class3))->getField('name');
		$db1 = D('News');
		$list = $db1->where(array('token'=>$token,'agentid'=>$agentid))->save($data);
		if($list != 0){
			$this->success('新闻信息设置成功',U('Site/news'));
		}else{
			$this->error('新闻信息设置失败',U('Site/news'));
		}
	}
	public function banner(){
		$db=D('Banners');
		$count      = $db->where($this->agentWhere)->count();
		$Page       = new Page($count,6);
		$show       = $Page->show();
		$list=$db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->display();
	}
	public function setBanner(){
		$db=M('Banners');
		if (IS_POST){
			if (isset($_POST['id'])&&intval($_POST['id'])){
				if($db->create()){
					$db->where(array('id'=>intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！',U('Site/banner'));
				}
			}else {
				if($db->create()){
					$db->add($_POST);
					$this->success('添加成功！',U('Site/banner'));
				}
			}
		}else {
			if (isset($_GET['id'])){
				$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
				if (!$thisItem){
					$this->error('记录不存在');
				}
				$this->assign('info',$thisItem);
			}
			$this->display();
		}
	}
	public function deleteBanner(){
		$db=M('Banners');
		$thisItem=$db->where(array('id'=>intval($_GET['id']),'agentid'=>$this->thisAgent['id']))->find();
		if (!$thisItem){
			$this->error('记录不存在');
		}
		$db->where(array('id'=>$thisItem['id']))->delete();
		$this->success('删除成功！',U('Site/banner'));
	}
	public function images(){
		$db=M('Images');
		$list=$db->where($this->agentWhere)->find();
		$id=$this->thisAgent['id'];
		$this->assign('id',$id);
		$this->assign('list',$list);
		$this->display();
	}
	public function setImages(){
		$db=M('Images');
		$cid=$this->_get('cid','intval');
		$info=$db->where($this->agentWhere)->find;
		switch($cid){
			case 1:
				$title='功能介绍';
				$img=$db->where($this->agentWhere)->getField('fc');
			break;
			case 2:
				$title='关于我们';
				$img=$db->where($this->agentWhere)->getField('about');
			break;
			case 3:
				$title='资费说明';
				$img=$db->where($this->agentWhere)->getField('price');
			break;
			case 4:
				$title='产品案例';
				$img=$db->where($this->agentWhere)->getField('common');
			break;
			case 5:
				$title='管理中心';
				$img=$db->where($this->agentWhere)->getField('login');
			break;
			case 6:
				$title='帮助中心';
				$img=$db->where($this->agentWhere)->getField('help');
			break;
		}
		$this->assign('img',$img);
		$this->assign('cid',$cid);
		$this->assign('info',$info);
		$this->assign('title',$title);
		$this->display();
	}
	public function addImages(){
		$db=D('Images');
		$where=$this->agentWhere;
		$id=$this->_POST('id','intval');
		$img=$this->_POST('img');
		$cou=$db->where($where)->count();
		switch($id){
			case 1:
				$data['fc']=$img;
			break;
			case 2:
				$data['about']=$img;
			break;
			case 3:
				$data['price']=$img;
			break;
			case 4:
				$data['common']=$img;
			break;
			case 5:
				$data['login']=$img;
			break;
			case 6:
				$data['help']=$img;
			break;
		}
		if($cou == '0'){
			$data['agentid']=$this->_POST('agentid','intval');
			$cc=$db->add($data);
		}else{
			$cc=$db->where($where)->save($data);
		}
		if($cc){
			$this->success('操作成功',U('Site/images'));
		}else{
			$this->error('操作失败',U('Site/images'));
		}
	}
	public function customs(){
		$agent=M('Agent');
		$agentid=$this->agentid;
		$nav=$agent->where(array('id'=>$agentid))->getField('isnav');
		if($nav != '0'){
			$this->error('商家不允许该账号设置自定义菜单');
		}
		$db = M('Customs');
		$fc = $db->where(array('agentid'=>$agentid,'type'=>'1'))->find();
		$about = $db->where(array('agentid'=>$agentid,'type'=>'2'))->find();
		$price = $db->where(array('agentid'=>$agentid,'type'=>'3'))->find();
		$common = $db->where(array('agentid'=>$agentid,'type'=>'4'))->find();
		$login = $db->where(array('agentid'=>$agentid,'type'=>'5'))->find();
		$help = $db->where(array('agentid'=>$agentid,'type'=>'6'))->find();
		$this->assign('fc',$fc);
		$this->assign('about',$about);
		$this->assign('price',$price);
		$this->assign('common',$common);
		$this->assign('login',$login);
		$this->assign('help',$help);
		$this->display();
	}
	public function setcustoms(){
		$id=$this->_get('id','intval');
		$this->assign('id',$id);
		$db=M('Customs');
		$agentid=$this->agentid;
		if(IS_POST){
			$cid=$this->_POST('id');
			$data['name']=$this->_POST('name');
			$data['url']=$this->_POST('url');
			$data['open']=$this->_POST('open','intval');
			$data['dspl']=$this->_POST('dspl','intval');
			$data['type']=$this->_POST('id','intval');
			$data['agentid']=$this->agentid;
			$cc=$db->where(array('agentid'=>$agentid,'type'=>$cid))->find();
			if($cc == ''){
				$tj=$db->add($data);
			}else{
				$tj=$db->where(array('agentid'=>$agentid,'type'=>$cid))->save($data);
			}
			if($tj){
				$this->success('设置成功',U('Site/customs'));
				exit;
			}else{
				$this->error('设置失败');
			}
		}
		switch($id){
			case 1:
				$names='功能介绍';	
			break;
			case 2:
				$names='关于我们';
			break;
			case 3:
				$names='资费说明';
			break;
			case 4:
				$names='产品案例';
			break;
			case 5:
				$names='管理中心';
			break;
			case 6:
				$names='帮助中心';
			break;
		}
		$list=$db->where(array('agentid'=>$agentid,'type'=>$id))->find();
		$this->assign('names',$names);
		$this->assign('list',$list);
		$this->display();
	}
	
	public function weixin()
	{
		$this->agentid;
		$account = D('Weixin_account')->where(array('agentid' => $this->agentid))->find();
		$this->assign('account', $account);
		if (IS_POST){
			$data['appId'] = isset($_POST['appId']) ? htmlspecialchars($_POST['appId']) : '';
			$data['appSecret'] = isset($_POST['appSecret']) ? htmlspecialchars($_POST['appSecret']) : '';
			$data['token'] = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
			$data['encodingAesKey'] = isset($_POST['encodingAesKey']) ? htmlspecialchars($_POST['encodingAesKey']) : '';
			$data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
			if ($account) {
				D('Weixin_account')->where(array('agentid' => $this->agentid))->save($data);
			} else {
				$data['agentid'] = $this->agentid;
				D('Weixin_account')->add($data);
			}
			$this->success('操作成功！',U('Site/weixin'));
		}else {
			$this->display();
		}
	}
}


?>