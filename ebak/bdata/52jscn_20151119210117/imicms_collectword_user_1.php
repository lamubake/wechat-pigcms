<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_collectword_user`;");
E_C("CREATE TABLE `imicms_collectword_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `is_prize` tinyint(3) unsigned NOT NULL default '0',
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL default '0',
  `share_key` varchar(100) NOT NULL default '0',
  `share_num` int(11) NOT NULL default '0',
  `tel` varchar(50) NOT NULL default '0',
  `is_join` tinyint(3) unsigned NOT NULL default '0',
  `count` int(10) unsigned NOT NULL default '0',
  `word_count` int(10) unsigned NOT NULL default '0',
  `update_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `wecha_id` (`wecha_id`),
  KEY `pid` (`pid`),
  KEY `share_key` (`share_key`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>