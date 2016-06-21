<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_imicmsemail`;");
E_C("CREATE TABLE `imicms_imicmsemail` (
  `token` varchar(60) NOT NULL,
  `type` tinyint(1) NOT NULL default '0',
  `smtpserver` varchar(40) NOT NULL default '',
  `port` varchar(40) NOT NULL default '',
  `name` varchar(60) NOT NULL default '',
  `password` varchar(60) NOT NULL default '',
  `receive` varchar(60) NOT NULL default '',
  `shangcheng` tinyint(1) NOT NULL default '0',
  `yuyue` tinyint(1) NOT NULL default '0',
  `baom` tinyint(1) NOT NULL default '0',
  `zxyy` tinyint(1) NOT NULL default '0',
  `toupiao` tinyint(1) NOT NULL default '0',
  `dingcan` tinyint(1) NOT NULL,
  `car` tinyint(1) NOT NULL,
  `yiliao` tinyint(1) NOT NULL,
  `jdbg` tinyint(1) NOT NULL,
  `beauty` tinyint(1) NOT NULL,
  `fitness` tinyint(1) NOT NULL,
  `gover` tinyint(1) NOT NULL,
  `zhaopin` tinyint(1) NOT NULL,
  `jianli` tinyint(1) NOT NULL,
  `fangchan` tinyint(1) NOT NULL,
  `food` tinyint(1) NOT NULL,
  `travel` tinyint(1) NOT NULL,
  `flower` tinyint(1) NOT NULL,
  `property` tinyint(1) NOT NULL,
  `bar` tinyint(1) NOT NULL,
  `fitment` tinyint(1) NOT NULL,
  `wedding` tinyint(1) NOT NULL,
  `affections` tinyint(1) NOT NULL,
  `housekeeper` tinyint(1) NOT NULL,
  `lease` tinyint(1) NOT NULL,
  PRIMARY KEY  (`token`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>