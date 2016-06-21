<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_company_staff`;");
E_C("CREATE TABLE `imicms_company_staff` (
  `id` int(10) NOT NULL auto_increment,
  `companyid` int(10) NOT NULL,
  `token` varchar(30) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `username` varchar(20) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `tel` varchar(11) NOT NULL default '',
  `time` int(10) NOT NULL,
  `func` varchar(1000) NOT NULL,
  `pcorwap` enum('pc','wap') NOT NULL,
  `wecha_id` char(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `companyid` (`companyid`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>