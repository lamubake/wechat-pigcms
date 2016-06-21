<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_bargain_order`;");
E_C("CREATE TABLE `imicms_bargain_order` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `paytype` varchar(50) default NULL,
  `paid` tinyint(1) NOT NULL default '0',
  `third_id` varchar(100) default NULL,
  `bargain_id` int(11) NOT NULL,
  `bargain_name` varchar(100) default NULL,
  `bargain_logoimg` varchar(100) default NULL,
  `bargain_original` int(20) default NULL,
  `bargain_minimum` int(20) default NULL,
  `bargain_nowprice` int(20) default NULL,
  `price` int(20) default NULL,
  `endtime` int(11) NOT NULL,
  `username` varchar(100) default NULL,
  `phone` varchar(100) default NULL,
  `address` varchar(300) default NULL,
  `state` int(11) NOT NULL default '0',
  `addtime` int(11) default NULL,
  `orderid` varchar(255) NOT NULL,
  `state2` int(1) NOT NULL default '0',
  PRIMARY KEY  (`imicms_id`),
  KEY `token` USING BTREE (`token`),
  KEY `wecha_id` USING BTREE (`wecha_id`),
  KEY `paid` USING BTREE (`paid`),
  KEY `bargain_id` USING BTREE (`bargain_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>