<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sign_in`;");
E_C("CREATE TABLE `imicms_sign_in` (
  `sign_id` int(10) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `wecha_id` char(60) NOT NULL,
  `user_name` char(50) NOT NULL,
  `integral` tinyint(4) NOT NULL,
  `time` char(11) NOT NULL,
  `continue` tinyint(2) NOT NULL,
  `phone` char(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `sid` int(10) NOT NULL,
  `del` int(1) NOT NULL,
  PRIMARY KEY  (`sign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>