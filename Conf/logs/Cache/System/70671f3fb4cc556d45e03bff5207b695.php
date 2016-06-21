<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<link href="<?php echo RES;?>/images/main.css" type="text/css" rel="stylesheet">
<meta http-equiv="x-ua-compatible" content="ie=7" />
</head>
<body style="background:none">
<div class="content">
<div class="box">
	<h3><?php echo C('site_name');?>说明</h3>
    <div class="con dcon">
    <div class="update">
    <p>服务器系统：[<?php echo PHP_OS; ?>]<?php echo $_SERVER[SERVER_SOFTWARE];?> </p>
	<p>服务器组件：<span class="red">MySql:</span> <?php echo mysql_get_server_info(); ?>&nbsp;&nbsp;<span class="red">php: <?php echo PHP_VERSION; ?>&nbsp;&nbsp;&nbsp;&nbsp;Zend版本：<?php $zend_version = zend_version();if(empty($zend_version)){echo '<font color=red>×</font>';}else{echo $zend_version;}?></p>
    <p>登陆IP：<?php echo $_SERVER['REMOTE_ADDR']; ?></p>
    <p>被屏蔽的函数：<?php echo get_cfg_var("disable_functions")?get_cfg_var("disable_functions"):"无" ; ?></p>
    <p><span class="black">
    <p>系统版本：Elcms V2.0_<?php echo ($ver); ?> <a href="./index.php?g=System&m=Update&a=index" class="isub">检查更新</a></p>
    </div>
     <ul class="myinfo">
        <li><p class="red">程序版本：亚蓝微营销多用户微信营销系统v2.0</p><span></li>
   <li><p><?php echo ($domain_time); ?></p></li>
	</ul>
    </div>
</div>
<!--/box-->
<div class="box">
	<h3><?php echo C('site_name');?>更新消息</h3>
    <div class="con dcon">

	
    <ul class="myinfo kjinfo">
    
   
    <B>平台模板发布：<a href="http://t.eake.cn/index.php?mod=channel&id=27" target="_blank">帮助中心>>></a></B>
     <a href="http://t.eake.cn/index.php?mod=channel&id=28" target="_blank">使用反馈>>></a>
	 <a href="http://t.eake.cn/index.php?mod=channel&id=28" target="_blank">其他>>></a></B>
	 <a href="http://t.eake.cn/index.php?mod=channel&id=230" target="_blank">更新消息>>></a>
	
	</ul>
    </div>
</div>

<!--/box-->
</div>
<script>
function systemupdatecheck(){
	$.ajax({
		type: "GET",
		url: "Services/EFService.svc/Members",
		data: "{}",
		contentType: "application/json; charset=utf-8",
		dataType: "json",
		success: function (data) {
			if (data.success == true) {
				setTimeout("window.location.href = location.href",2000);
			} else {
				alert(data.msg);
			}
		},
		error: function (msg) {
			alert(msg);
		}
	});
}
</script>
</body>
</html>