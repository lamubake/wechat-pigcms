<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_heka`;");
E_C("CREATE TABLE `imicms_heka` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(50) default NULL,
  `token` varchar(100) NOT NULL,
  `bg_topic` varchar(100) default NULL,
  `bg_music` varchar(100) default NULL,
  `bg_action` int(2) default NULL,
  `content` varchar(500) default NULL,
  `sub` varchar(20) default NULL,
  `show` int(1) default NULL,
  `url` varchar(100) default NULL,
  `name` varchar(20) default NULL,
  `fname` varchar(20) default NULL,
  `banquan` varchar(100) default NULL COMMENT '版权',
  `see` int(20) default '0' COMMENT '查看次数',
  `forwards` int(20) default '0' COMMENT '转发次数',
  `keyword` varchar(50) default NULL,
  `topic` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>