<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_vote`;");
E_C("CREATE TABLE `imicms_vote` (
  `id` bigint(11) unsigned NOT NULL auto_increment,
  `type` char(5) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `vpicurl` varchar(255) NOT NULL,
  `pic_show` smallint(1) unsigned NOT NULL default '0',
  `select_num` int(4) unsigned NOT NULL default '0',
  `click` int(10) unsigned NOT NULL default '0',
  `createtime` int(11) unsigned NOT NULL default '0',
  `statdate` int(11) unsigned NOT NULL default '0',
  `enddate` int(11) unsigned NOT NULL default '0',
  `token` varchar(255) NOT NULL default '',
  `updatetime` int(11) unsigned NOT NULL default '0',
  `instructions` text NOT NULL,
  `is_radio` smallint(1) NOT NULL default '1',
  `result` smallint(1) NOT NULL default '1',
  `qstime` varchar(100) NOT NULL,
  `status` smallint(1) NOT NULL default '0',
  `showpic` tinyint(4) NOT NULL,
  `info` varchar(500) NOT NULL,
  `display` tinyint(4) NOT NULL,
  `cknums` tinyint(3) NOT NULL default '1',
  `picurl` varchar(500) NOT NULL,
  `count` int(11) NOT NULL default '0',
  `refresh` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `keyword` (`keyword`),
  FULLTEXT KEY `token` (`token`),
  FULLTEXT KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>