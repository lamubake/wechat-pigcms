<?php
class SusceptibleAction extends BackAction{
	public function index(){
		if(!empty($_GET['word'])){
			$where['word'] = array("like","%".$_GET['word']."%");
			$where_page['word'] = $_GET['word'];
		}
		$count = M('Susceptible')->where($where)->count();
		$Page = new Page($count,50);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$list = M('Susceptible')->where($where)->order('state desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
 		$this->assign('page',$Page->show());
		$this->display();
	}
	public function set(){
		$id = intval($_GET['id']);
		$word = M('Susceptible')->where(array('id'=>$id))->find();
		if($word['state'] == 1){
			$save['state'] = 0;
		}else{
			$save['state'] = 1;
		}
		$save['time'] = time();
		$word_up = M('Susceptible')->where(array('id'=>$id))->save($save);
		S('Susceptible'.C('site_url'),null);
		$this->success('操作成功',U('System/Susceptible/index',array('word'=>$_GET['word'],'p'=>$_GET['p'])));
	}
	public function set_all(){
		if(intval($_GET['all']) == 1){
			$word_up = M('Susceptible')->where(array('state'=>0))->save(array('state'=>1));
		}else if(intval($_GET['all']) == 2){
			$word_up = M('Susceptible')->where(array('state'=>1))->save(array('state'=>0));
		}else{
			$word_id_arr = $_POST['word_id'];
			if(!is_array($word_id_arr)){
				$this->error('请选中一些敏感词！');
			}
			foreach($word_id_arr as $vo){
				$word = M('Susceptible')->where(array('id'=>intval($vo)))->find();
				if($_POST['set_all'] == 1){
					$save['state'] = 1;
				}else{
					$save['state'] = 0;
				}
				$save['time'] = time();
				$word_up = M('Susceptible')->where(array('id'=>intval($vo)))->save($save);
			}
		}
		S('Susceptible'.C('site_url'),null);
		$this->success('操作成功',U('System/Susceptible/index',array('word'=>$_GET['word'],'p'=>$_GET['p'])));
	}
	public function del(){
		$id = intval($_GET['id']);
		$del = M('Susceptible')->where(array('id'=>$id))->delete();
		S('Susceptible'.C('site_url'),null);
		$this->success('删除成功',U('System/Susceptible/index',array('word'=>$_GET['word'],'p'=>$_GET['p'])));
	}
	public function edit(){
		if(IS_POST){
			$id = intval($_POST['id']);
			$_POST['word'] = strip_tags($_POST['word']);
			$exp="/[\\pP]/u";
			preg_match_all($exp,$_POST['word'],$math);
			if(count($math[0]) > 0){
				$this->error('不能有标点符号！');
			}
			if(is_numeric($_POST['word'])){
				$this->error('不能只写数字！');
			}
			$word_num1 = strlen($_POST['word']);
			if($word_num1 == 1){
				$this->error('不能写单个字母！');
			}
			$word_num2 = mb_strlen($_POST['word'],'utf8');
			if($word_num2 > 50){
				$this->error('不能超过50个字！');
			}
			$find_word = M('Susceptible')->where(array('id'=>$id))->find();
			$find_word_post = M('Susceptible')->where(array('word'=>$_POST['word']))->find();
			if($find_word_post != '' && $find_word_post['word'] != $find_word['word']){
				$this->error('敏感词已存在！');
			}
			$save['word'] = $_POST['word'];
			$save['state'] = $_POST['state'];
			$save['time'] = time();
			$word_up = M('Susceptible')->where(array('id'=>$id))->save($save);
			S('Susceptible'.C('site_url'),null);
			$this->success('修改成功',U('System/Susceptible/index',array('word'=>$_GET['word'],'p'=>$_GET['p'])));
		}else{
			$id = intval($_GET['id']);
			$word = M('Susceptible')->where(array('id'=>$id))->find();
			$this->assign('info',$word);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$_POST['word'] = strip_tags($_POST['word']);
			$exp="/[\\pP]/u";
			preg_match_all($exp,$_POST['word'],$math);
			if(count($math[0]) > 0){
				$this->error('不能有标点符号！');
			}
			if(is_numeric($_POST['word'])){
				$this->error('不能只写数字！');
			}
			$word_num1 = strlen($_POST['word']);
			if($word_num1 == 1){
				$this->error('不能写单个字母！');
			}
			$word_num2 = mb_strlen($_POST['word'],'utf8');
			if($word_num2 > 50){
				$this->error('不能超过50个字！');
			}
			$find_word_post = M('Susceptible')->where(array('word'=>$_POST['word']))->find();
			if($find_word_post != ''){
				$this->error('敏感词已存在！');
			}
			if($_POST['word'] == ''){
				$this->error('请填写敏感词！');
			}
			$add['word'] = $_POST['word'];
			$add['state'] = $_POST['state'];
			$add['time'] = time();
			$add['addtime'] = time();
			$word_id = M('Susceptible')->add($add);
			S('Susceptible'.C('site_url'),null);
			$this->success('添加成功',U('System/Susceptible/index'));
		}else{
			$this->display();
		}
	}
	public function adds(){
		if(IS_POST){
			if($_POST['fenge'] == ''){
				$this->error('请填写分隔符！');
			}
			if($_POST['words'] == ''){
				$this->error('请填写敏感词！');
			}
			$word_array = explode($_POST['fenge'],$_POST['words']);
			foreach($word_array as $wo){
				$wo = strip_tags($wo);
				
				$exp="/[\\pP]/u";
				preg_match_all($exp,$wo,$math);
				if(count($math[0]) > 0){
					$this->error('每个敏感词不能有标点符号！');exit;
				}
				if(is_numeric($wo)){
					$this->error('每个敏感词不能只写数字！');exit;
				}
				$word_num1 = strlen($wo);
				if($word_num1 == 1){
					$this->error('每个敏感词不能写单个字母！');exit;
				}
				$word_num2 = mb_strlen($wo,'utf8');
				if($word_num2 > 50){
					$this->error('每个敏感词不能超过50个字！');exit;
				}
				$find_word_post = M('Susceptible')->where(array('word'=>$wo))->find();
				if($find_word_post != ''){
					$this->error('敏感词【'.$wo.'】已存在！');exit;
				}
				if(in_array($wo,$wo_array)){
					$this->error('敏感词【'.$wo.'】重复了！');exit;
				}else{
					$wo_array[] = $wo;
				}
			}
			foreach($word_array as $wo){
				$add['word'] = $wo;
				$add['state'] = $_POST['state'];
				$add['time'] = time();
				$add['addtime'] = time();
				$word_id = M('Susceptible')->add($add);
			}
			S('Susceptible'.C('site_url'),null);
			$this->success('添加成功',U('System/Susceptible/index'));
		}else{
			$this->display();
		}
	}
}
?>