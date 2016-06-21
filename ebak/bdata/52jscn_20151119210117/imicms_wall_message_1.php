<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wall_message`;");
E_C("CREATE TABLE `imicms_wall_message` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `wallid` int(10) NOT NULL default '0',
  `token` varchar(20) NOT NULL default '',
  `wecha_id` varchar(60) NOT NULL default '',
  `content` varchar(500) NOT NULL default '',
  `picture` varchar(150) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  `is_scene` enum('0','1') NOT NULL,
  `is_check` tinyint(1) NOT NULL default '1',
  `check_time` int(11) NOT NULL,
  `is_cheke` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `wallid` (`wallid`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>