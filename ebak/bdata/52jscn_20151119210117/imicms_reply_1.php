<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_reply`;");
E_C("CREATE TABLE `imicms_reply` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `wecha_id` varchar(60) NOT NULL,
  `differ` tinyint(4) NOT NULL default '0',
  `checked` tinyint(4) NOT NULL default '0',
  `message_id` int(11) NOT NULL,
  `reply` varchar(1000) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `message_id` (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>