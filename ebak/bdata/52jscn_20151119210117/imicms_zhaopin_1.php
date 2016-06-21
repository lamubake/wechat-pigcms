<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_zhaopin`;");
E_C("CREATE TABLE `imicms_zhaopin` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `gangwei` varchar(200) default NULL,
  `xinzi` varchar(200) default NULL,
  `yaoqiu` varchar(5000) default NULL,
  `ren` varchar(255) NOT NULL,
  `zhize` varchar(5000) NOT NULL,
  `jigou` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `people` varchar(255) NOT NULL,
  `tell` char(11) NOT NULL,
  `longitude` char(11) NOT NULL default '',
  `latitude` char(11) NOT NULL default '',
  `leibie` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `click` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `allow` tinyint(1) NOT NULL COMMENT '审核',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>