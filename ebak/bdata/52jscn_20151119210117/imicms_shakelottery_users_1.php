<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shakelottery_users`;");
E_C("CREATE TABLE `imicms_shakelottery_users` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL,
  `total_shakes` int(11) NOT NULL,
  `today_shakes` int(11) NOT NULL,
  `wecha_id` varchar(50) NOT NULL default '',
  `wecha_name` varchar(50) NOT NULL default '',
  `wecha_pic` varchar(255) NOT NULL default '',
  `phone` varchar(15) NOT NULL default '',
  `addtime` int(11) NOT NULL,
  `token` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>