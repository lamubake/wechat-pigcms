<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_customs`;");
E_C("CREATE TABLE `imicms_customs` (
  `id` int(11) NOT NULL auto_increment,
  `agentid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` char(80) NOT NULL,
  `url` char(160) NOT NULL,
  `open` int(11) NOT NULL,
  `dspl` int(11) NOT NULL,
  `style` int(11) NOT NULL,
  `fc` char(150) NOT NULL,
  `price` char(150) NOT NULL,
  `about` char(150) NOT NULL,
  `common` char(150) NOT NULL,
  `login` char(150) NOT NULL,
  `help` char(150) NOT NULL,
  `fcname` varchar(80) NOT NULL,
  `pricename` varchar(80) NOT NULL,
  `loginname` varchar(80) NOT NULL,
  `helpname` varchar(80) NOT NULL,
  `aboutname` varchar(80) NOT NULL,
  `commonname` varchar(80) NOT NULL,
  `fc_open` int(11) NOT NULL default '0',
  `about_open` int(11) NOT NULL default '0',
  `common_open` int(11) NOT NULL default '0',
  `help_open` int(11) NOT NULL default '0',
  `login_open` int(11) NOT NULL default '0',
  `price_open` int(11) NOT NULL default '0',
  `fc_dspl` int(11) NOT NULL default '0',
  `common_dspl` int(11) NOT NULL default '0',
  `about_dspl` int(11) NOT NULL default '0',
  `login_dspl` int(11) NOT NULL default '0',
  `price_dspl` int(11) NOT NULL default '0',
  `help_dspl` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `IDX_TYPE` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>