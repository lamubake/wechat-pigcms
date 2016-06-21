<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_mobilesite`;");
E_C("CREATE TABLE `imicms_mobilesite` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `owndomain` varchar(150) NOT NULL COMMENT '自己站点域名',
  `admindomain` varchar(150) NOT NULL COMMENT '后台域名',
  `tjscript` text NOT NULL COMMENT '第三方js脚本字符创',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `IDX_OWNDOMAIN` (`owndomain`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_mobilesite` values('1','kaiqpo1447853601','m.52jscn.com','demo8.52jscn.com','','1447872506');");

require("../../inc/footer.php");
?>