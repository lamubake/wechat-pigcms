<?php
header("Content-type: text/html; charset=utf-8");

if (get_magic_quotes_gpc()) {
 function stripslashes_deep($value){
  $value = is_array($value) ?
  array_map('stripslashes_deep', $value) :
  stripslashes($value);
  return $value;
 }

 $_POST = array_map('stripslashes_deep', $_POST);
 $_GET = array_map('stripslashes_deep', $_GET);
 $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

$_GET['g'] = 'Wap';
$_GET['m'] = 'Weixin';
$_GET['a'] = 'new_return_url';

$array_data = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);

$_GET['out_trade_no'] = $array_data['out_trade_no'];
$_GET['total_fee'] = $array_data['total_fee'];
$_GET['trade_state'] = $array_data['result_code'];
$_GET['transaction_id'] = $array_data['transaction_id'];
$get_arr = explode('&',$array_data['attach']);
foreach($get_arr as $value){
	$tmp_arr = explode('=',$value);
	$_GET[$tmp_arr[0]] = $tmp_arr[1];
}

define('APP_DEBUG',1);
define('APP_NAME', 'cms');
define('CONF_PATH','./../DataPig/conf/');
define('RUNTIME_PATH','./../DataPig/logs/');
define('TMPL_PATH','./../tpl/');
define('HTML_PATH','./../DataPig/html/');
define('APP_PATH','./../PigCms/');
define('CORE','./../PigCms/_Core');
require(CORE.'/PigCms.php');
?>