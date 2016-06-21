<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_twitter_remove`;");
E_C("CREATE TABLE `imicms_twitter_remove` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(60) NOT NULL,
  `twid` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '提款人姓名',
  `tel` varchar(15) NOT NULL,
  `number` varchar(32) NOT NULL COMMENT '收款账号',
  `bank` varchar(30) NOT NULL COMMENT '银行名称',
  `address` varchar(60) NOT NULL COMMENT '开户行地址',
  `price` float NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>