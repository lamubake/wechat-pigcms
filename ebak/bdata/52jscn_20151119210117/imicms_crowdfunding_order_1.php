<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_crowdfunding_order`;");
E_C("CREATE TABLE `imicms_crowdfunding_order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `order_name` char(50) NOT NULL,
  `orderid` char(50) NOT NULL,
  `token` char(40) NOT NULL,
  `pid` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `address` varchar(300) NOT NULL,
  `add_time` char(15) NOT NULL,
  `pay_time` char(15) NOT NULL,
  `price` double(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `username` char(20) NOT NULL,
  `tel` char(20) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `paytype` varchar(50) NOT NULL,
  `paid` tinyint(4) NOT NULL,
  `third_id` varchar(100) NOT NULL,
  `is_delete` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>