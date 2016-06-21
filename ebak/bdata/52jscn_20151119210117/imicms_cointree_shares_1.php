<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cointree_shares`;");
E_C("CREATE TABLE `imicms_cointree_shares` (
  `id` int(11) NOT NULL auto_increment,
  `share_wechaid` varchar(50) NOT NULL default '',
  `share_wechaname` varchar(50) NOT NULL default '',
  `share_wechapic` varchar(255) NOT NULL default '',
  `share_key` varchar(100) NOT NULL default '',
  `addcoins` varchar(15) NOT NULL,
  `opentime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>