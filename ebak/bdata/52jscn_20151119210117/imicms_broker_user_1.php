<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_broker_user`;");
E_C("CREATE TABLE `imicms_broker_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(255) NOT NULL,
  `bid` int(11) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `identity` tinyint(1) unsigned NOT NULL COMMENT 'broker_translation表id',
  `status` tinyint(1) unsigned NOT NULL default '0' COMMENT '0正常1禁用',
  `is_verify` tinyint(3) unsigned NOT NULL default '0' COMMENT '是否置业顾问',
  `identitylog` varchar(255) NOT NULL COMMENT '身份变更记录',
  `identitycode` varchar(20) NOT NULL COMMENT '身份证号',
  `company` varchar(255) NOT NULL COMMENT '公司名称',
  `recommendnum` int(10) unsigned NOT NULL COMMENT '推荐人数',
  `totalcash` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT '可提取佣金',
  `extractcash` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT '以提取出的佣金',
  `bank_truename` varchar(30) character set utf8 collate utf8_estonian_ci NOT NULL COMMENT '银行开户姓名',
  `bank_cardnum` varchar(20) NOT NULL COMMENT '银行卡号',
  `bank_name` varchar(60) NOT NULL COMMENT '银行名称',
  `wecha_id` varchar(100) NOT NULL COMMENT 'openid',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>