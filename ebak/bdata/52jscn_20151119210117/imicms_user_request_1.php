<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_user_request`;");
E_C("CREATE TABLE `imicms_user_request` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(30) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `msgtype` varchar(15) NOT NULL default 'text',
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `msgtype` USING BTREE (`msgtype`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>