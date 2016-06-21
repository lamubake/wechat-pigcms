<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_goldegg`;");
E_C("CREATE TABLE `imicms_goldegg` (
  `id` int(11) NOT NULL auto_increment,
  `joinnum` int(11) NOT NULL COMMENT '参与人数',
  `click` int(11) NOT NULL COMMENT '浏览人数',
  `token` varchar(255) NOT NULL COMMENT '微信TOKEN',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `startpicurl` varchar(255) NOT NULL COMMENT '填写活动开始图片网址',
  `title` varchar(255) NOT NULL COMMENT '活动名称',
  `txt` varchar(255) NOT NULL COMMENT '用户输入兑奖时候的显示信息',
  `summary` varchar(255) NOT NULL COMMENT '简介',
  `startdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `info` varchar(255) NOT NULL COMMENT '活动说明',
  `aginfo` varchar(255) NOT NULL COMMENT '重复抽奖回复',
  `endtite` varchar(255) NOT NULL COMMENT '活动结束公告主题',
  `endpicurl` varchar(255) NOT NULL COMMENT '活动结束回复图片',
  `endinfo` varchar(255) NOT NULL COMMENT '活动结束回复',
  `allpeople` int(11) NOT NULL COMMENT '预计活动的人数',
  `canrqnums` int(22) NOT NULL COMMENT '个人限制抽奖次数',
  `parssword` int(15) NOT NULL COMMENT '兑奖密码',
  `snimport` tinyint(1) NOT NULL COMMENT 'SN码生成设置',
  `renamesn` varchar(60) NOT NULL default 'SN码' COMMENT 'SN码重命名为',
  `renametel` varchar(60) NOT NULL default '手机号' COMMENT '手机号重命名',
  `displayjpnums` int(1) NOT NULL COMMENT '抽奖页面是否显示奖品数量',
  `createtime` int(11) NOT NULL COMMENT '活动创建时间',
  `status` int(1) NOT NULL COMMENT '活动状态,0未开始,1进行中,2已关闭',
  `verify` int(1) NOT NULL COMMENT '是否需要验证码',
  `verifynum` int(11) NOT NULL default '0' COMMENT '验证码次数',
  `verifycode` varchar(255) NOT NULL COMMENT '验证码列表',
  `type` varchar(10) NOT NULL COMMENT '活动类型',
  `first` varchar(50) NOT NULL COMMENT '一等奖奖品设置',
  `firstnums` int(4) NOT NULL COMMENT '一等奖奖品数量',
  `firstlucknums` int(11) NOT NULL COMMENT '一等奖中奖号码',
  `second` varchar(50) NOT NULL COMMENT '二等奖奖品设置',
  `secondnums` int(4) NOT NULL COMMENT '二等奖奖品数量',
  `secondlucknums` int(11) NOT NULL COMMENT '二等奖中奖号码',
  `third` varchar(50) NOT NULL COMMENT '三等奖',
  `thirdnums` int(4) NOT NULL COMMENT '二等奖',
  `thirdlucknums` int(11) NOT NULL COMMENT '二等奖',
  `four` varchar(50) NOT NULL COMMENT '四等奖奖品设置',
  `fournums` int(11) NOT NULL COMMENT '四等奖奖品数量',
  `fourlucknums` int(11) NOT NULL COMMENT '四等奖中奖号码',
  `five` varchar(50) NOT NULL COMMENT '五等奖',
  `fivenums` int(11) NOT NULL COMMENT '五等奖',
  `fivelucknums` int(11) NOT NULL COMMENT '五等奖',
  `six` varchar(50) NOT NULL COMMENT '六等奖奖品设置',
  `sixnums` int(11) NOT NULL COMMENT '六等奖奖品数量',
  `sixlucknums` int(11) NOT NULL COMMENT '六等奖中奖号码',
  `redo` int(1) NOT NULL default '0' COMMENT '是否允许继续操作1为允许0为不允许',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>