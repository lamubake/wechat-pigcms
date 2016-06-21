<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cointree`;");
E_C("CREATE TABLE `imicms_cointree` (
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
  `join_number` int(11) NOT NULL,
  `actual_join_number` int(11) NOT NULL,
  `everydaytimes` int(11) NOT NULL default '0',
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `usedup_conins` int(11) NOT NULL,
  `gain_conins` int(11) NOT NULL default '1',
  `timespan` int(11) NOT NULL,
  `record_nums` int(11) NOT NULL,
  `coinrecord_nums` int(11) NOT NULL,
  `is_limitwin` tinyint(1) NOT NULL default '1',
  `is_follow` tinyint(1) NOT NULL default '1',
  `is_register` tinyint(1) NOT NULL default '1',
  `token` varchar(30) NOT NULL default '',
  `status` tinyint(1) NOT NULL,
  `fistlucknums` int(11) NOT NULL,
  `secondlucknums` int(11) NOT NULL,
  `thirdlucknums` int(11) NOT NULL,
  `fourlucknums` int(11) NOT NULL,
  `fivelucknums` int(11) NOT NULL,
  `sixlucknums` int(11) NOT NULL,
  `is_amount` tinyint(1) NOT NULL,
  `share_count` int(11) NOT NULL,
  `custom_sharetitle` varchar(255) NOT NULL default '',
  `custom_sharedsc` varchar(500) NOT NULL default '',
  `sms_verify` tinyint(1) NOT NULL,
  `follow_msg` varchar(255) NOT NULL,
  `follow_btn_msg` varchar(255) NOT NULL,
  `register_msg` varchar(255) NOT NULL,
  `custom_follow_url` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>