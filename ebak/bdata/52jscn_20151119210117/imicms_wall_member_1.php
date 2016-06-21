<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wall_member`;");
E_C("CREATE TABLE `imicms_wall_member` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(20) NOT NULL default '',
  `wecha_id` varchar(60) NOT NULL default '',
  `portrait` varchar(150) NOT NULL default '',
  `nickname` varchar(60) NOT NULL default '',
  `truename` varchar(40) NOT NULL,
  `phone` char(11) NOT NULL,
  `mp` varchar(11) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  `wallid` int(10) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `sex` tinyint(1) NOT NULL default '0',
  `act_id` int(11) NOT NULL,
  `act_type` enum('1','2','3') NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`,`wallid`),
  KEY `wecha_id` (`wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>