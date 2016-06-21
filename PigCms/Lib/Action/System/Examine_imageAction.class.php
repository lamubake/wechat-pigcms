<?php
class Examine_imageAction extends BackAction{
	public function index(){
		$count = M('files')->count();
		$Page = new Page($count,10);
		$list = M('files')->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
 		$this->assign('page',$Page->show());
		$this->display();
	}
	public function del(){
		$id = intval($_GET['id']);
		$files = M('files')->where(array('id'=>$id))->find();
		$filename = '.'.str_replace(C('site_url'),'',$files['url']);
		/*if(copy('./tpl/System/Examine_image/weigui_bak.jpg',$filename)){
			
			$this->success('删除成功',U('System/Examine_image/index'));
		}*/
		@unlink($filename);
		$del = M('files')->where(array('id'=>$id))->delete();
		$this->success('删除成功',U('System/Examine_image/index'));
	}
	public function set(){
		$id = intval($_GET['id']);
		$files = M('files')->where(array('id'=>$id))->find();
		if($files['state'] == 1){
			$save['state'] = 0;
		}else{
			$save['state'] = 1;
		}
		$files_up = M('files')->where(array('id'=>$id))->save($save);
		$this->success('操作成功',U('System/Examine_image/index'));
	}
	public function set_all(){
		$files_id_arr = $_POST['files_id'];
		if(!is_array($files_id_arr)){
			$this->error('请选中一些图片！');
		}
		foreach($files_id_arr as $vo){
			$files = M('files')->where(array('id'=>intval($vo)))->find();
			if($files['state'] == 1){
				$save['state'] = 0;
			}else{
				$save['state'] = 1;
			}
			$files_up = M('files')->where(array('id'=>intval($vo)))->save($save);
		}
		$this->success('操作成功',U('System/Examine_image/index'));
	}
	public function info(){
		$id = intval($_GET['id']);
		$info = M('files')->where(array('id'=>$id))->find();
		$user = M('users')->where(array('id'=>$info['users_id']))->find();
		if($user == ''){
			$wxuser = M('wxuser')->where(array('token'=>$info['token']))->find();
			if($wxuser == ''){
				$info['username'] = '无';
			}else{
				$info['username'] = M('users')->where(array('id'=>$wxuser['uid']))->getField('username');
				$info['wxname'] = $wxuser['wxname'];
			}
		}else{
			$info['wxname'] = M('wxuser')->where(array('token'=>$info['token']))->getField('wxname');
			$info['wxname'] = $info['wxname']?$info['wxname']:'无';
			$info['username'] = $user['username'];
		}
		$info['wechaname'] = M('userinfo')->where(array('wecha_id'=>$info['wecha_id']))->getField('wechaname');
		$info['wechaname'] = $info['wechaname']?$info['wechaname']:'无';
		$this->assign('info',$info);
		$this->display();
	}
}
?>