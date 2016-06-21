<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_upfilesys`;");
E_C("CREATE TABLE `imicms_upfilesys` (
  `fileid_bigint` bigint(20) NOT NULL auto_increment,
  `userid_int` int(11) NOT NULL,
  `filetype_int` int(11) NOT NULL default '0' COMMENT '0背景,2音乐,1图片',
  `filesrc_varchar` varchar(200) default NULL,
  `create_time` datetime default NULL,
  `sizekb_int` decimal(18,2) default '0.00',
  `filethumbsrc_varchar` varchar(200) default NULL,
  `biztype_int` int(11) default '0',
  `ext_varchar` varchar(50) default NULL,
  `filename_varchar` varchar(100) default NULL,
  `eqsrc_varchar` varchar(200) default NULL,
  `tagid_int` int(11) default '0',
  `eqsrcthumb_varchar` varchar(200) default NULL,
  PRIMARY KEY  (`fileid_bigint`),
  KEY `userid` USING BTREE (`userid_int`,`filetype_int`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT");

require("../../inc/footer.php");
?>