<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_diymen_set`;");
E_C("CREATE TABLE `imicms_diymen_set` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `appid` varchar(18) NOT NULL,
  `appsecret` varchar(32) NOT NULL,
  `yappid` varchar(32) NOT NULL,
  `yappsecret` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_diymen_set` values('1','257e21c8a9e1be8c','wxabe07624b4c3afa3','b575cc3e180e28e07643386eec9f07b4','','');");
E_D("replace into `imicms_diymen_set` values('2','99630ff411650cfa','wxbd7cf9b1d6970a54','b40932be7ca4762cb9e45ec7dde16c7b','','');");
E_D("replace into `imicms_diymen_set` values('3','kaiqpo1447853601','52jscn','52jscn','','');");

require("../../inc/footer.php");
?>