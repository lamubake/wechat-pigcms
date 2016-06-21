<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_employee`;");
E_C("CREATE TABLE `imicms_cashier_employee` (
  `eid` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL,
  `username` char(50) NOT NULL,
  `account` char(100) NOT NULL,
  `password` char(32) NOT NULL,
  `email` char(200) NOT NULL,
  `salt` char(20) NOT NULL,
  `authority` text,
  `status` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`eid`),
  KEY `mid` USING BTREE (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>