<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_areply`;");
E_C("CREATE TABLE `imicms_areply` (
  `id` int(11) NOT NULL auto_increment,
  `keyword` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `uname` varchar(90) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `token` char(30) NOT NULL,
  `home` varchar(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_areply` values('1','首页','','1','','1411453897','1413704668','99630ff411650cfa','');");
E_D("replace into `imicms_areply` values('3','首页','','6','','1411985428','1412408634','257e21c8a9e1be8c','1');");
E_D("replace into `imicms_areply` values('4','首页','','14','','1416378047','1416388454','c4448ac95e30a1eb','');");
E_D("replace into `imicms_areply` values('5','首页','','15','','1417959852','1423837995','2a94af5381fcc932','0');");
E_D("replace into `imicms_areply` values('6','首页','','17','','1441161579','1441179743','1c5990460d702b81','0');");

require("../../inc/footer.php");
?>