<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_medical_set`;");
E_C("CREATE TABLE `imicms_medical_set` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `keyword` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `token` varchar(50) NOT NULL default '',
  `head_url` varchar(200) NOT NULL default '',
  `album_id` int(11) default NULL,
  `menu1` varchar(20) NOT NULL default '',
  `menu2` varchar(20) NOT NULL default '',
  `menu3` varchar(20) NOT NULL default '',
  `menu4` varchar(20) NOT NULL default '',
  `menu5` varchar(20) NOT NULL default '',
  `menu6` varchar(20) NOT NULL default '',
  `menu7` varchar(20) NOT NULL default '',
  `menu8` varchar(20) NOT NULL default '',
  `hotfocus_id` int(11) NOT NULL,
  `experts_id` int(11) NOT NULL,
  `ceem_id` int(11) NOT NULL,
  `Rcase_id` int(11) NOT NULL,
  `technology_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `evants_id` int(11) NOT NULL,
  `video` text NOT NULL,
  `symptoms_id` int(11) NOT NULL,
  `info` char(200) NOT NULL default '',
  `path` varchar(3000) default '0',
  `tpid` int(11) default NULL,
  `conttpid` int(11) default NULL,
  `menu9` varchar(50) default '',
  `menu10` varchar(50) default '',
  `picurl1` varchar(200) default '',
  `picurl2` varchar(200) default '',
  `picurl3` varchar(200) default '',
  `picurl4` varchar(200) default '',
  `picurl5` varchar(200) default '',
  `picurl6` varchar(200) default '',
  `picurl7` varchar(200) default '',
  `picurl8` varchar(200) default '',
  `picurl9` varchar(200) default '',
  `picurl10` varchar(200) default '',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>