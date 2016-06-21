<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wewall`;");
E_C("CREATE TABLE `imicms_wewall` (
  `id` int(11) NOT NULL auto_increment,
  `acttitle` varchar(40) NOT NULL COMMENT '活动标题',
  `isact` int(1) NOT NULL default '0' COMMENT '活动开关',
  `ifcheck` int(1) NOT NULL default '0' COMMENT '是否审核',
  `iflottery` int(1) NOT NULL default '1',
  `showtime` int(11) NOT NULL COMMENT '前台页面刷新间隔',
  `background` varchar(300) default NULL COMMENT '前台页面背景',
  `marklog` varchar(100) default NULL COMMENT '成绩记录',
  `createtime` int(20) NOT NULL COMMENT '创建时间',
  `endtime` int(20) default NULL COMMENT '结束时间',
  `token` varchar(40) NOT NULL,
  `bonu1` int(11) default NULL COMMENT '一等奖数量',
  `bonu2` int(11) default NULL COMMENT '二等奖数量',
  `bonu3` int(11) default NULL COMMENT '三等奖数量',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>