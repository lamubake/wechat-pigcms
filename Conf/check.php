<?php
header("Content-Type: text/html;charset=utf-8");


$dirfile_items = array(
    'conf' => array('type' => 'file', 'path' => './Conf/db.php'),
    'info' => array('type' => 'file', 'path' => './Conf/info.php'),
	
	'Ldf' => array('type' => 'file', 'path' => './Lib/index.html'),
	
	'Cof' => array('type' => 'file', 'path' => './Common/common.php'),
  // 'clf' => array('type' => 'file', 'path' => './Conf/logs/'),
  // 'clCf' => array('type' => 'file', 'path' => './Conf/logs/Cache/'),
  // 'clTf' => array('type' => 'file', 'path' => './Conf/logs/Temp/'),
   
  // 'uf' => array('type' => 'file', 'path' => './uploads/'),
   
  // 'PDf' => array('type' => 'file', 'path' => './PigData/'),
  // 'PDdf' => array('type' => 'file', 'path' => './PigData/database/'),
   
	'PLf' => array('type' => 'file', 'path' => './PigCms/Lib/index.html'),
	
	'PLAf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/index.html'),
	'PLAAf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/Agent/IndexAction.class.php'),
	'PLACf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/Chat/IndexAction.class.php'),
	'PLAFf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/Fuwu/FuwuAction.class.php'),
	'PLAHf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/Home/index.html'),
	'PLAOf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/Other/index.html'),
	'PLASf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/System/index.html'),
	'PLAUf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/User/index.html'),
	'PLAWf' => array('type' => 'file', 'path' => './PigCms/Lib/Action/Wap/index.html'),
	
	'PLMf' => array('type' => 'file', 'path' => './PigCms/Lib/Model/index.html'),
	'PLMOf' => array('type' => 'file', 'path' => './PigCms/Lib/Model/Other/MsgModel.class.php'),
	'PLMUf' => array('type' => 'file', 'path' => './PigCms/Lib/Model/User/index.html'),

	'PLOf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/index.html'),
	'PLOaef' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/aes/errorCode.php'),
	'PLOAYf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/Alipay/index.html'),
	'PLOAPf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/Allinpay/index.html'),
	'PLOFf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/Fuwu/Message.php'),
	'PLOTf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/Tenpay/PayResponse.class.php'),
	'PLOTCf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/TenpayComputer/function.php'),
	'PLOWf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/WapAlipay/log.txt'),
	'PLOWNf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/Weixinnewpay/WxPay.pub.config.php'),
	'PLOWPf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/Weixinpay/WxPayHelper.class.php'),
	'PLOYf' => array('type' => 'file', 'path' => './PigCms/Lib/ORG/Yeepay/Crypt_RSA.php'),
	
	
	'tf' => array('type' => 'file', 'path' => './tpl/index.html'),
	//'tAf' => array('type' => 'file', 'path' => './tpl/Agent/'),
	
	'tABf' => array('type' => 'file', 'path' => './tpl/Agent/Basic/index.html'),
	//'tACf' => array('type' => 'file', 'path' => './tpl/Agent/Common/'),
	'tAFf' => array('type' => 'file', 'path' => './tpl/Agent/Frame/header.html'),
	'tAIf' => array('type' => 'file', 'path' => './tpl/Agent/Index/index.html'),
	'tALf' => array('type' => 'file', 'path' => './tpl/Agent/Login/index.html'),
	'tASf' => array('type' => 'file', 'path' => './tpl/Agent/Site/index.html'),
	'tAUf' => array('type' => 'file', 'path' => './tpl/Agent/Users/index.html'),
	
	//'tCf' => array('type' => 'file', 'path' => './tpl/Chat/'),
	'tCdf' => array('type' => 'file', 'path' => './tpl/Chat/default/Index_index.html'),
	//'tCdsf' => array('type' => 'file', 'path' => './tpl/Chat/default/style/'),
	'tCdsf' => array('type' => 'file', 'path' => './tpl/Home/index.html'),
	'tOf' => array('type' => 'file', 'path' => './tpl/Other/index.html'),
	'tOdf' => array('type' => 'file', 'path' => './tpl/Other/default/Index_index.html'),
	//'tOsf' => array('type' => 'file', 'path' => './tpl/Other/default/style/'),
	
	'tsf' => array('type' => 'file', 'path' => './tpl/static/index.html'),
	'tSYSf' => array('type' => 'file', 'path' => './tpl/System/index.html'),
	
	'tUf' => array('type' => 'file', 'path' => './tpl/User/index.html'),
	'tUdf' => array('type' => 'file', 'path' => './tpl/User/default/index.html'),
	'tUdcf' => array('type' => 'file', 'path' => './tpl/User/default/common/style_2_common.css'),
	
	'tWf' => array('type' => 'file', 'path' => './tpl/Wap/index.html'),
	'tWdf' => array('type' => 'file', 'path' => './tpl/Wap/default/index.html'),
	'tWdcf' => array('type' => 'file', 'path' => './tpl/Wap/default/common/index.html'),
	
	/////////////////////////////////////////
	
	'L' => array('type' => 'dir', 'path' => './Lib/'),
	
	'Co' => array('type' => 'dir', 'path' => './Common/'),
	
	
   'c' => array('type' => 'dir', 'path' => './Conf/'),
   'cl' => array('type' => 'dir', 'path' => './Conf/logs/'),
   'clC' => array('type' => 'dir', 'path' => './Conf/logs/Cache/'),
   'clT' => array('type' => 'dir', 'path' => './Conf/logs/Temp/'),
   
   'u' => array('type' => 'dir', 'path' => './uploads/'),
   
   'PD' => array('type' => 'dir', 'path' => './PigData/'),
   'PDd' => array('type' => 'dir', 'path' => './PigData/database/'),
   
	'PL' => array('type' => 'dir', 'path' => './PigCms/Lib/'),
	
	'PLA' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/'),
	'PLAA' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/Agent/'),
	'PLAC' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/Chat/'),
	'PLAF' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/Fuwu/'),
	'PLAH' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/Home/'),
	'PLAO' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/Other/'),
	'PLAS' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/System/'),
	'PLAU' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/User/'),
	'PLAW' => array('type' => 'dir', 'path' => './PigCms/Lib/Action/Wap/'),
	
	'PLM' => array('type' => 'dir', 'path' => './PigCms/Lib/Model/'),
	'PLMO' => array('type' => 'dir', 'path' => './PigCms/Lib/Model/Other/'),
	'PLMU' => array('type' => 'dir', 'path' => './PigCms/Lib/Model/User/'),

	'PLO' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/'),
	'PLOae' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/aes/'),
	'PLOAY' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/Alipay/'),
	'PLOAP' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/Allinpay/'),
	'PLOF' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/Fuwu/'),
	'PLOT' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/Tenpay/'),
	'PLOTC' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/TenpayComputer/'),
	'PLOW' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/WapAlipay/'),
	'PLOWN' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/Weixinnewpay/'),
	'PLOWP' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/Weixinpay/'),
	'PLOY' => array('type' => 'dir', 'path' => './PigCms/Lib/ORG/Yeepay/'),
	
	
	't' => array('type' => 'dir', 'path' => './tpl/'),
	'tA' => array('type' => 'dir', 'path' => './tpl/Agent/'),
	
	'tAB' => array('type' => 'dir', 'path' => './tpl/Agent/Basic/'),
	'tAC' => array('type' => 'dir', 'path' => './tpl/Agent/Common/'),
	'tAF' => array('type' => 'dir', 'path' => './tpl/Agent/Frame/'),
	'tAI' => array('type' => 'dir', 'path' => './tpl/Agent/Index/'),
	'tAL' => array('type' => 'dir', 'path' => './tpl/Agent/Login/'),
	'tAS' => array('type' => 'dir', 'path' => './tpl/Agent/Site/'),
	'tAU' => array('type' => 'dir', 'path' => './tpl/Agent/Users/'),
	'tCse' => array('type' => 'dir', 'path' => './tpl/case/'),
	'tC' => array('type' => 'dir', 'path' => './tpl/Chat/'),
	'tCd' => array('type' => 'dir', 'path' => './tpl/Chat/default/'),
	'tCds' => array('type' => 'dir', 'path' => './tpl/Chat/default/style/'),
	
	'tO' => array('type' => 'dir', 'path' => './tpl/Other/'),
	'tOd' => array('type' => 'dir', 'path' => './tpl/Other/default/'),
	'tOs' => array('type' => 'dir', 'path' => './tpl/Other/default/style/'),
	
	'ts' => array('type' => 'dir', 'path' => './tpl/static/'),
	'tSYS' => array('type' => 'dir', 'path' => './tpl/System/'),
	
	'tU' => array('type' => 'dir', 'path' => './tpl/User/'),
	'tUd' => array('type' => 'dir', 'path' => './tpl/User/default/'),
	'tUdc' => array('type' => 'dir', 'path' => './tpl/User/default/common/'),
	
	'tW' => array('type' => 'dir', 'path' => './tpl/Wap/'),
	'tWd' => array('type' => 'dir', 'path' => './tpl/Wap/default/'),
	'tWdc' => array('type' => 'dir', 'path' => './tpl/Wap/default/common/'),

	
);


