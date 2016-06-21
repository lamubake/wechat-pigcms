<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_page`;");
E_C("CREATE TABLE `imicms_pc_page` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `s_title` varchar(100) NOT NULL,
  `key` varchar(50) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_pc_page` values('29','kaiqpo1447853601','关于我们','About Us','about','','','关于我们');");
E_D("replace into `imicms_pc_page` values('30','kaiqpo1447853601','人才招聘','Jobs','jobs','','','人才招聘');");
E_D("replace into `imicms_pc_page` values('31','kaiqpo1447853601','友情链接','Friend Links','links','','','友情链接');");
E_D("replace into `imicms_pc_page` values('32','kaiqpo1447853601','联系我们','Contact Us','contact','','','联系我们');");

require("../../inc/footer.php");
?>