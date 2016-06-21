<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_donate`;");
E_C("CREATE TABLE `imicms_member_card_donate` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(45) NOT NULL,
  `cardid` int(11) NOT NULL,
  `min_price` decimal(10,2) NOT NULL,
  `max_price` decimal(10,2) NOT NULL,
  `donate_price` decimal(10,2) NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED");

require("../../inc/footer.php");
?>