<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_moopha_site`;");
E_C("CREATE TABLE `imicms_moopha_site` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `intro` varchar(600) NOT NULL default '',
  `picurl` varchar(120) NOT NULL default '',
  `token` varchar(50) NOT NULL default '',
  `template` varchar(40) NOT NULL default '',
  `logourl` varchar(120) NOT NULL default '',
  `siteindex` varchar(50) NOT NULL,
  `taxis` int(4) NOT NULL,
  `main` int(1) NOT NULL,
  `abspath` tinyint(1) NOT NULL default '0',
  `url` varchar(100) default NULL,
  `statisticcode` varchar(600) NOT NULL default '',
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `siteindex` (`siteindex`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>