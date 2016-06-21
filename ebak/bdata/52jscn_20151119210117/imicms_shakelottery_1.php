<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shakelottery`;");
E_C("CREATE TABLE `imicms_shakelottery` (
  `id` int(11) NOT NULL auto_increment,
  `action_name` varchar(50) NOT NULL default '',
  `reply_title` varchar(50) NOT NULL default '',
  `reply_content` varchar(200) NOT NULL default '',
  `reply_pic` varchar(255) NOT NULL default '',
  `action_desc` text NOT NULL,
  `keyword` varchar(50) NOT NULL default '',
  `remind_word` varchar(255) NOT NULL default '',
  `remind_link` varchar(255) NOT NULL default '',
  `totaltimes` int(11) NOT NULL default '1',
  `everydaytimes` int(11) NOT NULL default '0',
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `timespan` int(11) NOT NULL,
  `record_nums` int(11) NOT NULL,
  `is_limitwin` int(11) NOT NULL,
  `is_follow` tinyint(1) NOT NULL default '1',
  `is_register` tinyint(1) NOT NULL default '1',
  `share_count` int(11) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL,
  `custom_sharedsc` varchar(500) NOT NULL,
  `follow_msg` varchar(255) NOT NULL,
  `follow_btn_msg` varchar(255) NOT NULL,
  `register_msg` varchar(255) NOT NULL,
  `custom_follow_url` varchar(255) NOT NULL,
  `token` varchar(30) NOT NULL default '',
  `status` tinyint(1) NOT NULL,
  `join_number` int(11) NOT NULL,
  `actual_join_number` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>