<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cosmetology`;");
E_C("CREATE TABLE `imicms_cosmetology` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(31) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `piccover` varchar(500) NOT NULL,
  `registrationtoppic` varchar(500) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `lng` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sms` varchar(100) NOT NULL,
  `open_email` varchar(50) NOT NULL,
  `open_sms` varchar(50) NOT NULL,
  `checked` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>