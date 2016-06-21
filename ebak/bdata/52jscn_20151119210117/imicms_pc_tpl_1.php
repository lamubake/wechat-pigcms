<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_tpl`;");
E_C("CREATE TABLE `imicms_pc_tpl` (
  `id` int(11) NOT NULL auto_increment,
  `tpl_name` varchar(50) NOT NULL default '',
  `tpl_sort` int(11) NOT NULL,
  `tpl_file` varchar(100) NOT NULL default '',
  `token` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_pc_tpl` values('1','绿茶模版','1','green_tea','cdzeck1433248466');");
E_D("replace into `imicms_pc_tpl` values('2','白色模版','2','white_electron','cdzeck1433248466');");
E_D("replace into `imicms_pc_tpl` values('3','家纺绿色','3','green_textile','cdzeck1433248466');");
E_D("replace into `imicms_pc_tpl` values('4','粉红色糖果甜品','4','pink_sweet','');");
E_D("replace into `imicms_pc_tpl` values('5','红色食品','5','red_food','');");
E_D("replace into `imicms_pc_tpl` values('6','唯品葡萄酿酒','6','red_wine','');");

require("../../inc/footer.php");
?>