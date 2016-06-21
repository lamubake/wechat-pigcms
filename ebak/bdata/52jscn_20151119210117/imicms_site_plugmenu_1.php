<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_site_plugmenu`;");
E_C("CREATE TABLE `imicms_site_plugmenu` (
  `token` varchar(60) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `url` varchar(100) default '',
  `taxis` mediumint(4) NOT NULL default '0',
  `display` tinyint(1) NOT NULL default '0',
  KEY `token` (`token`,`taxis`,`display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','video','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','music','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','wechat','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','qqzone','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','tencentweibo','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','weibo','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','activity','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','membercard','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','shopping','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','email','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','album','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','home','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','share','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','message',NULL,'0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','nav','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','memberinfo','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','tel','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','recommend','','0','0');");
E_D("replace into `imicms_site_plugmenu` values('99630ff411650cfa','other','','0','0');");

require("../../inc/footer.php");
?>