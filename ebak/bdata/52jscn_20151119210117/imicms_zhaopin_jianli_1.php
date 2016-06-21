<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_zhaopin_jianli`;");
E_C("CREATE TABLE `imicms_zhaopin_jianli` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `job` varchar(200) default NULL,
  `salary` varchar(200) default NULL,
  `introduce` varchar(5000) default NULL,
  `education` varchar(255) NOT NULL,
  `workarea` varchar(255) NOT NULL,
  `phone` char(11) NOT NULL,
  `leibie` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `click` int(11) NOT NULL,
  `year` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `validTime` int(100) NOT NULL,
  `age` char(3) NOT NULL default '',
  `wecha_id` varchar(200) NOT NULL,
  `allow` tinyint(1) NOT NULL COMMENT '审核',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>