<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_userinfo`;");
E_C("CREATE TABLE `imicms_userinfo` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `wecha_id` varchar(60) NOT NULL,
  `wechaname` varchar(60) NOT NULL,
  `truename` varchar(60) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `qq` int(11) NOT NULL,
  `sex` tinyint(1) default NULL,
  `age` int(3) NOT NULL,
  `birthday` varchar(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `info` varchar(200) NOT NULL,
  `continuous` int(11) NOT NULL,
  `sign_score` varchar(100) NOT NULL,
  `expend_score` varchar(100) NOT NULL,
  `total_score` varchar(100) NOT NULL,
  `add_expend_time` int(11) default NULL,
  `add_expend` varchar(100) NOT NULL,
  `getcardtime` int(11) NOT NULL,
  `live_time` int(11) NOT NULL,
  `change_score` varchar(100) NOT NULL,
  `change_score_time` int(11) NOT NULL,
  `expensetotal` int(11) default NULL,
  `portrait` varchar(200) default NULL,
  `wallopen` tinyint(1) NOT NULL default '0',
  `bornyear` varchar(4) default NULL,
  `bornmonth` varchar(4) default NULL,
  `bornday` varchar(4) default NULL,
  `balance` double(10,2) unsigned NOT NULL default '0.00',
  `paypass` varchar(32) default NULL,
  `twid` varchar(20) NOT NULL COMMENT '推广号',
  `username` varchar(32) NOT NULL COMMENT '账号',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `city` varchar(40) default NULL,
  `province` varchar(40) default NULL,
  `total_score_bf` int(10) NOT NULL,
  `balance_bf` decimal(10,2) NOT NULL,
  `store_id` int(10) default '0',
  `drp_cart` text NOT NULL COMMENT '分销系统-用户购物车',
  `regtime` varchar(20) NOT NULL default '' COMMENT '注册时间',
  `fakeopenid` varchar(100) NOT NULL default '',
  `issub` tinyint(1) NOT NULL default '0',
  `isverify` tinyint(2) NOT NULL default '0',
  `origin` varchar(200) default NULL,
  `hongbaoqiye_qr` char(250) NOT NULL,
  `cart` varchar(20) default '',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `twid` (`twid`),
  KEY `username` (`username`),
  KEY `store_id` USING BTREE (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_userinfo` values('1','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','','','','0','0','0','','','','0','','','',NULL,'','1415108559','0','','0',NULL,'','0','','','','0.00',NULL,'cjM1','','',NULL,NULL,'0','0.00','0','','','','0','0',NULL,'','');");

require("../../inc/footer.php");
?>