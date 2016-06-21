<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_games`;");
E_C("CREATE TABLE `imicms_games` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(40) NOT NULL default '',
  `gameid` int(11) NOT NULL default '0',
  `picurl` varchar(160) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `keyword` varchar(60) NOT NULL default '',
  `intro` varchar(500) NOT NULL default '',
  `selfinfo` varchar(5000) NOT NULL default '',
  `token` varchar(20) NOT NULL default '',
  `playcount` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>