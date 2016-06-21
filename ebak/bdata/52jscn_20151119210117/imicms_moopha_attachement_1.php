<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_moopha_attachement`;");
E_C("CREATE TABLE `imicms_moopha_attachement` (
  `url` varchar(150) NOT NULL default '',
  `pubid` smallint(3) NOT NULL default '1',
  `filetype` varchar(10) NOT NULL default 'picture',
  `cat` varchar(20) NOT NULL default '',
  `catid` int(10) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  KEY `cat` (`cat`,`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>