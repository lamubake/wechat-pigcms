<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sjmreply_info`;");
E_C("CREATE TABLE `imicms_sjmreply_info` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL default '',
  `picurl` varchar(120) NOT NULL default '',
  `sharepicurl` varchar(120) NOT NULL default '',
  `info` varchar(120) NOT NULL default '',
  `keyword` varchar(50) NOT NULL default '',
  `copyright` text NOT NULL,
  `ad` int(11) NOT NULL,
  `wxname` varchar(255) NOT NULL,
  `tip` varchar(255) NOT NULL,
  `url` char(255) NOT NULL,
  `shareurl` char(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>