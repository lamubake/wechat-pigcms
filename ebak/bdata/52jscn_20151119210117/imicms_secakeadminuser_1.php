<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_secakeadminuser`;");
E_C("CREATE TABLE `imicms_secakeadminuser` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` varchar(50) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` USING BTREE (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>