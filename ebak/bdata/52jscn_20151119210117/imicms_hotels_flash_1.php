<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hotels_flash`;");
E_C("CREATE TABLE `imicms_hotels_flash` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>