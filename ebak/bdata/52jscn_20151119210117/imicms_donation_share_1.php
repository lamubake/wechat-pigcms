<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_donation_share`;");
E_C("CREATE TABLE `imicms_donation_share` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `did` int(10) unsigned NOT NULL,
  `wecha_id` varchar(70) NOT NULL,
  `token` varchar(70) NOT NULL,
  `content` varchar(100) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>