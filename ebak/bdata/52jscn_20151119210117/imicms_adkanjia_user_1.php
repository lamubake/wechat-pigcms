<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_adkanjia_user`;");
E_C("CREATE TABLE `imicms_adkanjia_user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `nums` int(11) NOT NULL,
  `add_time` char(15) NOT NULL,
  `share_key` char(40) NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `nums_counts` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tel` varchar(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>