<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dining_table`;");
E_C("CREATE TABLE `imicms_dining_table` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `image` varchar(200) NOT NULL,
  `isbox` tinyint(1) unsigned NOT NULL,
  `isorder` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `catid` int(8) NOT NULL COMMENT '店铺id',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `isbox` (`isbox`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_dining_table` values('1','1','1号桌','12','','0','0','0','0');");
E_D("replace into `imicms_dining_table` values('2','1','2号桌','0','','0','0','0','0');");
E_D("replace into `imicms_dining_table` values('3','1','3号桌','0','','0','0','0','0');");
E_D("replace into `imicms_dining_table` values('4','1','4号桌','0','','0','0','0','0');");
E_D("replace into `imicms_dining_table` values('5','1','5号桌','0','','1','0','0','0');");

require("../../inc/footer.php");
?>