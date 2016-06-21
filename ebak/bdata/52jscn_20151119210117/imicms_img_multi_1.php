<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_img_multi`;");
E_C("CREATE TABLE `imicms_img_multi` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `keywords` varchar(100) default '',
  `imgs` varchar(100) default '',
  `token` char(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_img_multi` values('1','家政','9,9','257e21c8a9e1be8c');");
E_D("replace into `imicms_img_multi` values('2','保洁','6','257e21c8a9e1be8c');");
E_D("replace into `imicms_img_multi` values('3','开荒','11','257e21c8a9e1be8c');");
E_D("replace into `imicms_img_multi` values('4','景观','11','257e21c8a9e1be8c');");
E_D("replace into `imicms_img_multi` values('5','知识','20','257e21c8a9e1be8c');");
E_D("replace into `imicms_img_multi` values('6','产品','2','99630ff411650cfa');");
E_D("replace into `imicms_img_multi` values('7','功能','4','99630ff411650cfa');");
E_D("replace into `imicms_img_multi` values('8','微博','71','99630ff411650cfa');");
E_D("replace into `imicms_img_multi` values('9','新媒体','170,170','99630ff411650cfa');");
E_D("replace into `imicms_img_multi` values('10','其他','171','99630ff411650cfa');");
E_D("replace into `imicms_img_multi` values('11','热文','240','99630ff411650cfa');");

require("../../inc/footer.php");
?>