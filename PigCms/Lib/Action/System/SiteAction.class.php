<?php
class SiteAction extends BackAction{
	public function _initialize() {
        parent::_initialize();  //RBAC 验证接口初始化.
		$config_file_list = array('alipay.php','email.php','info.php','platform.php','safe.php','sms.php');
		foreach($config_file_list as $vo){
			$fh = fopen(CONF_PATH.$vo,"rb");
			$fs = fread($fh,filesize(CONF_PATH.$vo));
			fclose($fh);
			$fs = str_replace('\'up_exts\'','\'up_exts_error\'',$fs);
			file_put_contents(CONF_PATH.$vo, $fs, LOCK_EX);
			@unlink(RUNTIME_FILE);
		}				
		$_POST = filterPost($_POST, array('up_password'));
    }
	
	public function index(){
		$where=array('agentid'=>$this->agentid);
		$groups=M('User_group')->where($where)->order('id ASC')->select();
		$this->assign('groups',$groups);
		if(class_exists('updateSync')){
			$result = updateSync::getIfWeidian();
			$this->assign('load_config',$result);
		}
		$this->display();
	}
	public function mysql(){
		
		
		$this->display();
		
	}
	public function mysqlajax(){
		switch($_POST['type']){
			case 'table_name':
				$db_name = C('DB_NAME');
				$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$db_name."'";
				$query_sql = M()->query($sql);
				$table_name = array();
				foreach($query_sql as $k=>$v){
					$table_name[$k] = $v['TABLE_NAME'];
				}
				$data['table_name'] = $table_name;
				$data['table_count'] = count($table_name);
				$this->ajaxReturn($data,'JSON');
			break;
			case 'youhuasql':
				$sql_OPTIMIZE = "OPTIMIZE TABLE `".$_POST['table_name']."`";
				$query_sql_OPTIMIZE = M()->query($sql_OPTIMIZE);
				$query_sql_OPTIMIZE[0]['Table'] = str_replace(C('DB_NAME').'.','',$query_sql_OPTIMIZE[0]['Table']);
				$data = $query_sql_OPTIMIZE[0];
				$this->ajaxReturn($data,'JSON');
			break;
			case 'xiufusql':
				$sql_REPAIR = "REPAIR TABLE `".$_POST['table_name']."`";
				$query_sql_REPAIR = M()->query($sql_REPAIR);
				$query_sql_REPAIR[0]['Table'] = str_replace(C('DB_NAME').'.','',$query_sql_REPAIR[0]['Table']);
				$data = $query_sql_REPAIR[0];
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
	public function appajax(){
		$appid = $_POST['appid'];
		$secret = $_POST['secret'];

		$apiOauth 	= new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($appid);
		/*
		$url_access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
		$data_access_token = json_decode($this->https_request($url_access_token), true);
		$access_token = $data_access_token['access_token'];
		*/
		$url = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token={$access_token}";
		$wxdata = json_decode($this->https_request($url), true);
		if($wxdata['errcode'] == 48001){
			$data['error'] = 1;
		}
		/*
		if($data_access_token['errcode'] == 40001 || $data_access_token['errcode'] == 41002 || $data_access_token['errcode'] == 41004 || $data_access_token['errcode'] == 40125){
			$data['error'] = 2;
		}else{
			$data['error'] = $data_access_token['errcode'];
		}
		*/
		$this->ajaxReturn($data,'JSON');
	}
	//https请求（支持GET和POST）
    protected function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
	public function email(){
		$this->display();
	}	
	public function alipay(){
		$this->display();
	}
	public function safe(){
		$this->display();
	}
	public function upfile(){
		$this->display();
	}
	public function sms(){
		$total=M('Sms_expendrecord')->sum('count');
		$this->assign('total',$total);
		$this->display();
	}
	public function wechat_api(){
		$site 	= M('weixin_account')->find();
		if(IS_POST){
			if($site){
				if(M('Weixin_account')->where('1')->save($_POST)){
					$this->success('操作成功');
				}else{
					$this->success('操作失败');
				}
			}else{
				if(M('Weixin_account')->add($_POST)){
					$this->success('操作成功');
				}else{
					$this->success('操作失败');
				}
			}
		}else{
			$this->assign('site',$site);
			$this->display();
		}	
	}
	public function insert(){
		$appid = $_POST['appid'];
		$secret = $_POST['secret'];
		if($_POST['up_exts'] != ''){
			$_POST['up_exts'] = str_replace("'",'',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('"','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('‘','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('“','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('，',',',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('’','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('”','',$_POST['up_exts']);
			$_POST['up_exts'] = trim($_POST['up_exts']);
			$_POST['up_exts'] = strtolower($_POST['up_exts']);
		}else{
			unset($_POST['up_exts']);
		}
		if($appid != '' && $secret != ''){
			$apiOauth 	= new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($appid);
			/*
			$url_access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
			$data_access_token = json_decode($this->https_request($url_access_token), true);
			$access_token = $data_access_token['access_token'];
			*/
			$url = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token={$access_token}";
			$wxdata = json_decode($this->https_request($url), true);
			if($wxdata['errcode'] == 48001){
				$this->error('您填的appid和appsecret并不是认证后的服务号！');
				exit;
			}
			/*
			if($data_access_token['errcode'] == 40001 || $data_access_token['errcode'] == 41002 || $data_access_token['errcode'] == 41004 || $data_access_token['errcode'] == 40125){
				$this->error('您填的appid和appsecret不正确！');
				exit;
			}
			*/
		}
		$file=$this->_post('files');
		unset($_POST['files']);
		unset($_POST[C('TOKEN_NAME')]);
		if (isset($_POST['countsz'])){
		$_POST['countsz']=base64_encode($_POST['countsz']);
		}
		if($this->update_config($_POST,CONF_PATH.$file)){
			$this->success('操作成功');
		}else{
			$this->success('操作失败');
		}
	}
	
	public function smssendtest(){
		if (strlen($_GET['mp'])!=11){
			$this->error('请输入正确的手机号');
		}
		$this->error(Sms::sendSms('admin','hello,你好',$_GET['mp']));
	}
	private function update_config($config, $config_file = '') {
		!is_file($config_file) && $config_file = CONF_PATH . 'web.php';
		if (is_writable($config_file)) {
			//$config = require $config_file;
			//$config = array_merge($config, $new_config);
			//dump($config);EXIT;
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
			@unlink(RUNTIME_FILE);
			return true;
		} else {
			return false;
		}
	}
	public function rippleos_key(){
		$this->display();
	}	
	public function themes() {
		$this->display();
	}
	public function themes_up() {
		$data=$this->_post('beer');
		$date = substr(preg_replace('|[0-9/?@"<>{&}:+%_-]|','',$data),0,10);
		$setfile = "./Conf/Home/config.php";
		$settingstr="<?php \n return array(\n   'TMPL_FILE_DEPR'=>'_',  \n 'DEFAULT_THEME' => '".$data."',      );\n?>\n";
		file_put_contents($setfile,$settingstr);
		$this->success('操作成功',U('Site/themes'));
	}
}