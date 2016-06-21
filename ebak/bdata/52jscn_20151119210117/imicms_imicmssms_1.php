<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_imicmssms`;");
E_C("CREATE TABLE `imicms_imicmssms` (
  `token` varchar(60) NOT NULL,
  `phone` varchar(40) NOT NULL default '',
  `name` varchar(40) NOT NULL default '',
  `password` varchar(60) NOT NULL default '',
  `shangcheng` tinyint(1) NOT NULL default '0',
  `yuyue` tinyint(1) NOT NULL default '0',
  `baom` tinyint(1) NOT NULL default '0',
  `zxyy` tinyint(1) NOT NULL default '0',
  `toupiao` tinyint(1) NOT NULL default '0',
  `dingcan` tinyint(1) NOT NULL,
  `car` tinyint(1) NOT NULL,
  `yiliao` tinyint(1) NOT NULL,
  `jdbg` tinyint(1) NOT NULL,
  `ktv` tinyint(1) NOT NULL,
  `huisuo` tinyint(1) NOT NULL,
  `jiuba` tinyint(1) NOT NULL,
  PRIMARY KEY  (`token`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>