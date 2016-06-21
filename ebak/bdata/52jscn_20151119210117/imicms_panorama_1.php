<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_panorama`;");
E_C("CREATE TABLE `imicms_panorama` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `intro` varchar(300) NOT NULL default '',
  `music` varchar(100) NOT NULL default '',
  `frontpic` varchar(100) NOT NULL default '',
  `rightpic` varchar(100) NOT NULL default '',
  `backpic` varchar(100) NOT NULL default '',
  `leftpic` varchar(100) NOT NULL default '',
  `toppic` varchar(100) NOT NULL default '',
  `bottompic` varchar(100) NOT NULL default '',
  `keyword` varchar(60) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  `taxis` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>