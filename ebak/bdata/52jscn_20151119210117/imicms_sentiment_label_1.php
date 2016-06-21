<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sentiment_label`;");
E_C("CREATE TABLE `imicms_sentiment_label` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `label` varchar(50) NOT NULL,
  `count` int(11) NOT NULL default '0',
  `state` int(11) NOT NULL default '1',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `pid` (`pid`),
  KEY `wecha_id` (`wecha_id`),
  KEY `state` (`state`),
  KEY `label` (`label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>