<?php
require_once FUWU_PATH.'Gateway.php';
require_once FUWU_PATH.'HttpRequst.php';
require_once FUWU_PATH.'config.php';
require_once FUWU_PATH.'AlipaySign.php';

if (get_magic_quotes_gpc()) {
   foreach ( $_POST as $key => $value ) {
      $_POST [$key] = stripslashes ( $value );
   }
   foreach ( $_GET as $key => $value ) {
      $_GET [$key] = stripslashes ( $value );
   }
   foreach ( $_REQUEST as $key => $value ) {
      $_REQUEST [$key] = stripslashes ( $value );
   }
}
//日志记录下受到的请求
file_put_contents("log.txt", var_export($_POST,true)."\r\n",FILE_APPEND);
file_put_contents("log.txt", var_export($_GET,true)."\r\n",FILE_APPEND);

$sign = HttpRequest::getRequest ( "sign" );
$sign_type = HttpRequest::getRequest ( "sign_type" );
$biz_content = HttpRequest::getRequest ( "biz_content" );
$service = HttpRequest::getRequest ( "service" );
$charset = HttpRequest::getRequest ( "charset" );


if (empty($sign)||empty($sign_type)||empty($biz_content)||empty($service)||empty($charset)){
	echo "some parameter is empty.";
	exit();
}

//收到请求，先验证签名

$as = new AlipaySign ();
$sign_verify= $as->rsaCheckV2 ( $_REQUEST, $config ['alipay_public_key_file'] );
if (!$sign_verify){
	echo "sign verfiy fail.";
	exit();
}

//验证网关请求
if (HttpRequest::getRequest("service")=="alipay.service.check"){
// 	Gateway::verifygw();
	$gw=new Gateway();
	$gw->verifygw();
}else if (HttpRequest::getRequest("service")=="alipay.mobile.public.message.notify"){
	//处理收到的消息
	require_once 'Message.php';
	$msg=new Message($biz_content);
}
