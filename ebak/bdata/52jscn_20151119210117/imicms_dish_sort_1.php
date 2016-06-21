<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dish_sort`;");
E_C("CREATE TABLE `imicms_dish_sort` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `des` varchar(500) NOT NULL,
  `image` varchar(200) NOT NULL,
  `num` smallint(3) unsigned NOT NULL,
  `catid` int(8) NOT NULL COMMENT '店铺id',
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_dish_sort` values('1','1','麻辣海鲜','麻辣海鲜麻辣海鲜麻辣海鲜麻辣海鲜麻辣海鲜麻辣海鲜麻辣海鲜麻辣海鲜麻辣海鲜','','1','1','0');");

require("../../inc/footer.php");
?>