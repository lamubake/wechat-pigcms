<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_directhongbao`;");
E_C("CREATE TABLE `imicms_directhongbao` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `min_money` float(6,2) NOT NULL,
  `max_money` float(6,2) NOT NULL,
  `total_money` float(6,2) default NULL,
  `send_name` varchar(50) NOT NULL,
  `wishing` varchar(150) NOT NULL,
  `act_name` varchar(50) NOT NULL,
  `remark` varchar(300) NOT NULL,
  `hb_type` tinyint(1) NOT NULL default '1',
  `group_nums` int(10) NOT NULL,
  `send_type` tinyint(1) NOT NULL default '1',
  `gid` int(10) NOT NULL,
  `fans_id` text NOT NULL,
  `fans_name` text NOT NULL,
  `lastsendtime` int(10) NOT NULL,
  `totalnums` int(10) NOT NULL,
  `token` char(25) NOT NULL,
  `send_status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>