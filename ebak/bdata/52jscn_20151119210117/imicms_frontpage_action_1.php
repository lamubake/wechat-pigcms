<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_frontpage_action`;");
E_C("CREATE TABLE `imicms_frontpage_action` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `action_name` varchar(100) NOT NULL default '',
  `keyword` char(30) NOT NULL,
  `reply_pic` varchar(255) NOT NULL,
  `reply_title` varchar(100) NOT NULL,
  `reply_content` varchar(255) NOT NULL,
  `remind_word` varchar(255) NOT NULL,
  `remind_img` varchar(255) NOT NULL,
  `remind_link` varchar(255) NOT NULL,
  `total_create` int(11) NOT NULL,
  `day_create` int(11) NOT NULL,
  `sponsors` varchar(50) NOT NULL default '',
  `is_follow` tinyint(1) NOT NULL,
  `is_register` tinyint(1) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL default '',
  `custom_sharedsc` varchar(500) NOT NULL default '',
  `sharecount` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `token` char(25) NOT NULL,
  `defaultnews_hide` varchar(255) NOT NULL default '',
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `latest_count` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>