<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sentiment`;");
E_C("CREATE TABLE `imicms_sentiment` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `intro` text,
  `info` text,
  `reply_pic` varchar(200) NOT NULL,
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  `is_open` int(11) NOT NULL default '0',
  `is_reg` int(11) NOT NULL default '0',
  `is_attention` int(11) NOT NULL default '0',
  `is_sms` int(11) NOT NULL default '0',
  `fxtitle` varchar(200) default NULL,
  `is_nosex` int(11) NOT NULL default '0',
  `opposite_sex` varchar(20) NOT NULL default '0',
  `same_sex` varchar(20) NOT NULL default '0',
  `no_sex` varchar(20) NOT NULL default '0',
  `man_label` text NOT NULL,
  `woman_label` text NOT NULL,
  `name_zhi` varchar(50) NOT NULL default '魅力值',
  `rank_num` int(11) NOT NULL default '10',
  `fxinfo` varchar(300) default NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `title` (`title`),
  KEY `is_open` (`is_open`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>