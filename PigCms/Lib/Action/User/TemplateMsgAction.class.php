<?php

class TemplateMsgAction extends UserAction{

	public function __construct(){
		parent::__construct();
	}
	public function index(){
// 		if(IS_POST){
// 			$data = array();
// 			$data['tempkey'] = $_REQUEST['tempkey'];
// 			$data['name'] = $_REQUEST['name'];
// 			$data['content'] = $_REQUEST['content'];
// 			$data['industry'] = $_REQUEST['industry'];
// 			$data['topcolor'] = $_REQUEST['topcolor'];
// 			$data['textcolor'] = $_REQUEST['textcolor'];
// 			$data['status'] = $_REQUEST['status'];
// 			$data['tempid'] = $_REQUEST['tempid'];
// 			foreach ($data as $key => $val){
// 				foreach ($val as $k => $v){
// 					$info[$k][$key] = $v;
// 				}
// 			}
// 			foreach ($info as $kk => $vv){
// 				if($vv['tempid'] == ''){
// 					$info[$kk]['status'] = 0;
// 				}

// 				$info[$kk]['token'] = session('token');
// 				$where = array('token'=>session('token'),'tempkey'=>$info[$kk]['tempkey']);
// 				if(M('Tempmsg')->where($where)->getField('id')){
// 					unset($info[$kk]['name']);
// 					unset($info[$kk]['content']);
// 					unset($info[$kk]['industry']);
// 					M('Tempmsg')->where($where)->save($info[$kk]);
// 				}else{
// 					M('Tempmsg')->add($info[$kk]);
// 				}
// 			}

// 			$this->success('操作成功');

			
// 		}else{

			$model = new templateNews();
			$templs = $model->templates();

			$list = M('Tempmsg')->where(array('token'=>session('token')))->order('id DESC')->select();
			foreach ($list as $temp) {
				if (isset($templs[$temp['tempkey']])) {
					unset($templs[$temp['tempkey']]);
				}
				if (empty($temp['tempid']) && $temp['topcolor'] == '#ffffff' && $temp['textcolor'] == '#ffffff') {
					M('Tempmsg')->where(array('id' => $temp['id']))->save(array('topcolor' => '#029700', 'textcolor' => '#000000'));
				}
			}
			if ($templs) {
				foreach ($templs as $key => $val) {
					unset($val['vars']);
					$val['status'] = 0;
					$val['type'] = 0;
					$val['token'] = session('token');
					$val['topcolor'] = '#029700';
					$val['textcolor'] = '#000000';
					$val['tempkey'] = $key;
					$val['tempid'] = '';
					M('Tempmsg')->add($val);
				}
				$list = M('Tempmsg')->where(array('token'=>session('token')))->select();
			}
// 			$keys = array_keys($list);
// 			$i=count($list);
// 			$j = 0;
// 			foreach ($templs as $k => $v){
// 				$dbtempls = M('Tempmsg')->where(array('token'=>session('token'),'tempkey'=>$k))->find();
// 				 if($dbtempls == ''){
// 					$list[$i]['tempkey'] = $k;
// 					$list[$i]['name'] = $v['name'];
// 					$list[$i]['content'] = $v['content'];
// 					$list[$i]['industry'] = $v['industry'];
// 					$list[$i]['topcolor'] = '#029700';
// 					$list[$i]['textcolor'] = '#000000';
// 					$list[$i]['status'] = 0;
// 					$i++;
// 				 }else{
// 				 	$list[$j]['name'] = $v['name'];
// 				 	$list[$j]['content'] = $v['content'];
// 					$list[$j]['industry'] = $v['industry'];
// 				 	$j++;
// 				 }
// 			}
			$this->assign('list',$list);
			$this->display();
// 		}
	}

	public function add()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$tempmsg = D('Tempmsg')->where(array('id' => $id, 'token' => $this->token))->find();
		$this->assign('tempmsg', $tempmsg);
		if (IS_POST) {
			$data['tempkey'] = isset($_POST['tempkey']) ? htmlspecialchars($_POST['tempkey']) : '';
			$data['tempid'] = isset($_POST['tempid']) ? htmlspecialchars($_POST['tempid']) : '';
			$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$data['content'] = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
			$data['industry'] = isset($_POST['industry']) ? htmlspecialchars($_POST['industry']) : '';
			$data['topcolor'] = isset($_POST['topcolor']) ? htmlspecialchars($_POST['topcolor']) : '#029700';
			$data['textcolor'] = isset($_POST['textcolor']) ? htmlspecialchars($_POST['textcolor']) : '#000000';
			$data['status'] = isset($_POST['status']) ? intval($_POST['status']) : 0;
			if (empty($data['tempkey'])) {
				$this->error('模板编号不能为空');
			} else {
				$check_tempmsg = D('Tempmsg')->where(array('tempkey' => $data['tempkey'], 'token' => $this->token))->find();
				if ($check_tempmsg && $id != $check_tempmsg['id']) {
					$this->error('模板编号已经存在');
				}
			}
			if (empty($data['tempid']) && $data['status'] == 1) {
				$this->error('模板ID不能为空');
			} else {
				$check_tempmsg = D('Tempmsg')->where(array('tempid' => $data['tempid'], 'token' => $this->token))->find();
				
				if ($data['tempid'] && $check_tempmsg && $id != $check_tempmsg['id']) {
					$this->error('模板ID已经存在');
				}
			}
			if (empty($data['content'])) {
				$this->error('回复内容不能为空');
			}
			if ($tempmsg) {
				D('Tempmsg')->where(array('id' => $id, 'token' => $this->token))->save($data);
				$this->success('修改模板成功', U('TemplateMsg/index'));
			} else {
				$data['token'] = $this->token;
				$data['type'] = 1;
				if (D('Tempmsg')->add($data)) {
					$this->success('新增模板成功', U('TemplateMsg/index'));
				} else {
					$this->error('新增模板失败');
				}
			}
		} else {
			$this->display();
		}
	}

	public function del()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (D('Tempmsg')->where(array('id' => $id, 'token' => $this->token))->delete()) {
			$this->success('删除成功', U('TemplateMsg/index'));
		} else {
			$this->error('删除失败', U('TemplateMsg/index'));
		}
	}
}