<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_secakecoupon`;");
E_C("CREATE TABLE `imicms_secakecoupon` (
  `coupon_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` varchar(50) NOT NULL default '0',
  `coupon_name` varchar(100) NOT NULL default '',
  `coupon_value` varchar(100) NOT NULL default '0.00',
  `start_time` int(15) unsigned NOT NULL default '0',
  `if_issue` tinyint(3) unsigned NOT NULL default '0',
  `pro_id` int(10) default NULL,
  `x_id` int(10) default NULL,
  PRIMARY KEY  (`coupon_id`),
  KEY `store_id` USING BTREE (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>