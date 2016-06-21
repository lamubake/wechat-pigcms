<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_donation_order`;");
E_C("CREATE TABLE `imicms_donation_order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `did` int(10) unsigned NOT NULL,
  `orderid` varchar(20) NOT NULL,
  `token` varchar(80) NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `paid` tinyint(1) unsigned NOT NULL default '0',
  `price` decimal(10,2) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `isanonymous` tinyint(1) NOT NULL default '0' COMMENT '是否匿名（0:否，1：是）',
  PRIMARY KEY  (`id`),
  KEY `orderid` (`orderid`),
  KEY `token` (`token`,`wecha_id`,`sid`),
  KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>