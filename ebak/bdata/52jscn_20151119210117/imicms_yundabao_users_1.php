<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_yundabao_users`;");
E_C("CREATE TABLE `imicms_yundabao_users` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `endtime` int(11) NOT NULL,
  `AccessToken` varchar(200) default NULL,
  `UserId` int(11) default NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>