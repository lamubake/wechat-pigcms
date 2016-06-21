<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_comment`;");
E_C("CREATE TABLE `imicms_product_comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(50) NOT NULL,
  `cartid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `detailid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  `truename` varchar(20) NOT NULL,
  `tel` varchar(14) NOT NULL,
  `content` varchar(500) NOT NULL,
  `productinfo` varchar(80) NOT NULL,
  `score` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `isdelete` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `wecha_id` (`wecha_id`),
  KEY `token` (`token`),
  KEY `cartid` (`cartid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>