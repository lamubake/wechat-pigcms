<?php
class EqxAction extends UserAction{
	public $token;
	public function _initialize() {
		parent::_initialize();
		$function=M('Function')->where(array('funname'=>'Eqx'))->find();
        if (intval($this->user['gid'])<intval($function['gid'])){
            $this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>$this->token)));
        }
        $this->canUseFunction('Eqx');
		
		$token_open=M('token_open')->field('queryname')->where(array('token'=>session('token')))->find();

		$this->Eqx=$eqx_User = M(C('eqxname').'.users','cj_')->where(array('token'=>session('token'))) ;
      //  var_dump($this->Eqx);
		$this->token=session('token');

		$this->assign('token',$this->token);

	}

	public function index(){
		//自行增加 获取当前公众号设置的密码 传去易企秀 不能放到后面。会出错
		$users=M('wxuser')->where(array('token'=>$this->token))->find();//自行修改
		$this->assign('users',$users);
		//结束

      $User = M('users','cj_','mysql://'.C('eqxuser').':'.C('eqxpassword').'@'.C('eqxdburl').'/'.C('eqxname').''); 

	  $Users = M('scene','cj_','mysql://'.C('eqxuser').':'.C('eqxpassword').'@'.C('eqxdburl').'/'.C('eqxname').''); 
    //  $Info = M('eqx_info');
	 

		$eqx = $User->where(array('email_varchar'=>session('token')))->find() ;
       // $eqxx = $Users->where(array('email_varchar'=>session('token')))->find() ;
		 //dump($eqx);exit;

		$count=$eqx_scene=$Users->where(array('userid_int'=>$eqx['userid_int'],'delete_int'=>'0'))->count();

		$page=new Page($count,9);

		$eqx_scene=$Users->where(array('userid_int'=>$eqx['userid_int'],'delete_int'=>'0'))->order('createtime_time desc')->limit($page->firstRow.','.$page->listRows)->select();
 
        $num=$Users->where(array('userid_int'=>$eqx['userid_int'],'delete_int'=>'0'))->order('createtime_time desc')->count();//数量统计
		//$num=$Users->where(array('userid_int'=>$eqx['userid_int']))->order('createtime_time desc')->count();//数量统计

		$sum=$Users->where(array('userid_int'=>$eqx['userid_int'],'delete_int'=>'0'))->order('createtime_time desc')->sum('hitcount_int');//数量统计

		$date_count=$Users->where(array('userid_int'=>$eqx['userid_int'],'delete_int'=>'0'))->order('createtime_time desc')->sum('datacount_int');//数量统计
		
		// $pid=$Info->where(array('sceneid_bigint'=>$eqx_scene[0]['sceneid_bigint']))->find();//ID获取
		// echo $eqx_scene[0]['sceneid_bigint'].'<br>';
		// dump($pid);
     

		
		$token=session('token');
        $this->assign('page',$page->show());

		$this->assign('eqx',$eqx);

		$this->assign('num',$num);

		$this->assign('sum',$sum);

		$this->assign('date_count',$date_count);

		$this->assign('info',$eqx_scene);

		$this->assign('token',$token);
	//	$this->assign('pid',$pid);

		$this->display();
	}


	public function del(){
		
			$token=$_GET['token'];

	    	$id=$_GET['id'];
		
		   $where = array('token' => $this->token, 'id' => $id);
	//   }
		if (M('eqx_info')->where($where)->delete()) {	
			$whered['token'] = $this->token;
			$whered['module'] = 'Eqx';
			$whered['pid'] = $id;
			M('Keyword')->where($whered)->delete();
			$this->success('关键字回复删除成功');exit;
			//$this->success('删除', U('SeniorScene/index', array('token' => $this->token)));
		}

		$Users = M('scene','cj_','mysql://'.C('eqxuser').':'.C('eqxpassword').'@'.C('eqxdburl').'/'.C('eqxname').''); 
		
	
		
		//$pid=$_GET['pid'];

		$date['delete_int']='1';

		$dd=$Users->where(array('sceneid_bigint'=>$id))->save($date);//

		if($dd){

			$this->success('删除成功，如此场景建了关键字回复，请自行删除');exit;}else{$this->error('删除失败');exit;}

	}
	
	public function keywordlist()
	{
		$search = $this->_post('search', 'trim');
		$where = array('token' => $this->token);

		if ($search) {
			$where['title|keyword'] = array('like', '%' . $search . '%');
		}

		$count = M('eqx_info')->where($where)->count();
		$Page = new Page($count, 15);
		$list = M('eqx_info')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('page', $Page->show());
		$this->assign('list', $list);
		$this->display();
	}

	public function set(){

		$db=M('eqx_info');

		$pic=$_GET['pic'];

		$name=$_GET['name'];

		$desc_varchar=$_GET['desc_varchar'];

		$sceneid_bigint=$_GET['id'];

		$a=$db->where(array('token'=>$_GET['token'],'sceneid_bigint'=>$_GET['sceneid_bigint']))->find();

        if(empty($a)){ 

		       $a['picurl']=$pic;

			    $a['title']=$name;

				  $a['info']=$desc_varchar;
	     	}

		if(IS_POST){

			$date['keyword']=$_POST['keyword'];

			$date['title']=$_POST['title'];

			$date['info']=$_POST['info'];

			$date['picurl']=$_POST['picurl'];

			$date['url']=$_GET['url'];
			if(empty($date['url'])){
				$date['url']=$_POST['url'];
			}
			$date['token']=$_GET['token'];

			$date['pic']=$_POST['pic'];

			$date['sceneid_bigint']=$_GET['sceneid_bigint'];

		  if(empty($a['id'])){
	    
			$dd=$db->add($date);

			if($dd){

				$da['keyword']=$_POST['keyword'];
			//	echo $da['keyword'].'aa<br>';

				$da['pid']=$dd;
              //    echo $da['pid'].'bb<br>';
				$da['module']='Eqx';
// echo $da['module'].'cc<br>';
				$da['token']=$_GET['token'];
  //echo $da['token'].'dd<br>';
				$info=M('keyword')->add($da);
				
		//		var_dump($info);
	//			exit;
                $this->success('更新成功',U('Eqx/keywordlist',array('token'=>session('token'))));exit;
				//$this->success('添加成功');exit;

		   }else{ 
		       $this->success('添加失败');exit;
		   }	 

       }else{
   $ds=$db->where(array('id'=>$a['id'],'token'=>$_GET['token']))->save($date);

	    if($ds){

				$da['keyword']=$_POST['keyword'];
			    $da['module']='Eqx';

				$da['token']=$_GET['token'];

				$info=M('keyword')->where(array('token'=>$_GET['token'],'module'=>'Eqx','pid'=>$a['id']))->save($da);

				$this->success('更新成功',U('Eqx/keywordlist',array('token'=>session('token'))));exit;

			  }

			else{ $this->error('更新失败');exit;}	}}
        $this->assign('pic',$pic);

		$this->assign('a',$a);

		$this->display();

	}
}

?>