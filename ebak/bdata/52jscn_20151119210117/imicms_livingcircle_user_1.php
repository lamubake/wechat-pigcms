<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_livingcircle_user`;");
E_C("CREATE TABLE `imicms_livingcircle_user` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `wecha_id` varchar(100) NOT NULL,
  `name` varchar(100) default NULL,
  `address` varchar(200) default NULL,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>