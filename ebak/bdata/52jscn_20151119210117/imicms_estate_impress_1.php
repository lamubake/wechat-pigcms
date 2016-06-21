<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_estate_impress`;");
E_C("CREATE TABLE `imicms_estate_impress` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(30) NOT NULL default '',
  `pid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `is_show` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_estate_impress` values('1','99630ff411650cfa','1','很好，很不错','1','0','1');");

require("../../inc/footer.php");
?>