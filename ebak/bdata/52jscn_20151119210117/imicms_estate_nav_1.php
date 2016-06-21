<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_estate_nav`;");
E_C("CREATE TABLE `imicms_estate_nav` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` char(35) NOT NULL,
  `pic` char(100) NOT NULL,
  `url` varchar(120) NOT NULL,
  `is_show` enum('0','1') NOT NULL,
  `is_system` enum('0','1') NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_estate_nav` values('1','楼盘首页','./tpl/User/default/common/images/photo/plugmenu6.png','{siteUrl}/index.php?g=Wap&m=Estate&a=index&token=99630ff411650cfa&wecha_id={wecha_id}&id={id}','1','1','100','1','99630ff411650cfa');");
E_D("replace into `imicms_estate_nav` values('2','楼盘简介','./tpl/User/default/common/images/photo/plugmenu4.png','{siteUrl}/index.php?g=Wap&m=Estate&a=introduce&token=99630ff411650cfa&wecha_id={wecha_id}&id={id}','1','1','99','1','99630ff411650cfa');");
E_D("replace into `imicms_estate_nav` values('3','楼盘相册','./tpl/User/default/common/images/photo/plugmenu7.png','{siteUrl}/index.php?g=Wap&m=Estate&a=photo&token=99630ff411650cfa&wecha_id={wecha_id}&id={id}','1','1','98','1','99630ff411650cfa');");
E_D("replace into `imicms_estate_nav` values('4','户型全景','./tpl/User/default/common/images/photo/plugmenu17.png','{siteUrl}/index.php?g=Wap&m=Estate&a=housetype&token=99630ff411650cfa&wecha_id={wecha_id}&id={id}','1','1','97','1','99630ff411650cfa');");
E_D("replace into `imicms_estate_nav` values('5','印象点评','./tpl/User/default/common/images/photo/plugmenu15.png','{siteUrl}/index.php?g=Wap&m=Estate&a=impress&token=99630ff411650cfa&wecha_id={wecha_id}&id={id}','1','1','96','1','99630ff411650cfa');");
E_D("replace into `imicms_estate_nav` values('6','预约看房','./tpl/User/default/common/images/photo/plugmenu8.png','{siteUrl}/index.php?g=Wap&m=Reservation&a=index&token=99630ff411650cfa&wecha_id={wecha_id}&id={id}&rid={rid}','1','1','95','1','99630ff411650cfa');");
E_D("replace into `imicms_estate_nav` values('7','关于我们','./tpl/User/default/common/images/photo/plugmenu19.png','{siteUrl}/index.php?g=Wap&m=Estate&a=aboutus&token=99630ff411650cfa&wecha_id={wecha_id}&id={id}','1','1','94','1','99630ff411650cfa');");

require("../../inc/footer.php");
?>