<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_adkanjia`;");
E_C("CREATE TABLE `imicms_adkanjia` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `info` text NOT NULL,
  `reply_pic` varchar(250) NOT NULL,
  `top_pic` varchar(250) NOT NULL,
  `bgcolor` char(100) NOT NULL,
  `start` char(15) NOT NULL,
  `end` char(15) NOT NULL,
  `add_time` char(15) NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `okprice` decimal(9,2) NOT NULL,
  `adurl` varchar(255) NOT NULL,
  `ad_pic` varchar(255) NOT NULL,
  `xq_pic` varchar(255) NOT NULL,
  `shownum` int(10) NOT NULL,
  `clicknum` int(10) NOT NULL,
  `is_open` tinyint(4) NOT NULL,
  `is_reg` tinyint(4) NOT NULL,
  `is_attention` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>