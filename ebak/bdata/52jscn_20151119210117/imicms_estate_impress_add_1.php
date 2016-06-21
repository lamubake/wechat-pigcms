<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_estate_impress_add`;");
E_C("CREATE TABLE `imicms_estate_impress_add` (
  `id` int(11) NOT NULL auto_increment,
  `imp_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `imp_user` char(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_estate_impress_add` values('1','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','1','很是喜欢');");

require("../../inc/footer.php");
?>