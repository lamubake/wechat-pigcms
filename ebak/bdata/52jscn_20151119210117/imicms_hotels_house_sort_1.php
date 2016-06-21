<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hotels_house_sort`;");
E_C("CREATE TABLE `imicms_hotels_house_sort` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `price` float NOT NULL,
  `vprice` float NOT NULL,
  `note` varchar(500) NOT NULL,
  `num` tinyint(1) unsigned NOT NULL,
  `houses` smallint(3) unsigned NOT NULL,
  `area` float NOT NULL,
  `bed` varchar(100) NOT NULL,
  `floor` varchar(100) NOT NULL,
  `bedwidth` varchar(100) NOT NULL,
  `network` varchar(100) NOT NULL,
  `smoke` varchar(100) NOT NULL,
  `image_1` varchar(200) NOT NULL,
  `image_2` varchar(200) NOT NULL,
  `image_3` varchar(200) NOT NULL,
  `image_4` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_hotels_house_sort` values('1','1','99630ff411650cfa','亚蓝--微酒店展示','','500','450','本店就是为了展示效果使用不做为入驻使用。','255','1','0','','','','','','','','','');");

require("../../inc/footer.php");
?>