<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_share_set`;");
E_C("CREATE TABLE `imicms_share_set` (
  `token` varchar(40) NOT NULL default '',
  `score` int(5) NOT NULL default '0',
  `daylimit` int(5) NOT NULL default '1',
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>