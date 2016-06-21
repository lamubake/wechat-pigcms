<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_moopha_article`;");
E_C("CREATE TABLE `imicms_moopha_article` (
  `id` int(10) NOT NULL auto_increment,
  `channel_id` int(10) NOT NULL,
  `token` varchar(50) NOT NULL,
  `site` int(4) NOT NULL default '1',
  `title` varchar(200) NOT NULL,
  `titles` varchar(400) NOT NULL default '',
  `subtitle` varchar(200) default NULL,
  `link` varchar(200) default NULL,
  `externallink` tinyint(1) NOT NULL default '0',
  `thumb` varchar(100) default NULL,
  `content` longtext,
  `intro` varchar(2000) NOT NULL,
  `author` varchar(20) default NULL,
  `source` varchar(100) default NULL,
  `keywords` varchar(300) default NULL,
  `uid` varchar(10) NOT NULL default '0',
  `time` int(10) NOT NULL,
  `last_update` int(10) NOT NULL,
  `viewcount` int(10) NOT NULL default '0',
  `template` varchar(50) default NULL,
  `pagecount` tinyint(2) NOT NULL default '1',
  `disagree` int(10) NOT NULL default '0',
  `cancomment` tinyint(1) NOT NULL default '1',
  `commentcount` int(10) NOT NULL default '0',
  `agree` int(10) NOT NULL default '0',
  `taxis` int(10) NOT NULL default '0',
  `lastcreate` int(10) NOT NULL default '1400000000',
  `sourcetype` smallint(2) NOT NULL default '0',
  `ex` tinyint(1) default '0',
  `pubed` tinyint(1) NOT NULL default '1',
  `geoid` mediumint(6) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `channel_id_2` (`channel_id`,`thumb`),
  KEY `time` (`time`),
  KEY `taxis` (`taxis`),
  KEY `ex` (`ex`),
  KEY `geoid` (`geoid`),
  KEY `uid` (`uid`),
  KEY `keywords` (`keywords`),
  KEY `sourcetype` (`sourcetype`),
  KEY `pubed` (`pubed`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>