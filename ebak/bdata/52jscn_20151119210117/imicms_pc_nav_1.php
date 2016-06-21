<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_nav`;");
E_C("CREATE TABLE `imicms_pc_nav` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `s_name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `key` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_pc_nav` values('57','0','kaiqpo1447853601','公司介绍','Contact Us','/page/about.html','about','0');");
E_D("replace into `imicms_pc_nav` values('58','0','kaiqpo1447853601','产品中心','Products','/productcat/all.html','allproduct','0');");
E_D("replace into `imicms_pc_nav` values('59','0','kaiqpo1447853601','网站新闻','News','/newscat/all.html','allnews','0');");
E_D("replace into `imicms_pc_nav` values('60','0','kaiqpo1447853601','人才招聘','Jobs','/page/jobs.html','jobs','0');");
E_D("replace into `imicms_pc_nav` values('61','0','kaiqpo1447853601','网站留言','Books','/books.html','books','0');");
E_D("replace into `imicms_pc_nav` values('62','0','kaiqpo1447853601','友情链接','Friend Links','/page/links.html','links','0');");
E_D("replace into `imicms_pc_nav` values('63','0','kaiqpo1447853601','下载专区','Down','/downcat/all.html','alldown','0');");
E_D("replace into `imicms_pc_nav` values('64','0','kaiqpo1447853601','联系我们','Contact Us','/page/contact.html','contact','0');");

require("../../inc/footer.php");
?>