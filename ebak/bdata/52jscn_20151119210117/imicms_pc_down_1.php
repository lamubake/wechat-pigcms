<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_down`;");
E_C("CREATE TABLE `imicms_pc_down` (
  `id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `key` varchar(50) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `hits` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cat_id` (`cat_id`),
  KEY `time` (`time`),
  KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>