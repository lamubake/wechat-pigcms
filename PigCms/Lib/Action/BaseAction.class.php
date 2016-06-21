<?php
class BaseAction extends Action{
	public $isAgent;
	public $home_theme;
	public $reg_needCheck;
	public $minGroupid;
	public $reg_validDays;
	public $reg_groupid;
	public $thisAgent;
	public $agentid;
	public $adminMp;
	public $siteUrl;
	public $staticPath;
	public $owndomain; //curl请求过来的手机站自定义域名
	public $rget; //curl请求过来第三方服务器独立配置手机站标识参数 值默认为3
	public $isQcloud = false;
	public $f_logo; //得到当前logo 区分代理商
	public $isFuwu = 0;
	public $isWechat = 0;
	protected $updateServerDomain;



	protected function _initialize(){
		//检测终端类型
		$userAgent=strtolower($_SERVER['HTTP_USER_AGENT']);
		if (strpos($userAgent,'alipayclient')){
			$this->isFuwu=1;
		}elseif (strpos($userAgent,'micromessenger')){
			$this->isWechat=1;
		}

		define('ALI_FUWU_GROUP',$this->check_fuwu_exist());
		
		//检测电脑PC版
		if(GROUP_NAME == 'Home' && MODULE_NAME == 'Index' && ACTION_NAME == 'index'){
			$this->check_company_website();
		}
		$own_domain=$this->_get('owndomain', 'trim');//用户手机站自定义域名或第三方服务器独立配置手机站标识参数
		$rget=intval($this->_get('rget', 'trim'));//第三方服务器独立配置手机站标识参数
		$this->owndomain=$own_domain && !empty($own_domain) ? $own_domain : false;
		$this->rget=$rget>0 ? $rget : 0;
		$nomsite=$_SESSION[$_SERVER['HTTP_HOST'].'nomsite'];
		if(!$this->owndomain && (GROUP_NAME!="User") && !$nomsite){
		   $this->check_mobile_website();
		}

		if($this->_get('openId') != NULL){
			$this->isQcloud = true;
			if(session('isQcloud') == NULL){
				session('isQcloud',true);
			}
		}

		define('RES',THEME_PATH.'common');
		define('STATICS',TMPL_PATH.'static');

		$this->updateServerDomain = getUpdateServer();

		$this->assign('action',$this->getActionName());
		if (C('STATICS_PATH')){
			$staticPath='';
		}else {
			$staticPath='http://s.404.cn';
		}

		$this->isAgent=0;
		if (C('agent_version')){
			$thisAgent=M('agent')->where(array('siteurl'=>'http://'.$_SERVER['HTTP_HOST']))->find();
			if ($thisAgent){
				$this->isAgent=1;
			}
		}
		if (!$this->isAgent){
			$this->agentid=0;
			if (!C('site_logo')){
				$f_logo='tpl/Home/pigcms/common/images/logo-pigcms.png';
			}else {
				$f_logo=C('site_logo');
			}
			$f_siteName=C('SITE_NAME');
			$f_siteTitle=C('SITE_TITLE');
			//自行增加 易企秀相关eqx 开始
            $eqx_siteUrl=C('eqxsiteurl');
			$eqx_dbUrl=C('eqxdburl');
            $eqx_name=C('eqxname');
            $eqx_user=C('eqxuser');
            $eqx_password=C('eqxpassword');
            //自行增加 易企秀相关eqx  结束
			
			$f_metaKeyword=C('keyword');
			$f_metaDes=C('content');
			$f_qq=C('site_qq');
			$f_ipc=C('ipc');
			$f_qrcode='tpl/Home/pigcms/common/images/ewm2.jpg';
			$f_siteUrl=C('site_url');
			
			$this->home_theme=C('DEFAULT_THEME');
			$f_regNeedMp=C('reg_needmp')=='true'?1:0;
			$this->reg_needCheck=C('ischeckuser')=='false'?1:0;
			$this->minGroupid=1;
			$this->reg_validDays=C('reg_validdays');
			$this->reg_groupid=C('reg_groupid');
			$this->adminMp=C('site_mp');
		}else {
			$this->agentid=$thisAgent['id'];
			$this->thisAgent=$thisAgent;
			if (!C('site_logo')){
				$f_logo='tpl/Home/pigcms/common/images/logo-pigcms.png';
			}else {
				if($thisAgent['sitelogo'] == ''){
					$f_logo=C('site_logo');
				}else{
					$f_logo=$thisAgent['sitelogo'];
				}
			}
			//自行增加 易企秀相关eqx 开始
            $eqx_siteUrl=C('eqxsiteurl');
			$eqx_dbUrl=C('eqxdburl');
            $eqx_name=C('eqxname');
            $eqx_user=C('eqxuser');
            $eqx_password=C('eqxpassword');
            //自行增加 易企秀相关eqx  结束
			
			/*//自行增加 易企秀相关eqx 开始
			$eqxsiteUrl=$thisAgent['eqxsite_url'];
			$eqxdbUrl=$thisAgent['eqxdb_url'];
			$eqxname=$thisAgent['eqx_name'];
			$eqxuser=$thisAgent['eqx_user'];
			$eqxpassword=$thisAgent['eqx_password'];
			//自行增加 易企秀相关eqx  结束
               */
			$f_siteName=$thisAgent['sitename'];
			$f_siteTitle=$thisAgent['sitetitle'];
			$f_metaKeyword=$thisAgent['metakeywords'];
			$f_metaDes=$thisAgent['metades'];
			$f_qq=$thisAgent['qq'];
			$f_qrcode=$thisAgent['qrcode'];
			$f_siteUrl=$thisAgent['siteurl'];
			$f_ipc=$thisAgent['copyright'];
			$this->home_theme=C('DEFAULT_THEME');
			if (file_exists($_SERVER['DOCUMENT_ROOT'].'/tpl/Home/'.'agent_'.$thisAgent['id'])){
				$this->home_theme='agent_'.$thisAgent['id'];
			}
			$f_regNeedMp=$thisAgent['regneedmp'];
			$this->reg_needCheck=$thisAgent['needcheckuser'];
			$minGroup=M('User_group')->where(array('agentid'=>$thisAgent['id']))->order('id ASC')->find();
			$this->minGroupid=$minGroup['id'];
			$this->reg_validDays=$thisAgent['regvaliddays'];
			$this->reg_groupid=$thisAgent['reggid'];
			$this->adminMp=$thisAgent['mp'];
		}
		if(empty($staticPath) && ($rget==3) && !empty($own_domain)){
		   $staticPath=$f_siteUrl;  /**第三方服务器独立配置手机站时文件资源地址需要本站的**/
		}
		$this->staticPath=$staticPath;
		$this->assign('staticPath',$staticPath);
		define('STATICS',$staticPath.'/tpl/static');		//********************/
		if (!$f_siteUrl){
			$f_siteUrl='http://'.$_SERVER['SERVER_NAME'];
		}
		$this->siteUrl=$f_siteUrl;
        $this->f_logo=$f_logo;
		$this->assign('f_logo',$f_logo);
		//自行增加 易企秀相关eqx 开始
		$this->assign('eqx_siteUrl',$eqx_siteUrl);
		$this->assign('eqx_dbUrl',$eqx_dbUrl);
		$this->assign('eqx_name',$eqx_name);
		$this->assign('eqx_user',$eqx_user);
		$this->assign('eqx_password',$eqx_password);
        //自行增加 易企秀相关eqx  结束
		
		$this->assign('f_siteName',$f_siteName);
		$this->assign('f_siteTitle',$f_siteTitle);
		$this->assign('f_metaKeyword',$f_metaKeyword);
		$this->assign('f_metaDes',$f_metaDes);
		$this->assign('f_qq',$f_qq);
		$this->assign('f_qrcode',$f_qrcode);
		$this->assign('f_siteUrl',$f_siteUrl);
		$this->assign('f_regNeedMp',$f_regNeedMp);
		$this->assign('f_ipc',$f_ipc);
		$this->assign('reg_validDays',$this->reg_validDays);
		//******************/
	}

