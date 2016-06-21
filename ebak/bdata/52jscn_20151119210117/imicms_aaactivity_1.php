<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_aaactivity`;");
E_C("CREATE TABLE `imicms_aaactivity` (
  `id` int(11) NOT NULL auto_increment,
  `joinnum` int(11) NOT NULL COMMENT '已人数',
  `wecha_id` varchar(100) NOT NULL default '' COMMENT '发起人ID',
  `usename` varchar(100) NOT NULL default '' COMMENT '发起人名字',
  `tel` char(20) NOT NULL default '' COMMENT '发起者TEl',
  `token` varchar(30) NOT NULL default '',
  `keyword` varchar(10) NOT NULL,
  `top_pic` varchar(200) NOT NULL default '' COMMENT '活动顶部图片',
  `reply_pic` varchar(200) NOT NULL default '' COMMENT '回复图片',
  `share_pic` varchar(200) NOT NULL default '' COMMENT '分享小图',
  `title` varchar(60) NOT NULL COMMENT '活动名称',
  `statdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `intro` varchar(200) NOT NULL default '' COMMENT '活动说明',
  `info` varchar(255) NOT NULL default '' COMMENT '活动规则',
  `txtaudit` varchar(250) NOT NULL default '' COMMENT '提交说明',
  `type` tinyint(1) NOT NULL,
  `score` char(100) NOT NULL default '' COMMENT '扣除积分',
  `feiyong` char(100) NOT NULL default '' COMMENT 'AA费用',
  `backscore` char(100) NOT NULL default '' COMMENT '参与活动赠送积分',
  `backfeiyong` char(100) NOT NULL default '' COMMENT '商家赞助多少',
  `share_score` int(11) NOT NULL COMMENT '分享每拉人参与进来获得积分',
  `share_feiyong` int(11) NOT NULL COMMENT '分享每拉人参与进来获得金额',
  `minnums` int(11) NOT NULL COMMENT '最小报名人数',
  `maxnums` int(11) NOT NULL COMMENT '最大参与人数',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `zjpic` varchar(150) NOT NULL default '' COMMENT '活动图片',
  `daynums` mediumint(4) NOT NULL default '0' COMMENT '活动时长',
  `is_user` int(11) NOT NULL default '0' COMMENT '是否是用户发起的',
  `is_audit` int(11) NOT NULL default '0' COMMENT '是否审通过',
  `is_sub` int(11) NOT NULL default '0' COMMENT '是否关注',
  `is_reg` int(11) NOT NULL default '0' COMMENT '是否注册',
  `is_share_score` int(11) NOT NULL default '0' COMMENT '是否开启拉人送积分',
  `is_open` int(11) NOT NULL default '0',
  `is_score` int(11) NOT NULL default '0' COMMENT '是否开启扣除积分',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>