define('ROOT_PATH', dirname(__FILE__).'/../');//网站根目录

dirfile_check($dirfile_items);
//文件权限检查
function dirfile_check(&$dirfile_items) {
	foreach($dirfile_items as $key => $item) {
		$item_path = $item['path'];
		if($item['type'] == 'dir') {
			if(!dir_writeable(ROOT_PATH.$item_path)) {
				if(is_dir(ROOT_PATH.$item_path)) {
					$dirfile_items[$key]['status'] = 0;
					$dirfile_items[$key]['current'] = '+r';
					//echo $item_path.'&nbsp;&nbsp;&nbsp; <font color="#FF0000 size="-1">可读不可写</font><br><br>';
					//echo '<font color="#FF0000 size="-1">网站目录下存在可读不可写文件夹，请修改所有目录权限后再升级</font><br><br>';
	                echo "<script> alert('网站目录下存在只读文件夹，请将所有文件夹权限修改为可读可写后再升级'); </script>"; 
					die;
				} else {
					$dirfile_items[$key]['status'] = -1;
					$dirfile_items[$key]['current'] = 'nodir';
					//echo $item_path.'&nbsp;&nbsp;&nbsp;<font color="#FF0000 size="-1">目录无可读可写权限</font><br><br>';
					//echo '<font color="#FF0000 size="-1">网站目录下存在不可读不可写文件夹，请修改所有目录权限后再升级</font><br><br>';
					echo "<script> alert('网站目录下存在只读文件夹，请将所有文件夹权限修改为可读可写后再升级'); </script>"; 
					die;
				}
			} else {
				//echo '<br>3';
				$dirfile_items[$key]['status'] = 1;
				$dirfile_items[$key]['current'] = '+r+w';
				//echo $item_path.'&nbsp;&nbsp;&nbsp;<font size="-1">权限通过</font><br><br>';
			}
		} else {
			//echo '<br>4';
			if(file_exists(ROOT_PATH.$item_path)) {
				if(is_writable(ROOT_PATH.$item_path)) {
					$dirfile_items[$key]['status'] = 1;
					$dirfile_items[$key]['current'] = '+r+w';
					//$item_path = substr(strrchr($item_path,"/"),-100);
					//$item_path = dirname($item_path, '.php');
					//echo $item_path.'&nbsp;&nbsp;&nbsp;<font size="-1">权限通过</font><br><br>';
				//	echo '<br>5';
				} else {
					$dirfile_items[$key]['status'] = 0;
					$dirfile_items[$key]['current'] = '+r';
				//	if (substr($item_path)
					//echo $item_path.'&nbsp;&nbsp;&nbsp;<font color="#FF0000 size="-1">此目录下的所有文件都无可写权限，请修改此目录下的所有文件</font><br><br>';
					//die;
					//echo '<br>6';
					//echo '<font color="#FF0000 size="-1">网站文件存在可读不可写文件，请修改所有文件为可读可写权限后再升级</font><br><br>';
					echo "<script> alert('网站存在只读文件，请将所有文件修改为可读可写权限后再升级'); </script>"; 
					die;
				}
			} else {
				//echo '<br>7';
				if ($fp = @fopen(ROOT_PATH.$item_path,'wb+')){
					$dirfile_items[$key]['status'] = 1;
					$dirfile_items[$key]['current'] = '+r+w';
				//	echo $item_path.'&nbsp;&nbsp;&nbsp;<font size="-1">权限通过</font><br><br>';
				}else {
					$dirfile_items[$key]['status'] = -2;
					$dirfile_items[$key]['current'] = 'nofile';
				 // echo $item_path.'&nbsp;&nbsp;&nbsp;<font color="#FF0000 size="-1">文件无可读可写权限</font><br><br>';
				//	die;
			    	//echo '<font color="#FF0000 size="-1">网站文件存在不可读不可写文件，请修改所有文件为可读可写权限后再升级</font><br><br>';
					echo "<script> alert('网站存在只读文件，请将所有文件修改为可读可写权限后再升级'); </script>"; 
					die;
				}
			}
		}
	}
	//echo '<font color="#000000" size="-1">文件检测通过</font><br><br>';
	echo "<script> alert('所有文件通过检测，请放心升级'); </script>"; 
}
function dir_writeable($dir) {
	//echo $dir;
	$writeable = 0;
	if(!is_dir($dir)) {
		@mkdir($dir, 0755);
		//echo '<br>a';
	}else {
		@chmod($dir,0755);
	//	echo '<br>b';
	}
	if(is_dir($dir)) {
		//echo '<br>c';
		if($fp = @fopen("$dir/test.txt", 'w')) {
		//	echo '<br>d';
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
		//	echo '<br>e';
			$writeable = 0;
		}
	}
	//echo $writeable.'前面有值';
	return $writeable;
}

?>