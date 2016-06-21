<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_yuyue`;");
E_C("CREATE TABLE `imicms_yuyue` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(200) default NULL,
  `address` varchar(100) default NULL,
  `longitude` varchar(100) default NULL,
  `latitude` varchar(100) default NULL,
  `phone` varchar(20) default NULL,
  `topic` varchar(200) default NULL,
  `info` varchar(500) default NULL,
  `description` varchar(500) NOT NULL,
  `statdate` date default NULL,
  `enddate` date default NULL,
  `type` varchar(50) default NULL,
  `copyright` varchar(30) default NULL,
  `yuyue_type` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>