<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_moopha_picture`;");
E_C("CREATE TABLE `imicms_moopha_picture` (
  `id` int(10) NOT NULL auto_increment,
  `contentid` int(10) NOT NULL,
  `url` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `taxis` mediumint(4) NOT NULL default '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `contentid` (`contentid`),
  KEY `taxis` (`taxis`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>