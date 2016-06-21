<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wcard_zf`;");
E_C("CREATE TABLE `imicms_wcard_zf` (
  `id` int(11) NOT NULL auto_increment,
  `wecha_id` text NOT NULL,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `userName` text NOT NULL,
  `telephone` text NOT NULL,
  `count` text NOT NULL,
  `flag` tinyint(1) NOT NULL default '0',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_wcard_zf` values('1','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','99630ff411650cfa','1','尹可玺','18987133915','测试贺卡，不祝福都不行。','2','1415169161');");
E_D("replace into `imicms_wcard_zf` values('2','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','99630ff411650cfa','1','尹可玺','18987133915','12','1','1415169196');");

require("../../inc/footer.php");
?>