	//添加所有内容,包含关键词
	protected function all_insert($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->add();
			if($id){
				$m_arr=array('Img','Text','Voiceresponse','Ordering','Lottery','Host','Product','Selfform','Panorama','Wedding','Vote','Estate','Reservation','Greeting_card');
				if(in_array($name,$m_arr)){
					//isset($_POST['precisions']) ? $precisions = 1: $precisions = 0 ;
					$this->handleKeyword($id,$name,$_POST['keyword'],intval($_POST['precisions']));

				}

				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	//单一信息添加
	protected function insert($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->add();
			if($id==true){
				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	//单子信息修改
	protected function save($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->save();
			if($id==true){
				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	public function rloginck(){
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
	}
	//修改所有内容,包含关键词
	protected function all_save($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->save();
			if($id){
				$m_arr=array(
				'Img',
				'Text',
				'Voiceresponse',
				'Ordering','Lottery',
				'Host','Product',
				'Selfform',
				'Panorama',
				'Wedding',
				'Vote',
				'Estate',
				'Reservation',
				'Carowner','Carset'
				);
				if(in_array($name,$m_arr)){
					$this->handleKeyword(intval($_POST['id']),$name,$_POST['keyword'],intval($_POST['precisions']));

				}
				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	protected function del_id($name='',$jump=''){
		$name=$name?$name:MODULE_NAME;
		$jump=empty($name)?MODULE_NAME.'/index':$jump;
		$db=D($name);
		$where['id']=$this->_get('id','intval');
		$where['token']=session('token');
		if($db->where($where)->delete()){
			$this->success('操作成功',U($jump));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	protected function all_del($id,$name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->delete($id)){
			$this->ajaxReturn('操作成功',U(MODULE_NAME.$back));
		}else{
			$this->ajaxReturn('操作失败',U(MODULE_NAME.$back));
		}
	}

	//通用添加关键词 支持逗号和空格分隔关键词
	public function handleKeyword($id,$module,$keyword='',$precisions=0,$delete=0){
		$db=M('Keyword');
		$token = session('token');
		$db->where(array('pid'=>$id,'token'=>$token,'module'=>$module))->delete();
		$keyword = trim(trim($keyword),',');

		if (!$delete){

			$data['pid']=$id;
			$data['module']=$module;
			$data['token']=$token;

			$flag1 = strpos($keyword,',');
			$flag2 = strpos($keyword,' ');

			if( $flag1 === false &&  $flag2 === false ){
				$pk = explode('|',$keyword);
				if(count($pk) == 2){
					$data['precisions'] = $pk[1];
					$data['keyword'] = $pk[0];
				}else{
					$data['precisions'] = $precisions;
					$data['keyword'] = $keyword;
				}

				$db->add($data);

			}else{
				//关键词 关键|1 关键词|0
				if($flag1 === false){
					$keyword = explode(' ', $keyword);
					foreach ($keyword as $k => $v){
						$pk = explode('|',$v);
						if(count($pk) == 2){
							$data['precisions'] = $pk[1];
							$data['keyword'] = $pk[0];
						}else{
							$data['precisions'] = $precisions;
							$data['keyword'] = $v;
						}
						$db->add($data);
					}


				}else{

					$keyword = explode(',', $keyword);
					foreach ($keyword as $k => $v){
						$pk = explode('|',$v);
						if(count($pk) == 2){
							$data['precisions'] = $pk[1];
							$data['keyword'] = $pk[0];
						}else{
							$data['precisions'] = $precisions;
							$data['keyword'] = $v;
						}
						$db->add($data);
					}
				}
			}
		}
	}

	//判断是否是企业版的PC网站
	protected function check_company_website(){
		//如果当前网址和平台网址一样，则不查询。
		if (C('agent_version')){
			$agent=M('Agent')->where(array('siteurl'=>'http://'.$_SERVER['HTTP_HOST']))->find();
		}
		if (!$agent&&C('site_url')){
			$site_domain = parse_url(C('site_url'));
			//没有缓存去读数据库
				//$now_website = S('now_website'.$now_host);
				$now_website =  M('Pc_site')->where(array('site'=>$_SERVER['HTTP_HOST']))->find();
				$now_host = $now_website['site'];
				S('now_website'.$now_host);
			if($site_domain['host'] != $now_host){
				$now_website = $now_host;
				
				if(empty($now_website)){
					$group_list = explode(',',C('APP_GROUP_LIST'));
					if(in_array('Web',$group_list)){
						$database_pc_site = D('Pc_site');
						$condition_pc_site['site'] = $now_host;
						$now_website = $database_pc_site->field(true)->where($condition_pc_site)->find();
					}
				}
				if(!empty($now_website)){
					$_SESSION['now_website'] = $now_website;
					R('Web/Web_index/index');
					exit;
				}
			}
		}
	}

   	//判断是否是手机自定义域名壳
    protected function check_mobile_website() {
        /*$mb_token = $this->_get('token', 'trim');
        if ($mb_token && !preg_match("/^[0-9a-zA-Z]{3,42}$/", $mb_token)) {
            exit('error token');
        }*/
        $db_mobilesite = M('Mobilesite');
		$tmp =$_SESSION[$_SERVER['HTTP_HOST']];
		if(!empty($tmp)){
		  $tmp =unserialize($tmp);
		}else{
          $tmp = $db_mobilesite->where(array('owndomain' => $_SERVER['HTTP_HOST']))->find();
		}
		$bid = $this->_get('bid') ? intval($this->_get('bid', 'trim')) : 0;
        if (is_array($tmp) && !empty($tmp) && !empty($tmp['token'])) {
			    $_SESSION[$tmp['owndomain']]=serialize($tmp);
				$_SESSION[$_SERVER['HTTP_HOST'].'nomsite']=0;
				$siteUrl=preg_replace('/https?:\/\//','',C('site_url'));
				$siteUrl=trim($siteUrl);
				$siteUrl=rtrim($siteUrl,'/');/****解决人家 换域名后带来的问题*****/
				$admindomain=trim($tmp['admindomain']);
				if($siteUrl!=$admindomain){
				   $admindomain=$siteUrl;
				}
			    if($_SERVER["QUERY_STRING"]=="" || !strpos($_SERVER["REQUEST_URI"],"g=Wap")|| !strpos($_SERVER["REQUEST_URI"],"token=".$tmp['token'])){
				  $request_url='http://' . $admindomain."/index.php?g=Wap&m=Index&a=index&token=".$tmp['token']. "&rget=3&owndomain=" . $tmp['owndomain'];
				}else{
                  $request_url = 'http://' . $admindomain . $_SERVER['REQUEST_URI'] . "&rget=3&owndomain=" . $tmp['owndomain'];
				}
				if(isset($_COOKIE['qmjjr_loginuserid'.$bid])) $request_url=$request_url."&loginuserid=".$_COOKIE['qmjjr_loginuserid'.$bid];
                if (IS_POST) {
                    $responsearr = $this->httpRequest($request_url, REQUEST_METHOD, $_POST);
                } else {
                    $responsearr = $this->httpRequest($request_url, REQUEST_METHOD, null);
                }
				
                $tmpcontent = $responsearr['1'];
				$httpcode=intval(trim($responsearr['0']));
				if(in_array($httpcode,array(301,302))){
					header('Location:'. $responsearr['2']['url']);
					exit();
				}
                /* * ajax请求时 json封装带过来的数据 是否需要解析* */
                /* * 格式为**{"analyze":1,"error":0,"msg":"opt_cookie","data":{"ckkey":"bfdhdfhdf","ckv":2,"expire":3600}}**这样的json* */
                /* * analyze为数字：指明是否需要解析 大于0的值时需要解析 0不需要解析，请不要写成布尔值*** */
                /* * error为数字：指明一个状态**msg为字符串：指明操作**data为数据库：指明要操作的数据* */
                $jsonREG = '/^\{[\"\']analyze[\"\']\:\d\,[\"\']error[\"\']\:\d\,[\"\']msg[\"\']\:(.*)data[\"\']\:(.*)\}\}$/i';
                if (preg_match($jsonREG, $tmpcontent, $matches)) {
                    $jsonstr = $matches[0];
                    $jsonarr = !empty($jsonstr) ? json_decode($jsonstr, TRUE) : false;
                    if ($jsonarr && is_array($jsonarr)) {
                        $is_analyze = isset($jsonarr["analyze"]) ? intval($jsonarr["analyze"]) : 0;
                        if ($is_analyze > 0) {
                            $tmpcontent = $jsonarr["error"];

                            switch ($jsonarr["msg"]) {
                                case "opt_cookie":
                                    $tmpdata = $jsonarr["data"];
                                    $expire = intval($tmpdata["expire"]);
                                    $expire = $expire > 0 ? time() + $expire : 0;
                                    setcookie('qmjjr_loginuserid'.$bid, $tmpdata["ckv"], $expire, "/", $_SERVER["HTTP_HOST"]);
                                    break;
                                default:

                                    break;
                            }
                        }
                    }
                }
                $tmpcontent=str_replace($admindomain,$_SERVER['HTTP_HOST'],$tmpcontent);
               
                $_SESSION['otherSource']=1;
               echo $tmpcontent;
                if (!IS_AJAX && !empty($tmp['tjscript'])) {
                    $tjscript = base64_decode($tmp['tjscript']);
                    $tjscript = urldecode(str_replace('jshtmltag', 'script', $tjscript));
                    echo $tjscript;
                }
                exit();
        }else{
		   $_SESSION[$_SERVER['HTTP_HOST'].'nomsite']=1;
		}
    }

   public function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
        /* $Cookiestr = "";  * cUrl COOKIE处理* 
        if (!empty($_COOKIE)) {
            foreach ($_COOKIE as $vk => $vv) {
                $tmp[] = $vk . "=" . $vv;
            }
            $Cookiestr = implode(";", $tmp);
        }*/
		$method=strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
		$ssl =preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
		if($ssl){
		  curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		  curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
		}
		//curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
		curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
		$requestinfo=curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);

            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response,$requestinfo);
    }

    public function check_fuwu_exist()
    {
    	$group_list = explode(',',C('APP_GROUP_LIST'));
    	
    	return in_array('Fuwu',$group_list);
    }


}

