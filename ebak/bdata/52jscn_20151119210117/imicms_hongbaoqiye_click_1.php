<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbaoqiye_click`;");
E_C("CREATE TABLE `imicms_hongbaoqiye_click` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(255) NOT NULL,
  `wecha_id` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `pid` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>