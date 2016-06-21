<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_friend`;");
E_C("CREATE TABLE `imicms_friend` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(80) NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `name` varchar(10) NOT NULL COMMENT '姓名',
  `sex` tinyint(1) unsigned NOT NULL COMMENT '性别(2:女，1：男)',
  `address` varchar(200) NOT NULL COMMENT '居住地',
  `school` varchar(100) NOT NULL COMMENT '学校',
  `into` smallint(2) unsigned NOT NULL COMMENT '入学年份',
  `profession` varchar(100) NOT NULL COMMENT '专业',
  `company` varchar(100) NOT NULL COMMENT '所在单位',
  `post` varchar(100) NOT NULL COMMENT '任职职务',
  `info` varchar(300) NOT NULL COMMENT '个人简介',
  `dateline` int(10) unsigned NOT NULL,
  `head` varchar(200) NOT NULL COMMENT '头像',
  `tel` varchar(11) NOT NULL COMMENT '电话',
  PRIMARY KEY  (`id`),
  KEY `wecha_id` (`wecha_id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>