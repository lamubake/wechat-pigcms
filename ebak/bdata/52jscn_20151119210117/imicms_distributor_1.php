<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distributor`;");
E_C("CREATE TABLE `imicms_distributor` (
  `id` int(10) NOT NULL auto_increment COMMENT '分销商id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `name` varchar(50) NOT NULL default '' COMMENT '姓名',
  `tel` varchar(20) NOT NULL default '' COMMENT '电话',
  `addr` varchar(500) NOT NULL default '' COMMENT '地址',
  `latitude` double NOT NULL default '0' COMMENT '经度',
  `longitude` double NOT NULL default '0' COMMENT '纬度',
  `intro` text NOT NULL COMMENT '简介',
  `ischannel` char(1) NOT NULL default '0' COMMENT '渠道商 0,1',
  `status` char(1) NOT NULL default '1' COMMENT '状态',
  `balance` decimal(8,2) NOT NULL default '0.00' COMMENT '未提现金额',
  `paid` decimal(8,2) NOT NULL default '0.00' COMMENT '已提现金额',
  `checked` char(1) NOT NULL default '0' COMMENT '审核 0,1',
  `regtime` varchar(20) NOT NULL default '0' COMMENT '注册时间',
  `wecha_id` varchar(60) NOT NULL default '0' COMMENT '粉丝识别码',
  `token` varchar(50) NOT NULL default '0' COMMENT '主商城识别码',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `wecha_id` (`wecha_id`),
  KEY `token` USING BTREE (`token`),
  KEY `uid` USING BTREE (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商'");

require("../../inc/footer.php");
?>