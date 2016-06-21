<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_orderprinter`;");
E_C("CREATE TABLE `imicms_orderprinter` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(20) NOT NULL default '',
  `companyid` int(10) NOT NULL default '0',
  `mcode` varchar(60) NOT NULL default '',
  `mkey` varchar(60) NOT NULL default '',
  `mp` varchar(50) NOT NULL default '',
  `count` mediumint(5) NOT NULL default '1',
  `modules` varchar(100) NOT NULL default '',
  `paid` tinyint(1) NOT NULL default '0',
  `name` varchar(100) default NULL,
  `qr` varchar(200) default NULL,
  `type` tinyint(1) NOT NULL default '0',
  `apikey` varchar(100) NOT NULL default '' COMMENT 'apiKey',
  `printtype` varchar(2) NOT NULL default '',
  `mc` varchar(50) NOT NULL default '',
  `number` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>