<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cointree_prize`;");
E_C("CREATE TABLE `imicms_cointree_prize` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `first_prize` varchar(50) NOT NULL default '',
  `first_img` varchar(255) NOT NULL default '',
  `first_nums` int(11) NOT NULL,
  `second_prize` varchar(50) NOT NULL default '',
  `second_img` varchar(255) NOT NULL default '',
  `second_nums` int(11) NOT NULL,
  `third_prize` varchar(50) NOT NULL default '',
  `third_img` varchar(255) NOT NULL default '',
  `third_nums` int(11) NOT NULL,
  `fourth_prize` varchar(50) NOT NULL default '',
  `fourth_img` varchar(255) NOT NULL default '',
  `fourth_nums` int(11) NOT NULL,
  `fifth_prize` varchar(50) NOT NULL default '',
  `fifth_img` varchar(255) NOT NULL default '',
  `fifth_nums` int(11) NOT NULL,
  `sixth_prize` varchar(50) NOT NULL default '',
  `sixth_img` varchar(255) NOT NULL default '',
  `sixth_nums` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>