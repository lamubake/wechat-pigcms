<?php
class UserAction extends BaseAction{
	public $userGroup;
	public $token;
	public $user;
	public $userFunctions;
	public $wxuser;
	public $usertplid;
	protected function _initialize(){
		parent::_initialize();
		
		$userinfo=M('User_group')->where(array('id'=>session('gid')))->find();
		$this->assign('userinfo',$userinfo);
		$this->userGroup=$userinfo;
		$users=M('Users')->where(array('id'=>$_SESSION['uid']))->find();
		if (session('usertplid') === NULL || session('usertplid') != (int)$users['usertplid']) {
			session('usertplid',(int)$users['usertplid']);
		}
		$this->usertplid = (int)$users['usertplid'];
		$this->assign('usertplid',session('usertplid') );

		$this->user=$users;
		$this->token=session('token');
		$this->assign('thisUser',$users);
		
		$allow_pay = array('AlipayAction','TenpayAction','AlipayReceiveAction');
		$this->assign('viptime',$users['viptime']);
		if(session('uid') && session('role_name') != "" && session('role_name') !='staff'){
			if($users['viptime']<time()){
				if(function_exists('get_called_class')){
					if(!in_array(get_called_class(),$allow_pay)){
										$this->error('非常遗憾的告诉您，您的帐号已经到期，请充值后再使用，感谢继续使用我们的系统。',
										U('User/Alipay/index',array('flag'=>5.3)));
					}
				}else{
					if(!in_array(get_class($this),$allow_pay)){
					 $this->error('非常遗憾的告诉您，您的帐号已经到期，请充值后再使用，感谢继续使用我们的系统。',
								U('User/Alipay/index',array('flag'=>5.2)));
				   }

		        }

			}
		}
		$token_open=M('Token_open')->field('queryname')->where(array('token'=>$this->token))->find();
		$trans = include('./PigCms/Lib/ORG/FuncToModel.php');
		if (C('agent_version')&&$this->agentid){
			$user_group_where = array('id'=>session('gid'),'agentid'=>$this->agentid);
			$func_where = array('agentid'=>$this->agentid,'status'=>1);
			
			$function_db = M('Function');
		}else{
			$user_group_where = array('id'=>session('gid'));
			
			$func_where = array('status' => 1);
			$function_db = M('Function');
		}
		if(session('role_name') != "" && session('role_name') == 'staff'){
			$group_func = M('company_staff')->where(array('id'=>intval(session('staff_id'))))->getField('func');
		}else{
			$group_func = M('User_group')->where($user_group_where)->getField('func');
		}
		$Afunc = $function_db->where($func_where)->field('id,funname')->select();

		
		$group_func = explode(',', $group_func);
		foreach ($Afunc as $tk => $tv){
			
			$vvv = isset($trans[$tv['funname']])?ucfirst($trans[$tv['funname']]):ucfirst($tv['funname']);
			if(!in_array($tv['funname'],$group_func)){
				$not_exist[] = $vvv;
			}
			
			if (!in_array($vvv,$allCloseFunc)) {
				$Allfunc[] = $vvv;
			}
			
		}
		if(session('role_name') != "" && session('role_name') == 'staff'){
			$this->assign('group_func',$group_func);
		}
		$this->assign('not_exist',$not_exist);
		$this->assign('Allfunc',$Allfunc);
				
		$wecha=M('Wxuser')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wxuser',$wecha);
		$this->wxuser=$wecha;
		$this->assign('wecha',$wecha);
		$this->assign('wxuser',$wecha);
		//$this->token=session('token');
		//dump($_SESSION);
		$this->assign('token',$this->token);
		//
		$token_open=M('token_open')->field('queryname')->where(array('token'=>$this->token))->find();
		$this->userFunctions=explode(',',$token_open['queryname']);
		//
		if (MODULE_NAME!='Upyun'){
			if(isset($_SESSION['role_name']) && session('role_name') == 'staff'){
				if(strtolower(MODULE_NAME) == 'index'){
					$this->redirect('User/'.$_SESSION['first_func'].'/index?token='.$_SESSION['token']);
				}
			}else{
				if(session('uid')==false){
					$this->redirect('Home/Index/login');
				}
			}
		}else {
			if (isset($_SESSION['administrator'])||isset($_SESSION['agentid'])||isset($_SESSION['uid'])||isset($_SESSION['wapupload'])){
				
			}else {
				if(isset($_POST['PHPSESSID'])){
					session_id($_POST['PHPSESSID']);
				}else{
					$this->redirect('Home/Index/login');
				}

			}
			
		}
		
		//子分支的登陆判断
		if (session('companyLogin') == 1 && !in_array(MODULE_NAME, array('Attachment', 'Repast', 'Upyun', 'Hotels', 'Store', 'Classify', 'Catemenu','DishOut','LivingCircle'))) {
			if((MODULE_NAME=='Recognition' && ACTION_NAME=='get_Wxticket') || (MODULE_NAME=='Index' && ACTION_NAME == 'help')){
				/*****屏蔽掉分店获取二维码函数执行到下面*****/
			}else{
			$m_seller = M("livingcircle_seller");
			$where_seller['token'] = $this->token;
			$where_seller['cid'] = session('companyid');
			$find_seller = $m_seller->where($where_seller)->find();
			if(empty($find_seller)){
				$this->redirect(U('User/Repast/index',array('cid' => session('companyid'))));
			}else{
				$this->redirect(U('User/LivingCircle/myseller',array('token' => $this->token)));
			}
		  }
		}
		
		/****************upyun*********************/
		define('UNYUN_BUCKET',C('up_bucket'));
		define('UNYUN_USERNAME',C('up_username'));
		define('UNYUN_PASSWORD',C('up_password'));
		define('UNYUN_FORM_API_SECRET',C('up_form_api_secret'));
		define('UNYUN_DOMAIN',C('up_domainname'));
		$this->assign('upyun_domain','http://'.UNYUN_DOMAIN);
		$this->assign('upyun_bucket',UNYUN_BUCKET);
		//
		$token=$this->_session('token');
		if (!$token){
			if (isset($_GET['token'])){
				$token=$this->_get('token');
			}else {
				$token='admin';
			}
		}
		
		//
		$options = array();
		$now=time();
		$options['bucket'] = UNYUN_BUCKET; /// 空间名
		$options['expiration'] = $now+600; /// 授权过期时间
		$options['save-key'] = '/'.$token.'/{year}/{mon}/{day}/'.$now.'_{random}{.suffix}'; /// 文件名生成格式，请参阅 API 文档
		$options['allow-file-type'] = C('up_exts'); /// 控制文件上传的类型，可选
		$options['content-length-range'] = '0,'.intval(C('up_size'))*1000; /// 限制文件大小，可选
		if (intval($_GET['width'])){
			$options['x-gmkerl-type'] = 'fix_width';
			$options['fix_width '] = $_GET['width'];
		}
		//$options['return-url'] = C('site_url').'/index.php?g=User&m=Upyun&a=editorUploadReturn'; /// 页面跳转型回调地址
		$policy = base64_encode(json_encode($options));
		$sign = md5($policy.'&'.UNYUN_FORM_API_SECRET); /// 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）
		$this->assign('editor_upyun_sign',$sign);
		$this->assign('editor_upyun_policy',$policy);
		//notice
		//
		
		/*if (C('server_topdomain')=='pigcms.cn'&&!C('close_user_notice')){
			$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
			$updateUrl='http://up.pigcms.cn/oa/admin.php?m=notice&c=view&a=noticeByModule&group='.GROUP_NAME.'&module='.MODULE_NAME.'&fileid='.$updateRecord['version'];
			$urt=file_get_contents($updateUrl);
			if ($urt){
				$urt=json_decode($urt,1);
				if ($urt){
					$this->assign('upgradeNews',$urt);
				}
			}
		}*/
		$this->assign('__access_remind__','<div style="color:red"> &nbsp;（该选项仅适用于认证服务号）</div>');
	}

