<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_bargain_kanuser`;");
E_C("CREATE TABLE `imicms_bargain_kanuser` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `bargain_id` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `friend` varchar(100) NOT NULL,
  `dao` int(20) NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`imicms_id`),
  KEY `token` USING BTREE (`token`),
  KEY `wecha_id` USING BTREE (`wecha_id`),
  KEY `bargain_id` USING BTREE (`bargain_id`),
  KEY `orderid` USING BTREE (`orderid`),
  KEY `friend` USING BTREE (`friend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>