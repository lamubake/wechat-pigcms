<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_funclass`;");
E_C("CREATE TABLE `imicms_funclass` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `counts` int(11) NOT NULL,
  `menu_icon` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_funclass` values('1','行业应用','0','');");
E_D("replace into `imicms_funclass` values('2','基础功能','0','');");
E_D("replace into `imicms_funclass` values('3','微网站','0','');");
E_D("replace into `imicms_funclass` values('4','互动功能','0','');");
E_D("replace into `imicms_funclass` values('5','二次开发','0','');");
E_D("replace into `imicms_funclass` values('6','微现场','0','');");
E_D("replace into `imicms_funclass` values('7','电商系统','0','');");
E_D("replace into `imicms_funclass` values('8','粉丝CRM','0','');");
E_D("replace into `imicms_funclass` values('9','微硬件','0','');");
E_D("replace into `imicms_funclass` values('10','微渠道','0','');");
E_D("replace into `imicms_funclass` values('11','客服系统','0','');");
E_D("replace into `imicms_funclass` values('12','会员卡','0','');");
E_D("replace into `imicms_funclass` values('13','活动游戏','0','');");
E_D("replace into `imicms_funclass` values('14','数据魔方','0','');");

require("../../inc/footer.php");
?>