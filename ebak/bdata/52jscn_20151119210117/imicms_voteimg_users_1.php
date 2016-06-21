<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_voteimg_users`;");
E_C("CREATE TABLE `imicms_voteimg_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL COMMENT '活动id',
  `item_id` text NOT NULL COMMENT '投票选项',
  `wecha_id` varchar(100) NOT NULL,
  `nick_name` varchar(255) NOT NULL COMMENT '微信昵称',
  `votenum` int(11) NOT NULL COMMENT '已投票数',
  `votenum_day` int(11) NOT NULL COMMENT '今日已投票数',
  `vote_today` text NOT NULL,
  `vote_time` int(11) NOT NULL COMMENT '投票时间',
  `token` varchar(50) NOT NULL default '' COMMENT 'token',
  `phone` varchar(11) NOT NULL,
  PRIMARY KEY  (`user_id`),
  KEY `vote_id` USING BTREE (`vote_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>