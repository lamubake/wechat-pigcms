<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_platform_pay`;");
E_C("CREATE TABLE `imicms_platform_pay` (
  `platform_id` int(11) NOT NULL auto_increment,
  `orderid` varchar(60) NOT NULL,
  `price` float NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `paid` tinyint(1) NOT NULL default '0',
  `from` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`platform_id`),
  KEY `time` (`time`),
  KEY `orderid` USING BTREE (`orderid`,`from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>