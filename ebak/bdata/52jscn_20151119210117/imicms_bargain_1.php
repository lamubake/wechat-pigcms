<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_bargain`;");
E_C("CREATE TABLE `imicms_bargain` (
  `imicms_id` int(100) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '商品名称',
  `keyword` varchar(100) NOT NULL COMMENT '关键词',
  `wxtitle` varchar(100) NOT NULL COMMENT '图文回复标题',
  `wxpic` varchar(100) NOT NULL COMMENT '图文回复图片',
  `wxinfo` varchar(200) default NULL COMMENT '图文回复简单描述',
  `logoimg1` varchar(100) NOT NULL COMMENT '商品图片1',
  `logourl1` varchar(200) NOT NULL COMMENT '商品图片链接1',
  `logoimg2` varchar(100) default NULL COMMENT '商品图片2',
  `logourl2` varchar(200) default NULL COMMENT '商品图片链接2',
  `logoimg3` varchar(100) default NULL COMMENT '商品图片3',
  `logourl3` varchar(200) default NULL COMMENT '商品图片链接3',
  `info` mediumtext COMMENT '商品描述',
  `guize` mediumtext,
  `original` int(20) NOT NULL COMMENT '原价',
  `minimum` int(20) NOT NULL COMMENT '底价',
  `starttime` int(20) NOT NULL COMMENT '开始时间',
  `inventory` int(20) NOT NULL COMMENT '库存',
  `qdao` int(11) default NULL COMMENT '前n刀',
  `qprice` int(20) default NULL COMMENT '前n刀砍去多少钱',
  `dao` int(11) NOT NULL COMMENT '总共需要n刀',
  `pv` int(11) NOT NULL default '0',
  `state` int(11) NOT NULL default '1' COMMENT '开始-关闭',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY  (`imicms_id`),
  KEY `token` USING BTREE (`token`),
  KEY `name` USING BTREE (`name`),
  KEY `state` USING BTREE (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>