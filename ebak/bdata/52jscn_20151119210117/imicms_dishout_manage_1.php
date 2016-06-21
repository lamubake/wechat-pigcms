<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('latin1');
E_D("DROP TABLE IF EXISTS `imicms_dishout_manage`;");
E_C("CREATE TABLE `imicms_dishout_manage` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL COMMENT 'company?id',
  `token` varchar(255) NOT NULL,
  `zc_sdate` int(10) unsigned NOT NULL default '0',
  `zc_edate` int(10) unsigned NOT NULL default '0',
  `wc_sdate` int(10) unsigned NOT NULL default '0',
  `wc_edate` int(10) unsigned NOT NULL default '0',
  `permin` tinyint(3) unsigned NOT NULL default '15',
  `removing` tinyint(3) unsigned NOT NULL default '0' COMMENT '????',
  `area` varchar(255) character set utf8 NOT NULL COMMENT '????',
  `sendtime` tinyint(3) NOT NULL COMMENT '????(?)',
  `shopimg` text character set utf8 NOT NULL COMMENT '??????',
  `stype` tinyint(1) unsigned NOT NULL default '0' COMMENT '??????',
  `pricing` int(10) unsigned NOT NULL default '0' COMMENT '????????',
  `keyword` varchar(255) character set utf8 NOT NULL COMMENT '???',
  `rtitle` varchar(200) character set utf8 NOT NULL COMMENT '????',
  `rinfo` varchar(255) character set utf8 NOT NULL COMMENT '????',
  `picurl` varchar(255) character set utf8 NOT NULL COMMENT '??????',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");

require("../../inc/footer.php");
?>