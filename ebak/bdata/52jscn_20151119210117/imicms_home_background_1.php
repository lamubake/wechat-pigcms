<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_home_background`;");
E_C("CREATE TABLE `imicms_home_background` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL default '',
  `picurl` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `taxis` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>