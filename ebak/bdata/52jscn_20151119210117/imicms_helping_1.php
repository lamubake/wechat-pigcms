<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_helping`;");
E_C("CREATE TABLE `imicms_helping` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `info` text NOT NULL,
  `reply_pic` varchar(250) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `add_time` char(15) NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `is_newtp` int(11) NOT NULL default '0',
  `is_sms` int(11) NOT NULL default '0',
  `fxtitle` varchar(200) default NULL,
  `fxinfo` varchar(300) default NULL,
  `rank_num` int(11) default NULL,
  `pv` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `is_open` (`is_open`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>