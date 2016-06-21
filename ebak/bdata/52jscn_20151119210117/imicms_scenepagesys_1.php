<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_scenepagesys`;");
E_C("CREATE TABLE `imicms_scenepagesys` (
  `pageid_bigint` bigint(20) NOT NULL auto_increment,
  `sceneid_bigint` bigint(20) NOT NULL,
  `scenecode_varchar` varchar(50) NOT NULL,
  `pagecurrentnum_int` int(11) NOT NULL default '1' COMMENT '当前页数',
  `createtime_time` datetime default NULL,
  `content_text` longtext,
  `pagename_varchar` varchar(50) default NULL,
  `userid_int` int(11) NOT NULL,
  `biztype_int` int(11) default NULL,
  `tagid_int` int(11) default NULL,
  `thumbsrc_varchar` varchar(200) default NULL,
  `eqsrc_varchar` varchar(200) default NULL,
  `eqid_int` int(11) default NULL,
  `usecount_int` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pageid_bigint`),
  KEY `sceneid` USING BTREE (`scenecode_varchar`),
  KEY `scenid` USING BTREE (`sceneid_bigint`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>