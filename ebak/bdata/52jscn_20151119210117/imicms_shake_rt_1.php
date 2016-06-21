<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shake_rt`;");
E_C("CREATE TABLE `imicms_shake_rt` (
  `id` int(11) NOT NULL auto_increment,
  `wecha_id` varchar(60) NOT NULL default '',
  `token` varchar(30) NOT NULL default '',
  `phone` varchar(12) NOT NULL default '',
  `count` int(10) NOT NULL default '0',
  `shakeid` int(10) NOT NULL default '0',
  `round` mediumint(9) NOT NULL,
  `is_scene` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `shakeid` (`shakeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>