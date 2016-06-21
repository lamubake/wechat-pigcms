<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_service_wxuser`;");
E_C("CREATE TABLE `imicms_service_wxuser` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `app_id` varchar(255) default NULL,
  `app_key` varchar(255) default NULL,
  `state` tinyint(1) NOT NULL default '0',
  `wxappid` varchar(200) default NULL,
  `wxappsecret` varchar(500) default NULL,
  `domain` varchar(200) default NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>