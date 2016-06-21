<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_unitary`;");
E_C("CREATE TABLE `imicms_unitary` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(100) default NULL,
  `keyword` varchar(100) default NULL,
  `name` varchar(100) default NULL COMMENT '名称/微信中图文信息标题',
  `wxpic` varchar(100) default NULL COMMENT '微信中图文信息图片',
  `wxinfo` varchar(100) default NULL COMMENT '微信中图文信息说明',
  `wxregister` int(11) NOT NULL default '1' COMMENT '关注/注册',
  `register` int(11) NOT NULL default '0' COMMENT '注册/不注册',
  `price` int(11) default NULL COMMENT '价格',
  `type` int(11) default NULL COMMENT '分类',
  `logopic` varchar(100) default NULL COMMENT 'logo图片',
  `fistpic` varchar(100) default NULL COMMENT '展示图片1',
  `secondpic` varchar(100) default NULL COMMENT '展示图片2',
  `thirdpic` varchar(100) default NULL COMMENT '展示图片3',
  `fourpic` varchar(100) default NULL COMMENT '展示图片4',
  `fivepic` varchar(100) default NULL COMMENT '展示图片5',
  `sixpic` varchar(100) default NULL COMMENT '展示图片6',
  `addtime` int(11) default NULL COMMENT '添加时间',
  `opentime` int(11) default NULL COMMENT '结束后展示结果倒计时',
  `endtime` int(11) default NULL COMMENT '结束时间',
  `state` int(11) default NULL COMMENT '活动开关',
  `renqi` int(11) NOT NULL default '0' COMMENT '人气',
  `lucknum` int(11) default NULL COMMENT '幸运数字',
  `proportion` double NOT NULL default '0',
  `lasttime` int(11) default NULL,
  `lastnum` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>