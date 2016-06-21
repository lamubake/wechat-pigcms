<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_medical_user`;");
E_C("CREATE TABLE `imicms_medical_user` (
  `iid` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL default '',
  `wecha_id` varchar(50) NOT NULL default '',
  `rid` int(11) NOT NULL,
  `type` varchar(20) NOT NULL default '',
  `truename` varchar(50) NOT NULL default '',
  `utel` char(13) NOT NULL,
  `dateline` varchar(50) NOT NULL,
  `sex` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `txt33` varchar(50) NOT NULL default '',
  `txt44` varchar(50) NOT NULL default '',
  `txt55` varchar(50) NOT NULL default '',
  `yyks` varchar(50) NOT NULL default '',
  `yyzj` varchar(50) NOT NULL default '',
  `yybz` varchar(50) NOT NULL default '',
  `yy4` varchar(50) NOT NULL default '',
  `yy5` varchar(50) NOT NULL default '',
  `uinfo` varchar(50) NOT NULL default '',
  `kfinfo` varchar(100) NOT NULL default '',
  `remate` int(10) NOT NULL default '0',
  `booktime` int(11) default NULL,
  `paid` tinyint(4) default '0',
  `orderid` bigint(20) default NULL,
  `price` decimal(10,2) NOT NULL default '0.00',
  `orderName` varchar(200) NOT NULL default '',
  `txt3name` varchar(50) NOT NULL default '',
  `txt4name` varchar(50) NOT NULL default '',
  `txt5name` varchar(50) NOT NULL default '',
  `select4name` varchar(50) NOT NULL default '',
  `select5name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>