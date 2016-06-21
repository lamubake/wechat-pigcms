<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_scene`;");
E_C("CREATE TABLE `imicms_scene` (
  `sceneid_bigint` bigint(20) NOT NULL auto_increment,
  `scenecode_varchar` varchar(50) NOT NULL,
  `scenename_varchar` varchar(50) default NULL,
  `scenetype_int` int(11) NOT NULL default '0',
  `userid_int` int(50) NOT NULL,
  `hitcount_int` int(11) NOT NULL default '0',
  `createtime_time` datetime default NULL,
  `musicurl_varchar` varchar(500) default NULL,
  `videocode_varchar` varchar(2000) default NULL,
  `showstatus_int` int(11) NOT NULL default '1' COMMENT '显示状态1显示,2关闭',
  `thumbnail_varchar` varchar(200) default NULL COMMENT '缩略图',
  `movietype_int` int(11) default '0' COMMENT '翻页方式',
  `desc_varchar` varchar(500) default NULL COMMENT '场景描述',
  `ip_varchar` varchar(50) default NULL,
  `delete_int` int(11) NOT NULL default '0' COMMENT '0未删,1已经删除 ',
  `tagid_int` int(11) NOT NULL default '0',
  `sourceId_int` int(11) NOT NULL default '0',
  `biztype_int` int(11) NOT NULL default '1',
  `eqid_int` int(11) default NULL,
  `eqcode` varchar(50) default NULL,
  `datacount_int` int(11) NOT NULL default '0',
  `musictype_int` int(11) NOT NULL default '3',
  `usecount_int` int(11) NOT NULL default '0',
  `fromsceneid_bigint` bigint(20) NOT NULL default '0',
  `publishTime` int(10) unsigned NOT NULL default '0',
  `updateTime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`sceneid_bigint`),
  UNIQUE KEY `scenecode` (`scenecode_varchar`),
  KEY `userid` USING BTREE (`userid_int`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>