<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_donation_value`;");
E_C("CREATE TABLE `imicms_donation_value` (
  `id` int(11) NOT NULL auto_increment,
  `did` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `wecha_id` varchar(80) NOT NULL,
  `num` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `wecha_id` (`wecha_id`),
  KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>