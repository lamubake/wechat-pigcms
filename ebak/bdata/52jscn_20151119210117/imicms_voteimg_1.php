<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_voteimg`;");
E_C("CREATE TABLE `imicms_voteimg` (
  `id` int(11) NOT NULL auto_increment,
  `action_name` varchar(50) NOT NULL,
  `action_desc` text NOT NULL,
  `award_desc` text NOT NULL,
  `flow_desc` text NOT NULL,
  `join_desc` text NOT NULL,
  `keyword` varchar(50) NOT NULL default '' COMMENT '回复关键词',
  `reply_title` varchar(50) NOT NULL default '' COMMENT '回复标题',
  `reply_content` varchar(200) NOT NULL default '' COMMENT '回复内容',
  `reply_pic` varchar(255) NOT NULL COMMENT '回复图片',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `apply_start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `apply_end_time` int(11) NOT NULL,
  `is_follow` tinyint(1) NOT NULL default '1' COMMENT '是否需要关注',
  `is_register` tinyint(1) NOT NULL default '1' COMMENT '是否需要注册',
  `limit_vote` int(11) NOT NULL COMMENT '限制投票数',
  `limit_vote_day` int(11) NOT NULL COMMENT '限制每天投票数',
  `limit_vote_item` int(11) NOT NULL,
  `phone` char(50) NOT NULL,
  `token` varchar(50) NOT NULL default '' COMMENT 'token',
  `page_type` enum('waterfall','page') NOT NULL default 'waterfall',
  `display` tinyint(1) NOT NULL,
  `default_skin` tinyint(1) NOT NULL,
  `follow_msg` varchar(500) NOT NULL,
  `follow_url` varchar(255) NOT NULL,
  `self_status` tinyint(1) NOT NULL,
  `follow_btn_msg` varchar(255) NOT NULL,
  `register_msg` varchar(255) NOT NULL,
  `territory_limit` tinyint(1) NOT NULL,
  `pro_city` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>