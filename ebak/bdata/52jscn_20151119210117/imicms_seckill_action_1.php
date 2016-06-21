<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_seckill_action`;");
E_C("CREATE TABLE `imicms_seckill_action` (
  `action_id` int(11) NOT NULL auto_increment,
  `action_name` varchar(20) NOT NULL COMMENT '活动名称',
  `action_header_img` text NOT NULL COMMENT '活动头部图片',
  `action_key` varchar(50) NOT NULL COMMENT '活动key',
  `action_sdate` int(11) NOT NULL COMMENT '活动开始时间',
  `action_edate` int(11) NOT NULL COMMENT '活动结束时间',
  `rand_min_time` int(11) NOT NULL COMMENT '最小分享时间',
  `rand_max_time` int(11) NOT NULL COMMENT '最大分享时间',
  `reply_pic` text COMMENT '活动图片',
  `action_token` varchar(50) default '' COMMENT '活动发起人',
  `action_rule` text COMMENT '活动规则',
  `action_open` tinyint(4) default '0' COMMENT '活动状态',
  `reply_title` varchar(20) default '' COMMENT '回复标题',
  `reply_content` varchar(200) default '' COMMENT '回复简介',
  `reply_keyword` varchar(50) default '' COMMENT '关键词',
  `action_is_reg` tinyint(4) NOT NULL default '1',
  `action_is_attention` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`action_id`),
  KEY `action_name` USING BTREE (`action_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>