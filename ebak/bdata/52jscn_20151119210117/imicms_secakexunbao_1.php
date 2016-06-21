<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_secakexunbao`;");
E_C("CREATE TABLE `imicms_secakexunbao` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` varchar(50) NOT NULL default '0',
  `state` int(5) NOT NULL default '0',
  `x_id` int(10) default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` USING BTREE (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>