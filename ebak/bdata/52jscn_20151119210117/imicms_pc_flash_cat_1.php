<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_flash_cat`;");
E_C("CREATE TABLE `imicms_pc_flash_cat` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(50) NOT NULL,
  `cat_key` varchar(50) NOT NULL COMMENT '分类Key,使用Key调用轮播图',
  `token` varchar(50) NOT NULL,
  PRIMARY KEY  (`cat_id`),
  KEY `cat_key` (`cat_key`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='轮播图分类'");

require("../../inc/footer.php");
?>