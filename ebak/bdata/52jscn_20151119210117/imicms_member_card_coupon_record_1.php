<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_coupon_record`;");
E_C("CREATE TABLE `imicms_member_card_coupon_record` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(25) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `coupon_type` enum('1','2','3') NOT NULL,
  `is_use` enum('0','1') NOT NULL,
  `cardid` int(11) NOT NULL,
  `add_time` char(11) NOT NULL,
  `use_time` char(11) NOT NULL,
  `coupon_attr` text NOT NULL,
  `card_id` char(45) NOT NULL,
  `cancel_code` char(15) NOT NULL,
  `company_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `whereid` int(11) NOT NULL default '0',
  `iswhere` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>