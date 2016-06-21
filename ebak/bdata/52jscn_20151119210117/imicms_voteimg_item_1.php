<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_voteimg_item`;");
E_C("CREATE TABLE `imicms_voteimg_item` (
  `id` int(11) NOT NULL auto_increment,
  `vote_id` int(11) NOT NULL COMMENT '图片投票id',
  `baby_id` int(11) NOT NULL,
  `vote_title` varchar(100) NOT NULL default '' COMMENT '图片描述',
  `introduction` text NOT NULL COMMENT '自我介绍',
  `manifesto` varchar(255) NOT NULL COMMENT '拉票宣言',
  `vote_img` varchar(500) NOT NULL default '' COMMENT '图片地址',
  `jump_url` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL COMMENT '手机号',
  `vote_count` int(11) NOT NULL COMMENT '获得票数',
  `upload_time` int(11) NOT NULL COMMENT '上传时间',
  `token` varchar(50) NOT NULL default '' COMMENT 'token',
  `check_pass` tinyint(1) NOT NULL COMMENT '审核',
  `wecha_id` varchar(100) NOT NULL default '',
  `upload_type` tinyint(1) NOT NULL COMMENT '区分管理上传还是报名',
  PRIMARY KEY  (`id`),
  KEY `vote_id` USING BTREE (`vote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>