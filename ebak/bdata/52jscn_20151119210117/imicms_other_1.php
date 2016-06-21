<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_other`;");
E_C("CREATE TABLE `imicms_other` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `uname` varchar(90) NOT NULL,
  `lbs_distance` char(255) NOT NULL,
  `is_news` tinyint(1) NOT NULL,
  `keyword` text NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `click` int(11) NOT NULL,
  `token` char(30) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_other` values('1','6','1457963911','','0','知识','1413468389','1413468389','0','257e21c8a9e1be8c','');");
E_D("replace into `imicms_other` values('2','14','cyangkun','','0','首页','1416388485','1416388485','0','c4448ac95e30a1eb','');");

require("../../inc/footer.php");
?>