<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_scenedatadetail`;");
E_C("CREATE TABLE `imicms_scenedatadetail` (
  `detailid_bigint` bigint(20) NOT NULL auto_increment,
  `sceneid_bigint` bigint(20) NOT NULL default '0',
  `pageid_bigint` bigint(20) NOT NULL default '0',
  `createtime_time` datetime default NULL,
  `ip_varchar` varchar(50) default NULL,
  `content_varchar` text,
  `is_import` tinyint(1) unsigned NOT NULL default '0',
  `userid` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`detailid_bigint`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>