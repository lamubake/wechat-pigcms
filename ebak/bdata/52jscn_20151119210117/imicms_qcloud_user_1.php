<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_qcloud_user`;");
E_C("CREATE TABLE `imicms_qcloud_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `openid` char(100) NOT NULL,
  `token` char(255) NOT NULL,
  `mpname` char(60) NOT NULL,
  `mporiginalid` char(60) NOT NULL,
  `mpid` char(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>