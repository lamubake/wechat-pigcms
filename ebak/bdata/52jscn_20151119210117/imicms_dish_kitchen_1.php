<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dish_kitchen`;");
E_C("CREATE TABLE `imicms_dish_kitchen` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否将厨房的每道菜单独打印出来（0,：否，1：是）',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='厨房'");

require("../../inc/footer.php");
?>