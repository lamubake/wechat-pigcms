<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_selfform`;");
E_C("CREATE TABLE `imicms_selfform` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(30) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `keyword` varchar(100) NOT NULL default '',
  `intro` varchar(400) NOT NULL default '',
  `content` text NOT NULL,
  `time` int(11) NOT NULL default '0',
  `successtip` varchar(60) NOT NULL default '',
  `failtip` varchar(60) NOT NULL default '',
  `endtime` int(11) NOT NULL default '0',
  `logourl` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `endtime` (`endtime`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>