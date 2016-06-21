<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_frontpage_users`;");
E_C("CREATE TABLE `imicms_frontpage_users` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `total_create` int(11) NOT NULL,
  `today_create` int(11) NOT NULL,
  `wecha_id` varchar(50) NOT NULL default '',
  `wecha_name` varchar(50) NOT NULL default '',
  `wecha_pic` varchar(255) NOT NULL default '',
  `phone` varchar(15) NOT NULL default '',
  `token` varchar(30) NOT NULL default '',
  `share_key` varchar(100) NOT NULL default '',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>