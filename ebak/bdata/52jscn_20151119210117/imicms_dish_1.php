<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dish`;");
E_C("CREATE TABLE `imicms_dish` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit` varchar(3) NOT NULL,
  `price` float NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL,
  `isopen` tinyint(1) unsigned NOT NULL,
  `image` varchar(200) NOT NULL COMMENT 'Ƭ',
  `des` varchar(500) NOT NULL,
  `creattime` int(10) unsigned NOT NULL,
  `catid` int(8) NOT NULL COMMENT '店铺id',
  `sort` int(10) unsigned NOT NULL COMMENT '排序，数字越小排的越前',
  `istakeout` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否支持外卖',
  `isdiscount` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否支持会员折扣',
  `instock` int(10) unsigned NOT NULL default '0' COMMENT '库存',
  `refreshstock` int(10) unsigned NOT NULL default '0' COMMENT '刷新库存',
  `kitchen_id` int(10) unsigned NOT NULL COMMENT '厨房ID',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `sid` (`sid`),
  KEY `isopen` (`isopen`),
  KEY `sort` (`sort`),
  KEY `kitchen_id` (`kitchen_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_dish` values('1','1','1','麻辣螃蟹','盒','60','1','1','','','0','1','0','1','0','0','0','0');");

require("../../inc/footer.php");
?>