<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_market_cate`;");
E_C("CREATE TABLE `imicms_market_cate` (
  `cate_id` int(10) unsigned NOT NULL auto_increment,
  `cate_name` char(35) NOT NULL,
  `cate_pic` char(100) NOT NULL,
  `cate_intro` varchar(200) NOT NULL,
  `cate_pid` int(11) NOT NULL,
  `path_info` varchar(255) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `market_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY  (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>