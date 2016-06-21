<?php
class YundabaoAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
		$this->canUseFunction("Yundabao");
		
		//加载数据库
		$this->m_wxuser = M("wxuser");
		$this->m_users = M("yundabao_users");
		$this->m_yundabao = M("yundabao");
		
		/**
		*API的url
		*/
		//获取AccessToken的url
		$url['AccessToken'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=AgentUserLogin";
		//获取UserId的url
		$url['UserId'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=AgentUserId";
		//获取价格年限的url
		$url['PriceYears'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=ServiceList&accesstoken=";
		//创建应用url
		$url['CreateApp'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=CreateApp";
		//应用列表url
		$url['AppList'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=AppList";
		//修改应用url
		$url['ModifyApp'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=ModifyApp";
		//重新生成url
		$url['RebuildApp'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=RebuildApp";
		//删除应用url
		$url['DeleteApp'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=DeleteApp";
		//应用数据url
		$url['DataStatistics'] = "http://openapi.yundabao.com/api/mode3api.ashx?action=DataStatistics";
		//把url赋值给变量apiurl
		$this->apiurl = $url;
	}
	//创建应用
	public function add(){
		if($_GET['AppId'] == ""){
			$PriceYears = $this->yundabaoapi('PriceYears');
			$this->assign("PriceYears",$PriceYears['ServicesData']);
		}else{
			$where['token'] = $this->token;
			$where['AppId'] = $_GET['AppId'];
			$yundabao = $this->m_yundabao->where($where)->find();
			if($yundabao['endtime'] > time()){
				$this->assign("daoshi",($yundabao['endtime'] - time())+1);
			}
			$this->assign("yundabao",$yundabao);
		}
		$dingjiyuming = C('server_topdomain');
		$this->assign("dingjiyuming",$dingjiyuming);
		$this->display();
	}
	//二维码
	public function QRcode(){
		include './PigCms/Lib/ORG/phpqrcode.php';
		$url = "http://www.yundabao.com/download.aspx?id=".$_GET['AppId'];
		QRcode::png($url,false,1,11);
	}
	//下载跳转
	public function download(){
		if($_GET['filetype'] == ""){
			$url = "http://www.yundabao.com/download.aspx?id=".$_GET['AppId'];
		}else{
			$url = "http://www.yundabao.com/download.aspx?id=".$_GET['AppId']."&filetype=".$_GET['filetype'];
		}
		$this->assign("url",$url);
		$this->display();
	}
	//执行添加
	public function doadd(){
		//$url = $_POST['NavUrlhttp'].$_POST['NavUrl'];
		//$link=$this->convertLink($url);
		$url = $this->convertLink($_POST['NavUrl']);
		if(strstr($url,$_POST['NavUrlhttp'])){
			$link = $url;
		}else{
			$link = $_POST['NavUrlhttp'].$url;
		}
		$AppName = $_POST['AppName'];
		$AppNote = $_POST['AppNote'];
		if($_POST['HideTop'] == 0){
			$HideTop = "false";
		}else{
			$HideTop = "true";
		}
		$AppVer = $_POST['Version'];
		$AppYears = $_POST['Years'];
		$NavUrl = $link;
		if($_POST['IconName_url'] == ""){
			$IconType = $_POST['IconType'];
			if($_POST['IconName'] == ""){
				$IconName = mb_substr($_POST['AppName'],0,1,'utf-8');
			}else{
				$IconName = $_POST['IconName'];
			}
		}else{
			$IconType = 0;
			$IconName = $_POST['IconName_url'];
		}
		if($_POST['LogoName_url'] == ''){
			$LogoType = 1;
			if($_POST['LogoName'] == ""){
				$LogoName = $_POST['AppName'];
			}else{
				$LogoName = $_POST['LogoName'];
			}
		}else{
			$LogoType = 0;
			$LogoName = $_POST['LogoName_url'];
		}
		$BgColor = $_POST['BgColor'];
		if($_POST['SplashName'] == ""){
			$SplashType = $_POST['SplashType'];
			$SplashName = "";
		}else{
			$SplashType = 0;
			$SplashName = $_POST['SplashName'];
		}
		$add['token'] = $this->token;
		$add['AppName'] = $_POST['AppName'];
		$add['AppNote'] = $_POST['AppNote'];
		$add['HideTop'] = $_POST['HideTop'];
		$add['IconType'] = $_POST['IconType'];
		$add['IconName'] = $_POST['IconName'];
		$add['IconName_url'] = $_POST['IconName_url'];
		$add['LogoName'] = $_POST['LogoName'];
		$add['LogoName_url'] = $_POST['LogoName_url'];
		$add['BgColor'] = $_POST['BgColor'];
		$add['SplashType'] = $_POST['SplashType'];
		$add['SplashName'] = $_POST['SplashName'];
		$id = $this->m_yundabao->add($add);
		$data = "&appname={$AppName}&appnote={$AppNote}&hidetop={$HideTop}&appver={$AppVer}&appyears={$AppYears}&navurl={$NavUrl}&icontype={$IconType}&iconname={$IconName}&logotype={$IconType}&logoname={$LogoName}&splashtype={$SplashType}&splashname={$SplashName}&bgcolor={$BgColor}";
		$CreateApp = $this->yundabaoapi("CreateApp",$data);
		//dump($CreateApp);exit;
		$save['AppId'] = $CreateApp['AppId'];
		$save['endtime'] = $CreateApp['SecNums'] + time();
		$update = $this->m_yundabao->where(array('imicms_id'=>$id))->save($save);
		$this->redirect("Yundabao/add",array('token'=>$this->token,'AppId'=>$CreateApp['AppId']));
	}
	//应用列表
	public function applist(){
		if($_GET['type'] == "" || $_GET['type'] == '0'){
			$type = 0;
		}elseif($_GET['type'] == 1){
			$type = 1;
		}elseif($_GET['type'] == 2){
			$type = 2;
		}
		$data_count = "&type=".$type;
		$AppList_count = $this->yundabaoapi("AppList",$data_count);
		$count = count($AppList_count);
		import('ORG.Util.Page');
		$page = new Page($count,8);
		$show = $page->show();
		$this->assign('page',$show);
		if($_GET['p'] == ""){
			$_GET['p'] = 1;
		}
		$data = "&type=".$type."&pagesize=8&pageindex=".$_GET['p'];
		$AppList = $this->yundabaoapi("AppList",$data);
		$this->assign("AppList",$AppList);
		$this->display();
	}
	//应用修改
	public function update(){
		if($_GET['AppId'] == ""){
			$this->redirect("Yundabao/applist",array('token'=>$this->token));
			exit;
		}
		$where['token'] = $this->token;
		$where['AppId'] = $_GET['AppId'];
		$yundabao = $this->m_yundabao->where($where)->find();
		$this->assign("yundabao",$yundabao);
		$this->display();
	}
	//执行修改应用
	public function doupdate(){
		$AppName = $_POST['AppName'];
		$AppNote = $_POST['AppNote'];
		if($_POST['HideTop'] == 0){
			$HideTop = "false";
		}else{
			$HideTop = "true";
		}
		if($_POST['IconName_url'] == ""){
			$IconType = $_POST['IconType'];
			if($_POST['IconName'] == ""){
				$IconName = mb_substr($_POST['AppName'],0,1,'utf-8');
			}else{
				$IconName = $_POST['IconName'];
			}
		}else{
			$IconType = 0;
			$IconName = $_POST['IconName_url'];
		}
		if($_POST['LogoName_url'] == ''){
			$LogoType = 1;
			if($_POST['LogoName'] == ""){
				$LogoName = $_POST['AppName'];
			}else{
				$LogoName = $_POST['LogoName'];
			}
		}else{
			$LogoType = 0;
			$LogoName = $_POST['LogoName_url'];
		}
		$BgColor = $_POST['BgColor'];
		if($_POST['SplashName'] == ""){
			$SplashType = $_POST['SplashType'];
			$SplashName = "";
		}else{
			$SplashType = 0;
			$SplashName = $_POST['SplashName'];
		}
		$AppId = $_POST['AppId'];
		$save['AppName'] = $_POST['AppName'];
		$save['AppNote'] = $_POST['AppNote'];
		$save['HideTop'] = $_POST['HideTop'];
		$save['IconType'] = $_POST['IconType'];
		$save['IconName'] = $_POST['IconName'];
		$save['IconName_url'] = $_POST['IconName_url'];
		$save['LogoName'] = $_POST['LogoName'];
		$save['LogoName_url'] = $_POST['LogoName_url'];
		$save['BgColor'] = $_POST['BgColor'];
		$save['SplashType'] = $_POST['SplashType'];
		$save['SplashName'] = $_POST['SplashName'];
		$where['token'] = $this->token;
		$where['AppId'] = $_POST['AppId'];
		$update = $this->m_yundabao->where($where)->save($save);
		$data = "&appid={$AppId}&appname={$AppName}&appnote={$AppNote}&hidetop={$HideTop}&icontype={$IconType}&iconname={$IconName}&logotype={$LogoType}&logoname={$LogoName}&bgcolor={$BgColor}&splashtype={$SplashType}&splashname={$SplashName}";
		$ModifyApp = $this->yundabaoapi("ModifyApp",$data);
		//dump($ModifyApp);exit;
		$save2['endtime'] = time() + $ModifyApp['SecNums'];
		$update = $this->m_yundabao->where($where)->save($save2);
		$this->redirect("Yundabao/add",array('token'=>$this->token,'AppId'=>$ModifyApp['AppId']));
	}
	//重新生成
	public function RebuildApp(){
		$data = "&appid=".$_GET['AppId'];
		$RebuildApp = $this->yundabaoapi("RebuildApp",$data);
		$where['token'] = $this->token;
		$where['AppId'] = $_GET['AppId'];
		$save2['endtime'] = time() + $RebuildApp['SecNums'];
		$update = $this->m_yundabao->where($where)->save($save2);
		$this->redirect("Yundabao/add",array('token'=>$this->token,'AppId'=>$_GET['AppId']));
	}
	//删除应用
	public function DeleteApp(){
		$data = "&appid=".$_GET['AppId'];
		$DeleteApp = $this->yundabaoapi("DeleteApp",$data);
		if($DeleteApp['Status'] == '0'){
			$where['token'] = $this->token;
			$where['AppId'] = $_GET['AppId'];
			$del = $this->m_yundabao->where($where)->delete();
			$this->success("删除成功",U("Yundabao/applist",array('token'=>$this->token)));
		}else{
			$this->error("删除失败");
		}
	}
	//应用数据
	public function data(){
		if($_GET['AppId'] == ""){
			$this->redirect("Yundabao/applist",array('token'=>$this->token));
			exit;
		}
		$data = "&appid=".$_GET['AppId'];
		$DataStatistics = $this->yundabaoapi("DataStatistics",$data);
		$this->assign("data",$DataStatistics);
		$where['token'] = $this->token;
		$where['AppId'] = $_GET['AppId'];
		$yundabao = $this->m_yundabao->where($where)->find();
		$this->assign("yundabao",$yundabao);
		$this->display();
	}
	//云打包api执行
	protected function yundabaoapi($type,$data){
		//获取AccessToken
		$data_AccessToken = "Username=39886116@qq.com&PassWord=123456";
		$myAccessToken = json_decode($this->https_request($this->apiurl['AccessToken'],$data_AccessToken), true);
		$AccessToken = $myAccessToken['AccessToken'];
		//获取UserId
		$where_wxuser['token'] = $this->token;
		$id = $this->m_wxuser->where($where_wxuser)->getField('id');
		$where_users['token'] = $this->token;
		$users = $this->m_users->where($where_users)->find();
		if($users == ""){
			$add_users['token'] = $this->token;
			$add_users['username'] = C('server_topdomain')."_".$id."_".time();
			$add_users['addtime'] = time();
			$id_users = $this->m_users->add($add_users);
			$username = $add_users['username'];
		}else{
			$username = $users['username'];
		}
		$data_UserId = "AccessToken=".$myAccessToken['AccessToken']."&UserName=".$username;
		$myUserId = json_decode($this->https_request($this->apiurl['UserId'],$data_UserId), true);
		$UserId = $myUserId['UserId'];
		//应用操作
		switch($type){
			case 'PriceYears':
				$url_PriceYears = $this->apiurl['PriceYears'].$AccessToken;
				$PriceYears = json_decode($this->https_request($url_PriceYears), true);
				return $PriceYears;
			break;
			case 'CreateApp':
				$data_CreateApp = "accesstoken={$AccessToken}&userid={$UserId}".$data;
				$CreateApp = json_decode($this->https_request($this->apiurl['CreateApp'],$data_CreateApp), true);
				return $CreateApp;
			break;
			case 'AppList':
				$data_AppList = "accesstoken={$AccessToken}&userid={$UserId}".$data;
				$AppList = json_decode($this->https_request($this->apiurl['AppList'],$data_AppList), true);
				return $AppList['Data'];
			break;
			case 'ModifyApp':
				$data_ModifyApp = "accesstoken={$AccessToken}&userid={$UserId}".$data;
				$ModifyApp = json_decode($this->https_request($this->apiurl['ModifyApp'],$data_ModifyApp), true);
				return $ModifyApp;
			break;
			case 'RebuildApp':
				$data_RebuildApp = "accesstoken={$AccessToken}&userid={$UserId}".$data;
				$RebuildApp = json_decode($this->https_request($this->apiurl['RebuildApp'],$data_RebuildApp), true);
				return $RebuildApp;
			break;
			case 'DeleteApp':
				$data_DeleteApp = "accesstoken={$AccessToken}&userid={$UserId}".$data;
				$DeleteApp = json_decode($this->https_request($this->apiurl['DeleteApp'],$data_DeleteApp), true);
				return $DeleteApp;
			break;
			case 'DataStatistics':
				$data_DataStatistics = "accesstoken={$AccessToken}&userid={$UserId}".$data;
				$DataStatistics = json_decode($this->https_request($this->apiurl['DataStatistics'],$data_DataStatistics), true);
				return $DataStatistics['Data'];
			break;
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
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
?>