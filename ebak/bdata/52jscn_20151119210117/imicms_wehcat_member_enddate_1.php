<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wehcat_member_enddate`;");
E_C("CREATE TABLE `imicms_wehcat_member_enddate` (
  `id` int(11) NOT NULL auto_increment,
  `openid` varchar(60) NOT NULL,
  `enddate` int(11) NOT NULL,
  `joinUpDate` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `token` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `openid` USING BTREE (`openid`),
  KEY `openid_2` USING BTREE (`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_wehcat_member_enddate` values('1','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','1415193590','0','0','99630ff411650cfa');");
E_D("replace into `imicms_wehcat_member_enddate` values('2','oQqMOt_BsAHx6kGaCUhj3_xd5904','1415304652','0','0','99630ff411650cfa');");
E_D("replace into `imicms_wehcat_member_enddate` values('3','oQqMOt85Ie2SIJ_qXS-qGmBt8OQs','1411959331','0','0','99630ff411650cfa');");
E_D("replace into `imicms_wehcat_member_enddate` values('4','oupbZjof1M0-rpqKcJcylfXzKGG8','1413468507','0','0','257e21c8a9e1be8c');");
E_D("replace into `imicms_wehcat_member_enddate` values('5','oupbZjjTiGLyyb8O53r19zbii8yA','1412044573','0','0','257e21c8a9e1be8c');");
E_D("replace into `imicms_wehcat_member_enddate` values('6','oupbZjsYocTtdvDjKYFJq3KYq1lU','1414830197','0','0','257e21c8a9e1be8c');");
E_D("replace into `imicms_wehcat_member_enddate` values('7','oupbZjgKmgDRCf0z_d4OTascySbI','1412745165','0','0','257e21c8a9e1be8c');");
E_D("replace into `imicms_wehcat_member_enddate` values('8','oQqMOt_oo2WOMI975KeuCjoV71iw','1412873094','0','0','99630ff411650cfa');");
E_D("replace into `imicms_wehcat_member_enddate` values('9','oupbZjuP48FhsAtYWMf4klbMitm0','1413007676','0','0','257e21c8a9e1be8c');");
E_D("replace into `imicms_wehcat_member_enddate` values('10','oQqMOtxsGB71YIgtToEyq0FgF3ts','1413704680','0','0','99630ff411650cfa');");
E_D("replace into `imicms_wehcat_member_enddate` values('11','oQqMOt0EK6PZkHPttftYqCNzA5ug','1413848142','0','0','99630ff411650cfa');");
E_D("replace into `imicms_wehcat_member_enddate` values('12','oupbZjhAKHCoiYLr7ap9VXbuFmL0','1416215706','0','0','257e21c8a9e1be8c');");
E_D("replace into `imicms_wehcat_member_enddate` values('13','oaEFqs7ozoDgthoJlsVSYRana-lQ','1416472560','0','0','c4448ac95e30a1eb');");
E_D("replace into `imicms_wehcat_member_enddate` values('14','oupbZjmFFuC-0ESsmkx_GNsmlAMc','1416897699','0','0','257e21c8a9e1be8c');");
E_D("replace into `imicms_wehcat_member_enddate` values('15','oaEFqs0Q4pJDSt_tva1bWNg2RIHM','1418551367','0','0','c4448ac95e30a1eb');");
E_D("replace into `imicms_wehcat_member_enddate` values('16','oaEFqs_sQHvmFvriTlLDHbg4R4uc','1418953833','0','0','c4448ac95e30a1eb');");
E_D("replace into `imicms_wehcat_member_enddate` values('17','ock4Kj13F27xphYhCoK42hWIjFAQ','1423838007','0','0','2a94af5381fcc932');");
E_D("replace into `imicms_wehcat_member_enddate` values('18','ock4KjySMH3BPsoPCoFthNQiIFWA','1435990201','0','0','2a94af5381fcc932');");
E_D("replace into `imicms_wehcat_member_enddate` values('19','olAiJt4ZVEaIsx0jL9zdyGjWWxe4','1441179400','0','0','1c5990460d702b81');");
E_D("replace into `imicms_wehcat_member_enddate` values('20','olAiJt0ktxsOUzz9O50NyGWlKavo','1441796991','0','0','1c5990460d702b81');");
E_D("replace into `imicms_wehcat_member_enddate` values('21','olAiJt0ktxsOUzz9O50NyGWlKavo','1441796985','0','0','1c5990460d702b81');");

require("../../inc/footer.php");
?>