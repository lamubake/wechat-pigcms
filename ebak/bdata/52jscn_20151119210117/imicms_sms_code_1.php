<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sms_code`;");
E_C("CREATE TABLE `imicms_sms_code` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `code` char(40) NOT NULL,
  `token` char(30) NOT NULL,
  `wecha_id` char(45) NOT NULL,
  `action` char(100) NOT NULL,
  `create_time` char(11) NOT NULL,
  `is_use` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>