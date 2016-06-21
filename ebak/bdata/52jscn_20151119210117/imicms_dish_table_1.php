<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dish_table`;");
E_C("CREATE TABLE `imicms_dish_table` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `tableid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `reservetime` int(10) unsigned NOT NULL,
  `creattime` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `isuse` tinyint(1) unsigned NOT NULL,
  `dn_id` int(10) unsigned NOT NULL default '0' COMMENT 'dish_name表id',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `wecha_id` (`wecha_id`,`reservetime`),
  KEY `tableid` (`tableid`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_dish_table` values('1','1','1','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','1412868974','1412868437','1','0','0');");
E_D("replace into `imicms_dish_table` values('2','1','1','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','1413041886','1413041310','2','0','0');");

require("../../inc/footer.php");
?>