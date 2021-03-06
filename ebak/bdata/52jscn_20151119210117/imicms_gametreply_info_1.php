<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_gametreply_info`;");
E_C("CREATE TABLE `imicms_gametreply_info` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL default '',
  `picurl` varchar(255) NOT NULL default '',
  `picurls1` varchar(255) NOT NULL default '',
  `info` varchar(255) NOT NULL default '',
  `keyword` varchar(50) NOT NULL default '',
  `copyright` text NOT NULL,
  `ad` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>