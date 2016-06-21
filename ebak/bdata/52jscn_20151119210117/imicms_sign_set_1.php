<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_sign_set`;");
E_C("CREATE TABLE `imicms_sign_set` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `keywords` char(25) NOT NULL,
  `title` char(60) NOT NULL,
  `content` varchar(250) NOT NULL,
  `token` char(35) NOT NULL,
  `reply_img` char(150) NOT NULL,
  `top_pic` char(150) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `range` varchar(20) NOT NULL,
  `signpass` char(16) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `if` char(5) NOT NULL,
  `hour` char(5) NOT NULL,
  `minute` char(5) NOT NULL,
  `Cycle` char(5) NOT NULL,
  `day` varchar(10) NOT NULL,
  `site` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>