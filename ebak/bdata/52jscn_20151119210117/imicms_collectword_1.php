<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_collectword`;");
E_C("CREATE TABLE `imicms_collectword` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `word` varchar(20) NOT NULL,
  `intro` varchar(200) NOT NULL default '',
  `info` text,
  `reply_pic` varchar(200) NOT NULL,
  `start` int(11) NOT NULL default '0',
  `end` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  `is_open` int(11) NOT NULL default '0',
  `is_reg` int(11) NOT NULL default '0',
  `is_attention` int(11) NOT NULL default '0',
  `is_sms` int(11) NOT NULL default '0',
  `fxtitle` varchar(200) NOT NULL default '',
  `fxinfo` varchar(200) NOT NULL default '',
  `rank_num` int(11) NOT NULL default '10',
  `count` int(10) unsigned NOT NULL,
  `help_count` int(10) unsigned NOT NULL,
  `prize_display` tinyint(3) unsigned NOT NULL default '0',
  `prize_fxtitle` varchar(200) NOT NULL default '',
  `prize_fxinfo` varchar(200) NOT NULL default '',
  `day_count` int(10) unsigned NOT NULL default '0',
  `expect` int(10) unsigned NOT NULL default '0',
  `fxpic` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `title` (`title`),
  KEY `is_open` (`is_open`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>