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


$user_params = $_POST['user_params'];
$user_params = str_replace('||', '=', $user_params);
$user_params = str_replace('|', '&', $user_params);
parse_str ( $user_params );

$_GET['g'] = 'Wap';
$_GET['m'] = 'Alipaytype';
$_GET['a'] = 'notify_url';
$_GET['token'] = $token;
$_GET['wecha_id'] = $wecha_id;
$_GET['from'] = $from;
$_GET['rget'] = '1';
if (isset($pl)) {
	$_GET['pl'] = 1;
}

define('APP_NAME', 'cms');
define('CONF_PATH','./../Conf/');
define('TMPL_PATH','./../tpl/');
$GLOBALS['_beginTime'] = microtime(TRUE);
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
define('CORE','./../');
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
define('APP_PATH','./../PigCms/');
defined('APP_PATH') 	or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
define('RUNTIME_PATH','./../Conf/logs/');
defined('RUNTIME_PATH') or define('RUNTIME_PATH',APP_PATH.'Runtime/');
define('APP_DEBUG',1);
defined('APP_DEBUG') 	or define('APP_DEBUG',false);
$runtime = defined('MODE_NAME')?'~'.strtolower(MODE_NAME).'_runtime.php':'~runtime.php';
defined('RUNTIME_FILE') or define('RUNTIME_FILE',RUNTIME_PATH.$runtime);
if(!APP_DEBUG && is_file(RUNTIME_FILE)) {
    require RUNTIME_FILE;
}else{
    defined('THINK_PATH') or define('THINK_PATH', dirname(__FILE__).'/../');
    require THINK_PATH.'Common/runtime.php';
}
exit;
?>