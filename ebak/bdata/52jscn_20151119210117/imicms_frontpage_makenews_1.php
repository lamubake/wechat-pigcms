<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_frontpage_makenews`;");
E_C("CREATE TABLE `imicms_frontpage_makenews` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `wecha_name` varchar(50) NOT NULL,
  `news_txt` text NOT NULL,
  `news_title` varchar(100) NOT NULL,
  `spd` tinyint(1) NOT NULL,
  `per` tinyint(1) NOT NULL,
  `frontpage_name` char(30) NOT NULL default '',
  `news_type` tinyint(1) NOT NULL,
  `token` char(25) NOT NULL,
  `create_time` int(11) NOT NULL,
  `voicepath` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL,
  `frontpage_img` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>