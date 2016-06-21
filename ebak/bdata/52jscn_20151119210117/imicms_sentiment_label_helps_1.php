<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sentiment_label_helps`;");
E_C("CREATE TABLE `imicms_sentiment_label_helps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `labels` varchar(512) NOT NULL,
  `addtime` int(11) NOT NULL,
  `label_wecha_id` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `pid` (`pid`),
  KEY `wecha_id` (`wecha_id`),
  KEY `label_wecha_id` (`label_wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>