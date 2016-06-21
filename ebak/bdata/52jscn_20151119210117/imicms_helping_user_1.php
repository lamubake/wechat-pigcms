<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_helping_user`;");
E_C("CREATE TABLE `imicms_helping_user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `help_count` int(11) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_key` char(40) NOT NULL,
  `help_num` int(100) NOT NULL,
  `help_rank` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `is_join` int(11) NOT NULL default '0',
  `is_join2` int(11) NOT NULL default '1',
  `share_num` int(11) NOT NULL default '0',
  `pv` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `wecha_id` (`wecha_id`),
  KEY `pid` (`pid`),
  KEY `share_key` (`share_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>