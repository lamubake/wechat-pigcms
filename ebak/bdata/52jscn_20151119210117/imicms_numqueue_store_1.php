<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_numqueue_store`;");
E_C("CREATE TABLE `imicms_numqueue_store` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `store_type` tinyint(1) NOT NULL,
  `opentime` tinyint(4) NOT NULL default '0',
  `closetime` tinyint(4) NOT NULL default '0',
  `logo` varchar(255) NOT NULL COMMENT 'logo',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `remark` char(50) NOT NULL,
  `price` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_value` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL default '',
  `privilege_link` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `action_id` int(11) NOT NULL,
  `token` char(50) NOT NULL,
  `jump_name` varchar(255) NOT NULL,
  `hankowthames` varchar(255) NOT NULL default '',
  `password` varchar(100) NOT NULL,
  `rank` int(11) NOT NULL,
  `wait_time` int(11) NOT NULL default '0',
  `add_time` int(11) NOT NULL default '0',
  `allow_distance` float(6,2) NOT NULL,
  `need_numbers` tinyint(1) NOT NULL default '0',
  `need_wait` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>