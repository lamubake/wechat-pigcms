<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_collectword_record`;");
E_C("CREATE TABLE `imicms_collectword_record` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `word` tinyint(3) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `pid` (`pid`),
  KEY `wecha_id` (`wecha_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>