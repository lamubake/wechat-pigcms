<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_vote_record`;");
E_C("CREATE TABLE `imicms_vote_record` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `pid` bigint(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `wecha_id` varchar(255) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `votetime` int(10) unsigned NOT NULL default '0',
  `votes` varchar(100) NOT NULL default '',
  `item_id` varchar(50) NOT NULL,
  `vid` int(11) NOT NULL,
  `touched` tinyint(4) NOT NULL,
  `touch_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `itemid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>