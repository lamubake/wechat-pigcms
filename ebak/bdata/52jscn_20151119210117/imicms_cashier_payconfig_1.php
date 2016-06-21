<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_payconfig`;");
E_C("CREATE TABLE `imicms_cashier_payconfig` (
  `id` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL,
  `isOpen` tinyint(1) NOT NULL default '0',
  `configData` varchar(2000) default NULL,
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>