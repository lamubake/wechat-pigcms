<?php
class HongbaoqiyeAction extends UserAction
{
    public $token;
    public $set1;
    protected function _initialize()
    {
        parent::_initialize();
		$this->canUseFunction('Hongbaoqiye');
    }
    public function index()
    {
        $info = M('hongbaoqiye_set')->where(array('token' => $_GET['token']))->find();
		$host = $_SERVER['HTTP_HOST'];
		$value = 'http://'.$host.'/index.php?g=Wap%26m=Hongbaoqiye%26a=index%26token='.$this->token.'%26pid='.$this->wecha_id.'';
		if ($info) {
			$info['ewmurl']=$value;
		}
        if ($_POST) {
            $date['state_subscribe'] = $_POST['state_subscribe'];
            $date['url'] = $_POST['url'];
            $date['pic'] = $_POST['pic'];
            $date['statdate'] = strtotime($_POST['statdate']);
            $date['enddate'] = strtotime($_POST['enddate']);
            $date['tian'] = $_POST['tian'];
            $date['ci'] = $_POST['ci'];
            $date['haoyou'] = $_POST['haoyou'];
            $date['choujiangci'] = $_POST['choujiangci'];
            $date['quyu'] = $_POST['quyu'];
            $date['jiange'] = $_POST['jiange'];
            $date['gz'] = $_POST['gz'];
            $date['title'] = $_POST['title'];
            $date['banquan'] = $_POST['banquan'];
            $date['token'] = $_GET['token'];
            $date['ly'] = $_POST['ly'];
			 $date['user_total'] = $_POST['user_total'];
			 $date['quyus'] = $_POST['quyus'];
			 $date['quyux'] = $_POST['quyux'];
			 $date['cbt'] = $_POST['cbt'];
			$date['desc'] = $_POST['desc'];
			$date['state_pyq'] = $_POST['state_pyq'];
			$date['state_hy'] = $_POST['state_hy'];
			$date['state_fanspic'] = $_POST['state_fanspic'];
			$date['state_fansname'] = $_POST['state_fansname'];
			
            if (!$info) {
                $a = M('hongbaoqiye_set')->add($date);
                if ($a) {
                    $this->error('活动添加成功!', U('Hongbaoqiye/index', array('token' => $this->token)));
                    die;
                } else {
                }
            } else {
                $a = M('hongbaoqiye_set')->where(array('token' => $_GET['token']))->save($date);
                if ($a) {
                    $this->success('更新成功!', U('Hongbaoqiye/index', array('token' => $this->token)));
                    die;
                } else {
                    $this->error('更新失败!', U('Hongbaoqiye/index', array('token' => $this->token)));
                    die;
                }
            }
        }
        $this->info = $info;
        $this->display();
    }
    public function zzs()
    {
        $info = M('Hongbaoqiye_zzs')->where(array('token' => $_GET['token']))->select();
        $this->info = $info;
        $this->display();
    }
    public function zzs_add()
    {
        if ($_POST) {
            $info = M('Hongbaoqiye')->where(array('token' => $_GET['token']))->find();
            $date['pic'] = $_POST['pic'];
            $date['url'] = $_POST['url'];
            $date['name'] = $_POST['name'];
            $date['info'] = $_POST['info'];
            $date['pid'] = $info['id'];
            $date['token'] = $_GET['token'];
            $date['time'] = time();
            $a = M('Hongbaoqiye_zzs')->add($date);
            if ($a) {
                $this->success('保存成功!', U('Hongbaoqiye/zzs', array('token' => $this->token)));
                die;
            } else {
                $this->error('保存失败!');
                die;
            }
        }
        $this->display();
    }
    public function zzs_edit()
    {
        $info = M('Hongbaoqiye_zzs')->where(array('id' => $_GET['id']))->find();
		//dump($info);
		//die;
        if ($_POST) {
            $infos = M('Hongbaoqiye')->where(array('token' => $_GET['token']))->find();
            $date['pic'] = $_POST['pic'];
            $date['url'] = $_POST['url'];
            $date['name'] = $_POST['name'];
            $date['info'] = $_POST['info'];
            $date['pid'] = $_GET['id'];
            $date['token'] = $_GET['token'];
            $date['time'] = time();
            $a = M('Hongbaoqiye_zzs')->where(array('id' => $_GET['id']))->save($date);
            if ($a) {
                $this->success('更新成功!', U('Hongbaoqiye/zzs', array('token' => $this->token)));
                die;
            } else {
                $this->error('更新失败!');
                die;
            }
        }
        $this->info = $info;
        $this->display();
    }
	 public function rands()
    {
		
		$info=M('hongbaoqiye_rand')->where(array('token'=>$_GET['token']))->find();
		
		if($_POST){
							$date['token']=$_GET['token'];	
							$date['r1']=$_POST['r1'];
							$date['r2']=$_POST['r2'];
							$date['r3']=$_POST['r3'];
							$date['r4']=$_POST['r4'];
							$date['r5']=$_POST['r5'];
							$date['rand1']=$_POST['rand1'];
							$date['rand2']=$_POST['rand2'];
							$date['rand3']=$_POST['rand3'];
							$date['rand4']=$_POST['rand4'];
							$date['rand5']=$_POST['rand5'];
						if($info){
							
										$a=	M('hongbaoqiye_rand')->where(array('token'=>$_GET['token']))->save($date);
										if($a){
											  $this->success('更新成功!');exit;
											}else{
												  $this->success('更新失败!');exit;
												}
											
											
							
							}else{
											$a=	M('hongbaoqiye_rand')->add($date);	
												
											if($a){
											  $this->success('添加成功!');exit;
											}else{
												  $this->success('添加失败!');exit;
												}	
								
								
								}
			
			}
		  $this->assign('info',$info);	
       $this->display();
      
    }
    public function zzs_del()
    {
        $info = M('Hongbaoqiye_zzs')->where(array('id' => $_GET['id']))->delete();
        if ($info) {
            $this->success('删除成功!', U('Hongbaoqiye/zzs', array('token' => $this->token)));
            die;
        } else {
            $this->error('删除失败!');
            die;
        }
    }
	public function user()
    {  
		$count=M('Hongbaoqiye_record')->where(array('token'=>$_GET['token']))->count();
		$count_money=round(M('Hongbaoqiye_record')->where(array('token'=>$_GET['token']))->sum('money'),2);
		$count_user=M('Hongbaoqiye_record')->where(array('token'=>$_GET['token']))->distinct(true)->count();
		
		
		  $page=new Page($count,10);	
		  $info=M('Hongbaoqiye_record')->where(array('token'=>$_GET['token']))->limit($page->firstRow.','.$page->listRows)->order('time desc')->select();
		
          $this->assign('count',$count);
		  $this->assign('count_user',$count_user);
		   $this->assign('count_money',$count_money);
		  $this->assign('info',$info);
		  $this->assign('page',$page->show());
		$this->display();
        
    }
	public function blackuser()
    {  
		$info=M('Hongbaoqiye_set')->where(array('token'=>$_GET['token']))->find();
		$black = explode(',', $info['blackuser']);
		
		if (in_array($_GET['name'], $black)) {
            $this->error('用户已经存在黑名单了，请勿重复添加！');
            die;
        }
		if(!$info['blackuser']){
			$date['blackuser']=$_GET['name'];
		    $a=M('Hongbaoqiye_set')->where(array('token'=>$_GET['token']))->save($date);	
			}else{
				
			 $date['blackuser']=$info['blackuser'].','.	$_GET['name'];
			 $a=M('Hongbaoqiye_set')->where(array('token'=>$_GET['token']))->save($date);		
				
				}
				if($a){
		 $this->success('加入黑名单成功!', U('Hongbaoqiye/user', array('token' => $this->token)));}else{
			 $this->error('操作失败!'); 
			 }
		  
		
        
    }
	public function blackip()
    {  
		$info=M('Hongbaoqiye_set')->where(array('token'=>$_GET['token']))->find();
		$black = explode(',', $info['blackip']);
		
		if (in_array($_GET['ip'], $black)) {
            $this->error('用户已经存在黑名单了，请勿重复添加！');
            die;
        }
		if(!$info['blackip']){
			$date['blackip']=$_GET['ip'];
		    $a=M('Hongbaoqiye_set')->where(array('token'=>$_GET['token']))->save($date);	
			}else{
				
			 $date['blackip']=$info['blackip'].','.	$_GET['ip'];
			 $a=M('Hongbaoqiye_set')->where(array('token'=>$_GET['token']))->save($date);		
				
				}
				if($a){
		 $this->success('加入黑名单成功!', U('Hongbaoqiye/user', array('token' => $this->token)));}else{
			 $this->error('操作失败!'); 
			 }
		  
		
        
    }
}