<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_lottery_cheat`;");
E_C("CREATE TABLE `imicms_lottery_cheat` (
  `id` int(10) NOT NULL auto_increment,
  `lid` int(10) NOT NULL default '0',
  `wecha_id` varchar(60) NOT NULL default '',
  `mp` varchar(11) NOT NULL default '',
  `prizetype` mediumint(4) NOT NULL default '0',
  `intro` varchar(60) NOT NULL default '',
  `code` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `lid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>