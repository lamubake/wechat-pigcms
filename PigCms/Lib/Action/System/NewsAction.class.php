<?php
class NewsAction extends BackAction{
	public function index(){
		$db=D('News');
		if (!C('agent_version')){
			$case=$db->where('status=1')->select();
		}else {
			$case=$db->where('status=1 AND agentid=0')->select();
			$where=array('agentid'=>0);
		}
		S('News',$News);
		$info=$db->where($where)->find();
		$wxname=$info['wxname'];
		$this->assign('wxname',$wxname);
		$this->assign('info',$info);
		$wx = D('Wxuser');
		$tok = $wx->where(array('weixin'=>$wxname))->getField('token');
		$cla = D('Classify');
		$class = $cla->where(array('token'=>$tok))->select();
		$this->assign('tok',$tok);
		$this->assign('class',$class);
		$pid=$this->_get('pid','intval');
		$this->assign('pid',$pid);
		$this->display();
	}
	public function upsave(){
		$db = M('Classify');
		$pid = $this->_POST('pid','intval');
		$wx = $this->_post('name');
		$class1 = $data['class1'] = $this->_post('class1','int');
		$class2 = $data['class2'] = $this->_post('class2','int');
		$class3 = $data['class3'] = $this->_post('class3','int');
		$token =$this->_post('tok');
		$data['name1']=$db->where(array('id'=>$class1))->getField('name');
		$data['name2']=$db->where(array('id'=>$class2))->getField('name');
		$data['name3']=$db->where(array('id'=>$class3))->getField('name');
		$db1 = D('News');
		$list = $db1->where(array('token'=>$token,''=>$wx))->save($data);
		if($list){
			$this->success('新闻信息设置成功',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
		}else{
			$this->error('新闻信息设置失败',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
		}
	}
	public function insert(){
		$db=D('News');
		$wxname = $this->_post('new');
		$pid=$this->_POST('pid','intval');
		$db1 = D('Wxuser');
		$token = $db1->where(array('weixin'=>$wxname))->getField('token');
		if (empty($token)){
			$this->error('公众号不存在',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
		}
		$data['wxname'] = $wxname;
		$data['token'] = $token;
		$data['agentid'] = 0;
		$list = $db->where(array('agentid'=>0))->find();
		if($list == ''){
			if($db->add($data)){
				$this->success('公众号设置成功',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
			}else{
				$this->success('公众号设置失败',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
			}
		}else{
			$sa=$db->where(array('agentid'=>0))->save($data);
			if($sa){
				$this->success('操作成功',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
			}else{
				$this->success('操作失败',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
			}
		}
		
	}
	public function del(){
		$wxname=$this->_get('wxname');
		$pid=$this->_get('pid','intval');
		$db=D('News');
		$list = $db->where(array('wxname'=>$wxname))->delete();
		if($list){
			$this->success('公众号已退出',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
		}else{
			$this->error('公众号退出失败',U(MODULE_NAME.'/index',array('pid'=>$pid,'level'=>3)));
		}
	}
}