	public function canUseFunction($funname){
		//权限验证
		if (C('agent_version')&&$this->agentid){
			$func_where = array('agentid'=>$this->agentid,'status'=>1);
			$function_db = M('Function');
		}else{
			$func_where = array('status' => 1);
			$function_db = M('Function');
		}
		$func_where['funname'] = $funname;

		$allow = $function_db->where($func_where)->getField('id');

		function map_tolower($v){
			return strtolower($v);
		}
		
		if(session('role_name') != "" && session('role_name') == 'staff'){
			$user_group = M('company_staff')->where(array('id'=>intval(session('staff_id'))))->getField('func');
		}else{
			$user_group = M('User_group')->where(array('id'=>intval(session('gid'))))->getField('func');
		}
		$user_group = explode(',', $user_group);
		$user_group = array_map("map_tolower", $user_group);
		if (in_array(strtolower($funname),$user_group) === false || !$allow){
			if(session('role_name') != "" && session('role_name') == 'staff'){
				$this->error('店主没有给你分配该权限,请联系你的店主',U(''.$_SESSION['first_func'].'/index',array('token'=>$this->token)));
			}else{
				$this->error('您还没有开启这个功能的使用权,请到“功能模块”菜单中勾选这个功能',U('Function/index',array('token'=>$this->token)));
			}
		}
	}
	public function convertLink($url){
		$url=str_replace(array('{siteUrl}','&amp;','&wecha_id={wechat_id}','{changjingUrl}'),array($this->siteUrl,'&','&diymenu=1','http://www.weihubao.com'),$url);
		return $url;
	}


