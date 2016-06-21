<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_game_records`;");
E_C("CREATE TABLE `imicms_game_records` (
  `id` int(11) NOT NULL auto_increment,
  `gameid` int(11) NOT NULL default '0',
  `token` varchar(30) NOT NULL default '',
  `wecha_id` varchar(50) NOT NULL default '',
  `score` float(7,2) NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `gameid` (`gameid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>