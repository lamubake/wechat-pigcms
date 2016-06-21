<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_scenedatasys`;");
E_C("CREATE TABLE `imicms_scenedatasys` (
  `dataid_bigint` bigint(20) NOT NULL auto_increment,
  `sceneid_bigint` bigint(20) NOT NULL default '0',
  `pageid_bigint` bigint(20) NOT NULL default '0',
  `elementid_int` int(11) default '0',
  `elementtitle_varchar` varchar(50) default NULL,
  `elementtype_int` int(11) NOT NULL default '5',
  `userid_int` int(11) NOT NULL default '0',
  PRIMARY KEY  (`dataid_bigint`),
  KEY `sceneid` USING BTREE (`sceneid_bigint`,`userid_int`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>