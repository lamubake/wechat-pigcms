<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_live_company`;");
E_C("CREATE TABLE `imicms_live_company` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(25) NOT NULL,
  `name` char(50) NOT NULL,
  `bg_pic` char(120) NOT NULL,
  `top_pic` char(120) NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `live_id` int(10) unsigned NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `show_map` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>