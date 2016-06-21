<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_custom_limit`;");
E_C("CREATE TABLE `imicms_custom_limit` (
  `limit_id` int(10) unsigned NOT NULL auto_increment,
  `enddate` char(10) NOT NULL,
  `sub_total` smallint(6) NOT NULL,
  `today_total` smallint(6) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY  (`limit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_custom_limit` values('2','','0','0','0');");
E_D("replace into `imicms_custom_limit` values('3','','0','0','0');");
E_D("replace into `imicms_custom_limit` values('4','','0','0','0');");
E_D("replace into `imicms_custom_limit` values('5','','0','0','0');");
E_D("replace into `imicms_custom_limit` values('6','','0','0','0');");

require("../../inc/footer.php");
?>