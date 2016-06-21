<?php

class PopularityAction extends UserAction{
	
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction("Popularity");
	}
	
	public function index(){
		$search     = $this->_post('search','trim');
		$where      = array('token'=>$this->token);
		if($search){
			$where['title|keyword']  = array('like','%'.$search.'%');
		}
		
		$count      = M('Popularity')->where($where)->count();
		$Page       = new Page($count,15);
		
		$list 	= M('Popularity')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function set(){
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'id'=>$id);
		$pop_info   = M('Popularity')->where($where)->find();

		if(IS_POST){
			
			$_POST['token'] 	= $this->token; 
			$_POST['start'] 	= strtotime($_POST['start']);
			$_POST['end'] 		= strtotime($_POST['end']);
			$_POST['add_time'] 	= time(); 
			$prize 	= $_REQUEST['prize'];

			if(D('Popularity')->create()){
				if($pop_info){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('Popularity')->where($up_where)->save($_POST);
					
					$this->prize_set($this->_post('id','intval'),$prize,'save');
					$this->handleKeyword($this->_post('id','intval'),'Popularity',$this->_post('keyword','trim'));
					$this->success('修改成功',U('Popularity/index',array('token'=>$this->token)));
				}else{
					$id                 = D('Popularity')->add($_POST);
					if($id){
						$this->prize_set($id,$prize,'add');
						$this->handleKeyword($id,'Popularity',$this->_post('keyword','trim'));
						$this->success('添加成功',U('Popularity/index',array('token'=>$this->token)));
					}
				}	
				
			}else{

                $this->error(D('Popularity')->getError());
            }
			
		}else{
			$prize 	= M('Popularity_prize')->where(array('token'=>$this->token,'pid'=>$id))->order('id asc')->select();
			
			$this->assign('start_date',time());
			$this->assign('end_date',strtotime('+1 month'));
			$this->assign('prize',$prize);
			$this->assign('set',$pop_info);
			$this->display();
		}
	}
	
	
	public function prize_log(){
		$id 	= $this->_get('id','intval');
		$where  = array('token'=>$this->token,'pid'=>$id,'is_real'=>1);
		import('ORG.Util.Page');
		$where_page['token'] = $this->token;
		$where_page['id'] = $_GET['id'];
		
		if(IS_POST){
			$min 	= $this->_post('min','intval');
			$max 	= $this->_post('max','intval');

			if($min && $max){
				$where['share_count'] 	= array('BETWEEN',array($min,$max));
			}
			$where_page['min'] = $_POST['min'];
			$where_page['max'] = $_POST['max'];
			
		}else{
			if(!empty($_GET['min'])){
				$min 	= $this->_get('min','intval');
				$max 	= $this->_get('max','intval');
				if($min && $max){
					$where['share_count'] 	= array('BETWEEN',array($min,$max));
				}
				$where_page['min'] = $_GET['min'];
				$where_page['max'] = $_GET['max'];
			}
		}
		if($_POST['search']){
			//$where['username|mobile'] 	= array('like','%'.trim($_POST['search']).'%');
		}
		
		$count  = M('Popularity_user')->where($where)->count();
		$Page   = new Page($count,15);
		foreach($where_page as $key=>$val){
			$Page->parameter.="$key=".urlencode($val).'&';
		}
		$list 	= M('Popularity_user')->where($where)->order('share_count desc,add_time asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		foreach($list as $key=>$val){
			$prize 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->field('wechaname,truename,tel')->find();
			$list[$key]['mobile'] 	= $prize['tel']?$prize['tel']:'无';
			$list[$key]['username'] = $prize['truename']?$prize['truename']:'匿名';
			$list[$key]['nickname'] = $prize['wechaname']?$prize['wechaname']:'无';
		}
	
			
		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	public function del(){
		$id		= $this->_get('id','intval');
		if(M('Popularity')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			
			M('Popularity_user')->where(array('token'=>$this->token,'pid'=>$id))->delete();
			M('Popularity_prize')->where(array('token'=>$this->token,'pid'=>$id))->delete();
			M('Popularity_share')->where(array('token'=>$this->token,'pid'=>$id))->delete();
			
			$this->success('删除成功');
		}
	}
	public function log_del(){
		$id		= $this->_get('id','intval');
		$key 	= M('Popularity_user')->where(array('token'=>$this->token,'id'=>$id))->field('pid,share_key')->find();

		if(M('Popularity_user')->where(array('token'=>$this->token,'id'=>$id))->delete()){
			M('Popularity_share')->where(array('token'=>$this->token,'pid'=>$key['pid'],'share_key'=>$key['share_key']))->delete();
			$this->success('删除成功');
		}
	}
	
	public function prize_set($pid,$data,$type='add'){
		
		foreach($data as $key=>$val){
			$val['token'] 	= $this->token;
			$val['pid'] 	= $pid;

			if($type == 'add'){
				M('Popularity_prize')->add($val);
			}else if($type == 'save'){
				$where 	= array('id'=>$val['id'],'token'=>$this->token,'pid'=>$pid);
				M('Popularity_prize')->where($where)->save($val);
			}

		}

	}
	
	public function is_ok(){
		$id		= $this->_get('id','intval');
		if(M('Popularity_prize_log')->where(array('token'=>$this->token,'id'=>$id))->save(array('status'=>1))){
			$this->success('操作成功');
		}
	}
}