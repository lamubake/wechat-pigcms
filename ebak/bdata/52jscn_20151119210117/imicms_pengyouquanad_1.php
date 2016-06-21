<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pengyouquanad`;");
E_C("CREATE TABLE `imicms_pengyouquanad` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(40) NOT NULL,
  `title` char(40) NOT NULL,
  `keyword` char(20) NOT NULL,
  `intro` varchar(250) NOT NULL,
  `reply_pic` varchar(250) NOT NULL,
  `adtitle` varchar(250) NOT NULL,
  `adurl` varchar(255) NOT NULL,
  `ad_pic` varchar(255) NOT NULL,
  `adinfo` varchar(255) NOT NULL,
  `adimg` varchar(255) NOT NULL,
  `gongzhonghao` char(100) NOT NULL,
  `gzhid` char(100) NOT NULL,
  `gzh_ewm` varchar(255) NOT NULL,
  `stats` int(2) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>