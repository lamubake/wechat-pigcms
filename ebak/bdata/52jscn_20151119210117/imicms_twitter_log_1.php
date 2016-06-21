<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_twitter_log`;");
E_C("CREATE TABLE `imicms_twitter_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(60) NOT NULL,
  `twid` varchar(20) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `price` float NOT NULL,
  `fromsource` varchar(60) NOT NULL COMMENT '来自源',
  `param` float NOT NULL,
  `wecha_id` varchar(64) NOT NULL COMMENT '操作人，即操作了推广人推广的产品',
  `info` varchar(500) NOT NULL COMMENT '推广的详情',
  PRIMARY KEY  (`id`),
  KEY `twid` (`twid`),
  KEY `token` (`token`),
  KEY `fromsource` (`fromsource`),
  KEY `cid` (`cid`),
  KEY `wecha_id` USING BTREE (`wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='获取佣金记录'");

require("../../inc/footer.php");
?>