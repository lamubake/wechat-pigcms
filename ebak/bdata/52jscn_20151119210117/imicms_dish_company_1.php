<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dish_company`;");
E_C("CREATE TABLE `imicms_dish_company` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `istakeaway` tinyint(1) unsigned NOT NULL,
  `price` float NOT NULL,
  `payonline` tinyint(1) unsigned NOT NULL,
  `token` varchar(50) NOT NULL default '',
  `catid` mediumint(3) NOT NULL default '0',
  `status` int(1) NOT NULL COMMENT '店铺状态',
  `category` varchar(10) NOT NULL COMMENT '店铺分类',
  `time` varchar(12) NOT NULL COMMENT '营业时间',
  `money` double(10,2) NOT NULL COMMENT '起送价格',
  `radius` varchar(10) NOT NULL COMMENT '服务半径',
  `scope` varchar(100) NOT NULL COMMENT '配送范围',
  `bulletin` text NOT NULL COMMENT '店铺公告',
  `memberCode` varchar(50) NOT NULL,
  `feiyin_key` varchar(50) NOT NULL,
  `deviceNo` varchar(20) NOT NULL,
  `print_status` int(1) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_status` int(1) NOT NULL,
  `email_status` int(1) NOT NULL,
  `subscription` float NOT NULL,
  `discount` decimal(10,1) unsigned NOT NULL default '0.0' COMMENT '折扣',
  `kconoff` tinyint(1) unsigned NOT NULL default '0' COMMENT '开启库存',
  `autoref` tinyint(1) unsigned NOT NULL default '0',
  `starttime` int(10) unsigned NOT NULL default '0' COMMENT '营业开始时间',
  `endtime` int(10) unsigned NOT NULL default '0' COMMENT '营业结束时间',
  `imgs` varchar(250) NOT NULL COMMENT '餐厅图片',
  `bookingtime` varchar(255) NOT NULL COMMENT '餐桌选择时间段',
  `nowpay` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否立刻支付',
  `advancepay` int(10) unsigned NOT NULL COMMENT '预付定金',
  `print_total` int(4) NOT NULL COMMENT '打印份数',
  `phone_authorize` int(1) NOT NULL COMMENT '手机订单短信验证开关',
  `printer` varchar(30) NOT NULL,
  `dishsame` tinyint(1) default NULL,
  `offtable` tinyint(1) default NULL,
  `starttime2` int(10) unsigned NOT NULL default '0' COMMENT '营业开始时间',
  `endtime2` int(10) unsigned NOT NULL default '0' COMMENT '营业结束时间',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_dish_company` values('1','1','1','10','1','','1','0','快餐、海鲜、炒货','','0.00','','','','','','','1','','','0','0','0','0.0','0','0','0','0','','','1','0','0','0','',NULL,NULL,'0','0');");

require("../../inc/footer.php");
?>