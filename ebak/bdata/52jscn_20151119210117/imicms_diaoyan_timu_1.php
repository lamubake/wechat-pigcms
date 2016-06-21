<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_diaoyan_timu`;");
E_C("CREATE TABLE `imicms_diaoyan_timu` (
  `tid` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `optia` varchar(200) default NULL,
  `optib` varchar(200) default NULL,
  `optic` varchar(200) default NULL,
  `optid` varchar(200) default NULL,
  `optie` varchar(200) default NULL,
  `max` smallint(2) default NULL,
  `pid` int(11) NOT NULL,
  `perca` int(11) default '0',
  `percb` int(11) default '0',
  `percc` int(11) default '0',
  `percd` int(11) default '0',
  `perce` int(11) default '0',
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>