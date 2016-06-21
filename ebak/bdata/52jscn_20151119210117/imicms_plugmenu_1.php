<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_plugmenu`;");
E_C("CREATE TABLE `imicms_plugmenu` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `uname` varchar(90) NOT NULL,
  `keyword` char(255) NOT NULL,
  `type` varchar(1) NOT NULL COMMENT '关键词匹配类型',
  `text` text NOT NULL COMMENT '简介',
  `business_type` varchar(20) NOT NULL,
  `activity_type` tinyint(4) NOT NULL,
  `icon` char(255) NOT NULL default 'icon-bullhorn' COMMENT '菜单图标',
  `display` tinyint(1) NOT NULL default '0' COMMENT '导航菜单是否显示',
  `oid` tinyint(4) NOT NULL COMMENT '导航菜单排序',
  `url` char(255) NOT NULL COMMENT '链接地址',
  `createtime` varchar(13) NOT NULL,
  `uptatetime` varchar(13) NOT NULL,
  `click` int(11) NOT NULL,
  `token` char(30) NOT NULL,
  `title` varchar(60) NOT NULL,
  `ltype` varchar(10) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `business_func` varchar(255) NOT NULL,
  `act` int(11) NOT NULL,
  `place` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `activity_value` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `classid` (`business_type`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>