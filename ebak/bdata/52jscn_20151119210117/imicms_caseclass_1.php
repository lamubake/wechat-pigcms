<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_caseclass`;");
E_C("CREATE TABLE `imicms_caseclass` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `counts` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_caseclass` values('1','餐饮美食','0');");
E_D("replace into `imicms_caseclass` values('2','酒店/旅游','0');");
E_D("replace into `imicms_caseclass` values('3','健身/美容','0');");
E_D("replace into `imicms_caseclass` values('4','广告/媒体','0');");
E_D("replace into `imicms_caseclass` values('5','娱乐行业','0');");

require("../../inc/footer.php");
?>