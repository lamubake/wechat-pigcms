<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_donation_dynamic`;");
E_C("CREATE TABLE `imicms_donation_dynamic` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(80) NOT NULL,
  `did` int(10) unsigned NOT NULL COMMENT '活动ID',
  `image_id` int(10) unsigned NOT NULL COMMENT '图文ID',
  `sort` int(10) unsigned NOT NULL default '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `did` (`did`),
  KEY `image_id` (`image_id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>