<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_moopha_channel`;");
E_C("CREATE TABLE `imicms_moopha_channel` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `shortname` varchar(50) NOT NULL default '',
  `isnav` tinyint(1) NOT NULL default '1',
  `channeltype` tinyint(1) NOT NULL default '1',
  `cindex` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL default '',
  `link` varchar(200) NOT NULL,
  `externallink` tinyint(1) NOT NULL default '0',
  `des` mediumtext NOT NULL,
  `thumb` varchar(100) default NULL,
  `metatitle` varchar(100) default NULL,
  `metakeyword` varchar(100) default NULL,
  `metades` varchar(200) default NULL,
  `thumbwidth` int(4) NOT NULL,
  `thumbheight` int(4) NOT NULL,
  `thumb2width` mediumint(4) NOT NULL default '0',
  `thumb2height` mediumint(4) NOT NULL default '0',
  `thumb3width` mediumint(4) NOT NULL default '0',
  `thumb3height` mediumint(4) NOT NULL default '0',
  `thumb4width` mediumint(4) NOT NULL default '0',
  `thumb4height` mediumint(4) NOT NULL default '0',
  `parentid` int(10) NOT NULL default '0',
  `channeltemplate` int(10) default '1',
  `contenttemplate` int(10) default '1',
  `autotype` varchar(10) NOT NULL default '',
  `ex` tinyint(1) NOT NULL default '0',
  `iscity` tinyint(1) NOT NULL default '0',
  `site` int(4) NOT NULL default '0',
  `taxis` int(10) NOT NULL default '0',
  `lastcreate` int(10) NOT NULL default '1400000000',
  `pagesize` smallint(3) NOT NULL default '30',
  `specialid` mediumint(4) NOT NULL default '0',
  `homepicturechannel` tinyint(1) NOT NULL default '0',
  `hometextchannel` tinyint(1) NOT NULL default '0',
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parentid` (`parentid`),
  KEY `site` (`site`),
  KEY `taxis` (`taxis`),
  KEY `time` (`time`),
  KEY `specialid` (`specialid`),
  KEY `token` (`token`),
  KEY `isnav` (`isnav`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>