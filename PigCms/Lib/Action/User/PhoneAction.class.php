<?php

class PhoneAction extends UserAction {

    public function _initialize() {
        parent::_initialize();
        $this->canUseFunction('Phone');
    }

    public function index() {
        $this->baseSet();
    }

    public function baseSet() {
        $info = M('Mobilesite')->where(array('token' => session('token')))->find();
        if (is_array($info) && !empty($info['tjscript'])) {
            $info['tjscript'] = base64_decode($info['tjscript']);
            $info['tjscript'] = urldecode(str_replace('jshtmltag', 'script', $info['tjscript']));
        }
		$autodomain='';
        $mbid = is_array($info) && $info['id'] > 0 ? $info['id'] : 0;
		if(in_array(C('server_topdomain'),array('pigcms.cn','pigcms.com'))){
		   $autodomain='m_'.session('token').'.maopan.com';//pigcms演示站自动分配域名
		}
		/*$autodomain='m_'.session('token').'.maopan.com';//pigcms演示站自动分配域名*/
		if(!empty($autodomain)){
		   $nomodify=true;
		}else{
		   $nomodify=false;
		}
        $this->assign('infos', $info);
		$this->assign('autodomain', $autodomain);
		$this->assign('nomodify', $nomodify);
        $this->assign('mbid', $mbid);
        $this->display('baseSet');
    }

    public function saveData() {
        $id = $this->_post("id", 'trim');

        $owndomain = $this->_post("dnm", 'trim');
		if($owndomain=='nomodify'){
		    $owndomain='m_'.session('token').'.maopan.com';
		}elseif (empty($owndomain) || preg_match('/[^A-Za-z0-9_\.\-]/', $owndomain)) {
            $this->dexit(array('error' => 1, 'msg' => '域名格式不对'));
        }
        //$tjscript=urldecode($this->_post("tjscr",'trim'));
        $tjscript = $this->_post("tjscr", 'trim');
        if (!empty($tjscript)) {
            $tjscript = str_replace('script', 'jshtmltag', $tjscript);
            //$tjscript=htmlspecialchars($tjscript,ENT_QUOTES);
            $tjscript = base64_encode($tjscript);
        } else {
            $tjscript = '';
        }
		$token=trim(session('token'));
        $db_mobilesite = M('Mobilesite');
		$tmps = $db_mobilesite->where(array('owndomain'=>$owndomain))->select();
		if(!empty($tmps)){
		   if(count($tmps)>1){
		      $this->dexit(array('error' => 1, 'msg' => '此域名已经被绑定过了'));
		   }else if($tmps[0]['token']!=$token){
		     $this->dexit(array('error' => 1, 'msg' => '此域名已经被绑定过了'));
		   }
		}
        $tmp = $db_mobilesite->where(array('id' => $id, 'token' => $token))->find();
			$siteUrl=preg_replace('/https?:\/\//','',C('site_url'));
			$siteUrl=trim($siteUrl);
			$siteUrl=rtrim($siteUrl,'/');/****解决人家 换域名后带来的问题*****/
        if (is_array($tmp) && !empty($tmp)) {
			$updatas=array('tjscript' => $tjscript,'owndomain'=>$owndomain);
			if($siteUrl!=$tmp['admindomain']) $updatas['admindomain']=$siteUrl;
            $db_mobilesite->where(array('id' => $tmp['id']))->save($updatas);
            $this->dexit(array('error' => 0, 'msg' => $tmp['id']));
        } else {
            $data['token'] = $token;
            $data['owndomain'] = $owndomain;
            $data['admindomain'] = $siteUrl;
            $data['tjscript'] = $tjscript;
            $data['addtime'] = time();
            $mbid = $db_mobilesite->add($data);
            if ($mbid > 0) {
                $this->dexit(array('error' => 0, 'msg' => $mbid));
            }
        }
        $this->dexit(array('error' => 1, 'msg' => '操作失败'));
    }

