<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_scenepage`;");
E_C("CREATE TABLE `imicms_scenepage` (
  `pageid_bigint` bigint(20) NOT NULL auto_increment,
  `sceneid_bigint` bigint(20) NOT NULL,
  `scenecode_varchar` varchar(50) NOT NULL,
  `pagecurrentnum_int` int(11) NOT NULL default '1' COMMENT '当前页数',
  `createtime_time` datetime default NULL,
  `content_text` longtext,
  `pagename_varchar` varchar(50) default NULL,
  `userid_int` int(11) NOT NULL,
  `properties_text` text NOT NULL,
  `myTypl_id` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pageid_bigint`),
  KEY `sceneid` USING BTREE (`scenecode_varchar`),
  KEY `scenid` USING BTREE (`sceneid_bigint`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>