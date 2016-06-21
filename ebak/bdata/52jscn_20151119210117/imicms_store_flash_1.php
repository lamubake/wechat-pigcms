<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_store_flash`;");
E_C("CREATE TABLE `imicms_store_flash` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `img` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `info` varchar(90) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>