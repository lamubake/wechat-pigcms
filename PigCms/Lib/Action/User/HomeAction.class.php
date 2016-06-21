<?php
class HomeAction extends UserAction{
	public $token;
	public $home_db;
	public $apiurl;
	public function _initialize() {
		parent::_initialize();
		$this->token=$this->_session('token');
		$this->home_db=M('home');
		
		$this->canUseFunction('shouye');
		
		$this->m_wxuser = M('wxuser');
		$this->m_mywxuser = M('service_wxuser');
		
		//开启
		$this->apiurl['create'] = "http://im.pigcms.com/api/app_create.php";
		//关闭
		$this->apiurl['status'] = "http://im.pigcms.com/api/app_edit.php";
	}
	//配置
	public function set(){
		$home=$this->home_db->where(array('token'=>session('token')))->find();
		if(IS_POST){
			//客服开启关闭处理
			//dump($_POST['servicestate']);exit;
			$where_mywxuser['token'] = $this->token;
			$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();
			if($_POST['servicestate'] == '1'){
				if($mywxuser['app_id'] == ''){
					$where_wxuser['token'] = $this->token;
					$wxuser = $this->m_wxuser->where($where_wxuser)->find();
					$data['domain'] = str_replace('http://','',C('site_url'));
					$data['domain'] = str_replace('https://','',$data['domain']);
					$data['labe1'] = $this->token;
					$data['wx_app_id'] = $wxuser['appid'];
					$data['wx_app_secret'] = $wxuser['appsecret'];
					$data['from'] = '1';
					$data['activity_url'] = C('site_url')."/index.php?g=Wap&m=ServiceReturn&a=activity&token=".$this->token;
					$data['msg_tip_url'] = C('site_url')."/index.php?g=Wap&m=ServiceReturn&a=msgtip&token=".$this->token;
					$data['my_url'] = C('site_url')."/index.php?g=Wap&m=ServiceReturn&a=my&token=".$this->token;
					ksort($data);
					$i = 0;
					foreach($data as $k=>$v){
						if($i == 0){
							$mydata .= $k."=".$v;
						}else{
							$mydata .= "&".$k."=".$v;
						}
						$i++;
					}
					$data['key'] = md5($mydata);
					$open = json_decode($this->https_request($this->apiurl['create'],$data), true);
					if($open['err_code'] == 0){
						$where_mywxuser['token'] = $this->token;
						$save_mywxuser['state'] = $_POST['servicestate'];
						$save_mywxuser['app_id'] = $open['app_id'];
						$save_mywxuser['app_key'] = $open['app_key'];
						$update_mywxuser = $this->m_mywxuser->where($where_mywxuser)->save($save_mywxuser);
					}else{
						$this->error($open['err_msg']);
					}
				}else{
					$data['app_id'] = $mywxuser['app_id'];
					$data['data'] = array('status'=>$_POST['servicestate']);
					$mydata['app_id'] = $mywxuser['app_id'];
					$data['key'] = $this->set_key($mydata,$mywxuser['app_key']);
					$open = json_decode($this->https_request($this->apiurl['status'],$data), true);
					if($open['err_code'] == 0){
						$where_mywxuser['token'] = $this->token;
						$save_mywxuser['state'] = $_POST['servicestate'];
						$update_mywxuser = $this->m_mywxuser->where($where_mywxuser)->save($save_mywxuser);
					}else{
						$this->error($open['err_msg']);
					}
				}
			}else{
				$data['app_id'] = $mywxuser['app_id'];
				$data['status'] = $_POST['servicestate'];
				$mydata['app_id'] = $mywxuser['app_id'];
				$data['key'] = $this->set_key($mydata,$mywxuser['app_key']);
				$open = json_decode($this->https_request($this->apiurl['status'],$data), true);
				if($open['err_code'] == 0){
					$where_mywxuser['token'] = $this->token;
					$save_mywxuser['state'] = $_POST['servicestate'];
					$update_mywxuser = $this->m_mywxuser->where($where_mywxuser)->save($save_mywxuser);
				}else{
					$this->error($open['err_msg']);
				}
			}
			
			
			
			$_POST['token'] = session('token');
			$stpic=$_POST['stpic'];
			$start=$_POST['start'];
			$str = substr($stpic,0,1);
			if($str == '#' && $start == '4'){
				$_POST['stpic']="";
			}
			//$info = str_replace("\r\n",' ',$_POST['info']);
			//$_POST['info'] = str_replace('&quot;','',$info);
			$token = session('token');
			S("homeinfo_".$token,NULL);
			if($home==false){				
				$this->all_insert('Home','/set');
			}else{
				$_POST['id']=$home['id'];
				$this->all_save('Home','/set');				
			}
		}else{
			$strpic=$home['stpic'];
			$str=substr( $strpic, 0, 1 );
			if($str != ''){
				if($str == '#'){
					$strcolor=$strpic;
					$img = '1';
				}else{
					$strcolor='';
					$img = '2';
				}
			}
			if($home == ''){
				$home['start']='0';
			}
			$this->assign('img',$img);
			$this->assign('strpic',$strpic);
			$this->assign('strcolor',$strcolor);
			$this->assign('home',$home);
			
			$where_mywxuser['token'] = $this->token;
			$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();
			$this->assign('servicestate',$mywxuser['state']);
			$this->display();
		}
	}
	
	//制作key
	public function set_key($data,$app_key){
		$new_arr = array();
		ksort($data);
		foreach($data as $k=>$v){
			$new_arr[] = $k.'='.$v;
		}
		$new_arr[] = 'app_key='.$app_key;
		$str = implode('&',$new_arr);
		return md5($str);
	}
	
	public function plugmenu(){
		$where=array('token'=>$this->token);
		$menuArr=array('tel','memberinfo','nav','message','share','home','album','email','shopping','membercard','activity','weibo','tencentweibo','qqzone','wechat','music','video','recommend','other');
		$home=$this->home_db->where(array('token'=>session('token')))->find();
		$plugmenu_db=M('site_plugmenu');
		if (!$home){
			$this->error('请先配置3g网站信息',U('Home/set',array('token'=>session('token'))));
		}else {
			S("homeinfo_".$this->token,NULL);
			if(IS_POST){
				//保存版权信息和菜单颜色
				$this->home_db->where($where)->save(array('plugmenucolor'=>$this->_post('plugmenucolor'),'copyright'=>$this->_post('copyright')));
				//保存各个菜单
				//先删除原来的
				$plugmenu_db->where($where)->delete();
				//添加
				foreach ($menuArr as $m){
					$row=array('token'=>$this->token);
					$row['name']=$m;
					$row['url']=$this->_post('url_'.$m);
					$row['taxis']=intval($_POST['sort_'.$m]);
					$row['display']=intval($_POST['display_'.$m]);
					//if (strlen(trim($row['url']))){
						$plugmenu_db->add($row);
					//}
				}
				$this->success('设置成功',U('Home/plugmenu',array('token'=>$this->token)));
			}else {
				$homeInfo=$this->home_db->where($where)->find();
				if (!$homeInfo['plugmenucolor']){
					$homeInfo['plugmenucolor']='#ff0000';
				}
				//
				$this->assign('userGroup',$this->userGroup);
				//
				$this->assign('homeInfo',$homeInfo);
				$menus=$plugmenu_db->where($where)->select();
				$menusArr=array();
				foreach ($menus as $m){
					$menusArr[$m['name']]=$m;
				}
				$this->assign('menus',$menusArr);
				$this->display();
			}
		}
	}
	
	//https请求（支持GET和POST）
    protected function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
	
}



?>