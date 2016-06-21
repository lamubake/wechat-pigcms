<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_yuyue_order`;");
E_C("CREATE TABLE `imicms_yuyue_order` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `name` varchar(20) default NULL,
  `phone` varchar(11) default NULL,
  `date` datetime default NULL,
  `memo` varchar(200) default NULL,
  `type` smallint(4) NOT NULL default '0',
  `wecha_id` varchar(200) NOT NULL,
  `or_date` date default NULL,
  `time` varchar(50) default NULL,
  `nums` varchar(20) default NULL,
  `fieldsigle` varchar(100) default NULL,
  `fielddownload` varchar(100) default NULL,
  `price` varchar(10) default NULL,
  `kind` varchar(20) default NULL,
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>