	public function check_allow_Function($funname)
	{
		$gid = intval($_SESSION['gid']);
		$func = M('User_group')->where(array('id'=>$gid))->getField('func');
		$func = explode(',', $func);
		if (in_array($funname, $func)) {
			return TRUE;
		}else{
			return FALSE;
		}
	}


	/**
	 * 2015-05-21 修改为全局 李祥
	 * 解析处理url
	 * @param String $url	要处理的url
	 * @param Array $params 要替换的参数 如： array('query'=>array('wecha_id'=>'{wechat_id}')) 意思为 把query里 wecha_id的值设置为{wechat_id} 可同时处理多个参数
	 * @return string
	 */
	protected function replaceUrl($url, $params = array()) {
		if (empty($url)) {
			return '';
		}
		$result = '';
		$url = parse_url($url);
		$siteUrl = parse_url($this->siteUrl);
		parse_str(htmlspecialchars_decode($url['query']), $query);
		foreach ($params['query'] as $key => $value) {
			if (isset($query[$key])) { 
				$query[$key] = $value;
			}
		}
		$url['query'] = urldecode(http_build_query($query));
		if (isset($url['scheme'])) {
			$result = $url['scheme'].'://';
		}
		if (isset($url['user'])) {
			$result .= $url['user'].':';
		}
		if (isset($url['pass'])) {
			$result .= $url['pass'].'@';
		}
		if (isset($url['host'])) {
			if ($siteUrl['host'] == $url['host'] || '{siteUrl}' == $url['host']) { // 为本站链接时替换  或 {siteUrl}带http://
				$result='';
			} else {
				$result .= $url['host'];
			}
		}
		if (!empty($url['path'])) { // /index.php 开头 或 index.php开头 侧替换
			if (empty($result)) {
				$flag = true;
				$dirname = dirname($url['path']);
				if ($dirname == $siteUrl['host']) { // 为本站链接时替换 不带 http://
					$dirname = '.';
				}
				if ('{siteUrl}' == $dirname || strstr($dirname, '{siteUrl}')) {	//是替换模板时不添加http://	解决pathinfo 模式会强制加http
					$flag = false;
				}
				$basename = basename($url['path']);
				if ('.' == $dirname && 'index.php' != $basename) { // 如果url 为 纯数字或字母时的处理  如 ： 12341234 abcabc
					$result = $url['path'];
				} else {
					if ('/' == $dirname || '\\' == $dirname || '.' == $dirname) {// 当url 为 /index.php \index.php ./index.php 时的处理
						$flag = false;
						$result .= '{siteUrl}/'.$basename;
					} else {
						$result .= $url['path'];
					}
					
				}
			} else {
				$result .= $url['path']; // 当为 完整url时的处理  如 http://www.pigcms.com/index.php
			}
		} else {
			if (empty($result)) {
				$result = '{siteUrl}/index.php'; // 当url 为空 或不存在时的处理  如 ： '空' ?a=b  其他的特殊字符还未处理
			}
		}
		// 是否加http
		if ($flag) {
			$result = 'http://'.$result;
		}
		if (!empty($url['query'])) {
			$result .= '?'.$url['query'];
		}
		if (!empty($url['fragment'])) {
			$result .= '#'.$url['fragment'];
		}
		return $result;
	}
	
	/**
	 * 2015-05-22 李祥  
	 * 查询是否是短网址
	 * @param Array $data 格式  $this->dwzQuery(array('tinyurl' => 'http://t.cn/123456'));
	 * @return boolean
	 */
	protected function dwzQuery($data) {
		$urls = array('t.cn', 'dwz.cn', 'url.cn', '985.so', 'is.gd', 'url7.me', 'to.ly', 'goo.gl');
		$url = parse_url($data['tinyurl']);
		if (in_array($url['host'], $urls)) {
			return true;
		} else if (in_array(dirname($url['path']), $urls)) {
			return true;
		} else if (in_array($data['tinyurl'], $urls)) {
			return true;
		} else {
			return false;
		}
		// 使用正则的方式，建议使用 会导致其他不是短网址的域名不能增加
		/*
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://dwz.cn/query.php");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);
		$query = json_decode($result, true);
		if($query['status'] == 0) {
			return true;
		} else {
			return false;
		}
		*/
	}

}
?>