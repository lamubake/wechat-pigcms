<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_yundabao`;");
E_C("CREATE TABLE `imicms_yundabao` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `AppId` int(11) default NULL,
  `endtime` int(11) default NULL,
  `AppName` varchar(100) default NULL,
  `AppNote` text,
  `HideTop` int(11) default NULL,
  `IconType` int(11) default NULL,
  `IconName` varchar(200) default NULL,
  `IconName_url` varchar(200) default NULL,
  `LogoName` varchar(100) default NULL,
  `LogoName_url` varchar(200) default NULL,
  `BgColor` int(11) default NULL,
  `SplashType` int(11) default NULL,
  `SplashName` varchar(200) default NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>