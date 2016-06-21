<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_school_set_index`;");
E_C("CREATE TABLE `imicms_school_set_index` (
  `setid` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `head_url` varchar(200) NOT NULL,
  `info` varchar(100) NOT NULL,
  `album_id` int(11) NOT NULL,
  `musicurl` varchar(200) NOT NULL default '',
  `menu1` varchar(50) NOT NULL,
  `menu1_id` int(11) NOT NULL,
  `menu2` char(15) NOT NULL,
  `menu3` char(15) NOT NULL,
  `menu4` char(15) NOT NULL,
  `menu5` char(15) NOT NULL,
  `menu6` char(15) NOT NULL,
  `menu7` char(15) NOT NULL,
  `menu8` char(15) NOT NULL,
  `menu2_id` int(11) NOT NULL,
  `menu3_id` int(11) NOT NULL,
  `menu4_id` int(11) NOT NULL,
  `menu5_id` int(11) NOT NULL,
  `menu6_id` int(11) NOT NULL,
  `menu9` varchar(50) NOT NULL default '',
  `menu10` varchar(50) NOT NULL default '',
  `path` varchar(3000) NOT NULL default '0',
  `tpid` int(11) default NULL,
  `conttpid` int(11) default NULL,
  `picurl1` varchar(200) NOT NULL default '',
  `picurl2` varchar(200) NOT NULL default '',
  `picurl3` varchar(200) NOT NULL default '',
  `picurl4` varchar(200) NOT NULL default '',
  `picurl5` varchar(200) NOT NULL default '',
  `picurl6` varchar(200) NOT NULL default '',
  `picurl7` varchar(200) NOT NULL default '',
  `picurl8` varchar(200) NOT NULL default '',
  `picurl9` varchar(200) NOT NULL default '',
  `picurl10` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`setid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>