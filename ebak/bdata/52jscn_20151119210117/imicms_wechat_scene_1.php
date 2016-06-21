<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wechat_scene`;");
E_C("CREATE TABLE `imicms_wechat_scene` (
  `id` int(11) NOT NULL auto_increment,
  `keyword` char(30) NOT NULL,
  `title` char(50) NOT NULL,
  `pic` char(100) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `shake_id` int(10) unsigned NOT NULL,
  `wall_id` int(10) unsigned NOT NULL,
  `vote_id` char(25) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `open_vote` enum('0','1') NOT NULL,
  `open_zzle` enum('0','1') NOT NULL,
  `open_lottery` enum('0','1') NOT NULL,
  `token` char(20) NOT NULL,
  `logo` char(100) NOT NULL,
  `background` char(100) NOT NULL,
  `qrcode` char(100) NOT NULL,
  `lottery_type` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>