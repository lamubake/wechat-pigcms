<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dish_reply`;");
E_C("CREATE TABLE `imicms_dish_reply` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL COMMENT 'company表id',
  `token` varchar(250) NOT NULL,
  `tableid` int(10) unsigned NOT NULL COMMENT 'dining_table表id',
  `keyword` varchar(100) NOT NULL COMMENT '关键词',
  `cf` varchar(20) NOT NULL COMMENT '来源',
  `addtime` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL default '0' COMMENT '1餐厅2餐桌后台0餐桌',
  `reg_id` int(10) unsigned NOT NULL default '0' COMMENT 'recognition表id',
  PRIMARY KEY  (`id`),
  KEY `cid` USING BTREE (`cid`),
  KEY `reg_id` USING BTREE (`reg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>