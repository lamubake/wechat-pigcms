<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_directhongbao_record`;");
E_C("CREATE TABLE `imicms_directhongbao_record` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hid` int(10) unsigned NOT NULL,
  `mch_billno` char(50) NOT NULL default '',
  `fans_id` varchar(60) NOT NULL default '',
  `fans_nickname` varchar(60) NOT NULL default '',
  `money` float(6,2) default NULL,
  `status` char(20) NOT NULL default '',
  `hb_type` tinyint(1) NOT NULL,
  `token` char(25) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `hid` USING BTREE (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>