    //生成独立部署index.php文件
    function downloadFile() {
        //$tjscript=$this->_post("tjscr",'trim');
        $tjscript = base64_encode($this->_get("tjscr", 'trim'));
        $content = '<?php
	/* * *服务器必须要安装php libcurl功能扩展库（Client URL Library）****** */
	/* * *将此文件放在站点根目录下并改名成 index.php即可****** */
	/* * *Author LiHongShun**2015-01-07**** */
	/* * *这种方式 cookie存在跨域获取不到问题，目前已解决全民经纪人cookie存取**** */
	/* * *其他功能如用到cookie 存在跨域问题 请参照下面json格式的解析处理**** */
	/* * *功能程序员端可用BaseAction 中$owndomain和$rget变量来判断是否是手机站独立部署请求的**** */
	/* * *支持ajax的post和get请求**** */
	define("REQUEST_METHOD", strtoupper(trim($_SERVER["REQUEST_METHOD"])));
	define("IS_AJAX", ((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") || !empty($_POST["ajax"]) || !empty($_GET["ajax"])) ? true : false);
	$tjscript="' . $tjscript . '";  /**第三方统计js脚本**/
	$token="' . session('token') . '";
	$Request_url="";
	$Request_site="' . $this->siteUrl . '";
	if(isset($_SERVER["QUERY_STRING"]) && !empty($_SERVER["QUERY_STRING"])){
		$Request_url = $Request_site."/index.php?".$_SERVER[\'QUERY_STRING\'] . "&rget=3&owndomain=" . $_SERVER[\'HTTP_HOST\'];
	}else if($_SERVER["QUERY_STRING"]=="" || !strpos($_SERVER["REQUEST_URI"],"g=Wap")){
		$Request_url=$Request_site."/index.php?g=Wap&m=Index&a=index&token=".$token."&rget=3&owndomain=" . $_SERVER[\'HTTP_HOST\'];
	}else{
		$REQUEST_Uri=preg_replace(\'/\/(.*)\?/i\',"/index.php?",$_SERVER["REQUEST_URI"]);  /***支持不带index.php URL请求***/
		$Request_url = $Request_site . $REQUEST_Uri . "&rget=3&owndomain=" . $_SERVER[\'HTTP_HOST\'];
	}
	if(strpos($Request_url, "token=".$token) && strpos($Request_url, "g=Wap") && strpos($Request_url, "m=") && strpos($Request_url, "a=")){
	if (REQUEST_METHOD == "POST") {
		if(IS_AJAX) $_POST["ajax"]=1;
		$responsearr = httpRequest($Request_url, REQUEST_METHOD, $_POST);
		$tmpcontent=$responsearr["1"];
	} else {
		if(IS_AJAX) $Request_url=$Request_url."&ajax=1";
		$responsearr = httpRequest($Request_url, REQUEST_METHOD, null);
		/**get方式请求页面时**正则匹配换掉 图片 css js等引用文件用的相对地址成绝对地址**POST方式不需要替换**/
		$tplstatic1REG=\'/(src|href)=([\"\\\'])([\.]{0,2}[\/]?)tpl\/static/i\';
		$tplstatic2REG=\'/url\(([\"\\\'])([\.]{0,2}[\/]?)tpl\/static/i\';
		$tplWap1REG=\'/(src|href)=([\"\\\'])([\.]{0,2}[\/]?)tpl\/wap/i\';
		$tplWap2REG=\'/url\(([\"\\\'])([\.]{0,2}[\/]?)tpl\/wap/i\';
		$tmpcontent=preg_replace($tplstatic1REG,"\\\\1=\\\\2".$Request_site."/tpl/static",$responsearr["1"]);
		$tmpcontent=preg_replace($tplstatic2REG,"url(\\\\1".$Request_site."/tpl/static",$tmpcontent);
		$tmpcontent=preg_replace($tplWap1REG,"\\\\1=\\\\2".$Request_site."/tpl/Wap",$tmpcontent);
		$tmpcontent=preg_replace($tplWap2REG,"url(\\\\1".$Request_site."/tpl/Wap",$tmpcontent);

		/*$tmpcontent=preg_replace($tplstatic1REG,"$1=$2".$Request_site."/tpl/static",$responsearr["1"]);
		$tmpcontent=preg_replace($tplstatic2REG,"url($1".$Request_site."/tpl/static",$tmpcontent);
		$tmpcontent=preg_replace($tplWap1REG,"$1=$2".$Request_site."/tpl/Wap",$tmpcontent);
		$tmpcontent=preg_replace($tplWap2REG,"url($1".$Request_site."/tpl/Wap",$tmpcontent);*/
	}
	 /***Header("Access-Control-Allow-Origin:$Request_site");****/
		/**ajax请求时 json封装带过来的数据 是否需要解析**/
		/**格式为**{"analyze":1,"error":0,"msg":"opt_cookie","data":{"ckkey":"bfdhdfhdf","ckv":2,"expire":3600}}**这样的json**/
		/**analyze为数字：指明是否需要解析 大于0的值时需要解析 0不需要解析，请不要写成布尔值****/
		/**error为数字：指明一个状态**msg为字符串：指明操作**data为数据库：指明要操作的数据**/
		/***目前仅支持cookie存取，如有需要以后再扩展此功能******/
		$jsonREG=\'/^\{[\"\\\']analyze[\"\\\']\:\d\,[\"\\\']error[\"\\\']\:\d\,[\"\\\']msg[\"\\\']\:(.*)data[\"\\\']\:(.*)\}\}$/i\';
		if(preg_match($jsonREG,$tmpcontent,$matches)){
		   $jsonstr=$matches[0];
		   $jsonarr=!empty($jsonstr)? json_decode($jsonstr,TRUE):false;
		   if($jsonarr && is_array($jsonarr)){
			  $is_analyze=isset($jsonarr["analyze"]) ? intval($jsonarr["analyze"]):0;
			  if($is_analyze > 0){
		       $tmpcontent=$jsonarr["error"];
			   
			   switch($jsonarr["msg"]){
				  case "opt_cookie":
					  $tmpdata=$jsonarr["data"];
					  $expire=intval($tmpdata["expire"]);
					  $expire=$expire>0 ? time()+$expire :0;
					  setcookie($tmpdata["ckkey"], urlencode(json_encode($tmpdata["ckv"])), $expire, "/",$_SERVER["HTTP_HOST"]);
					  break;
				default:
					
					break;
				}
		      }
		   }
		}
	echo $tmpcontent; /*输出页面或数据*/
	if (!IS_AJAX && !empty($tjscript)) {
		$tjscript = base64_decode($tjscript);
		echo $tjscript;
	}
	exit();
	}else{
		header("Content-type: text/html; charset=utf-8");
		$tipsstr="<style type=\'text/css\'>.mytips{height: 60%;margin: 20px;width: 100%;} .mytips p{font-size: 18px;font-weight: bold;margin: 10px;padding-top: 10px;}</style>";
		$tipsstr.="<div class=\'mytips\'><p>请求地址出错！</p></div>";
		echo $tipsstr;
	}

	function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
		$Cookiestr=""; /**cUrl COOKIE处理**/
		if(!empty($_COOKIE)){
		   foreach($_COOKIE as $vk=>$vv){
		       $tmp[]=$vk."=".$vv;
		   }
			$Cookiestr=implode(";",$tmp);
		}
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
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLINFO_HEADER_OUT, true);
		curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr);/***COOKIE带过去***/
		$response = curl_exec($ci);
		$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);

		if ($debug) {
			echo "=====post data======\r\n";
			var_dump($postfields);
			echo "=====info===== \r\n";
			print_r(curl_getinfo($ci));

			echo "=====$response=====\r\n";
			print_r($response);
		}
		curl_close($ci);
		return array($http_code, $response);
	}

?>';

        $fname = 'index.php';
        header("Pragma: public"); // required 
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        //header('Content-type: image/png');
		//header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition: attachment; filename={$fname}");
        header("Content-Transfer-Encoding: binary");
        echo $content;
    }

    //json格式输出封装函数
    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }

}

?>