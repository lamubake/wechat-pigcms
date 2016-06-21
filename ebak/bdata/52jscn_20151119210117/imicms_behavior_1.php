<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_behavior`;");
E_C("CREATE TABLE `imicms_behavior` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `openid` varchar(60) NOT NULL,
  `date` varchar(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `model` varchar(60) NOT NULL,
  `num` int(11) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_behavior` values('1','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-03','1409736335','chat','0','昆明公交10','0');");
E_D("replace into `imicms_behavior` values('2','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-03','1409736594','chat','0','昆明公交15','0');");
E_D("replace into `imicms_behavior` values('3','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-03','1409759921','chat','0','昆明公交','0');");
E_D("replace into `imicms_behavior` values('4','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-04','1409827532','chat','0','安宁ヅ׸','0');");
E_D("replace into `imicms_behavior` values('5','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-04','1409827541','chat','0','安宁ヅ','0');");
E_D("replace into `imicms_behavior` values('6','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-23','1411450588','chat','0','项目分析','0');");
E_D("replace into `imicms_behavior` values('7','0','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','2014-09-25','1411603935','chat','0','1','0');");
E_D("replace into `imicms_behavior` values('8','0','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','2014-09-25','1411604021','chat','0','天津50公交','0');");
E_D("replace into `imicms_behavior` values('9','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-25','1411643570','chat','0','测试','0');");
E_D("replace into `imicms_behavior` values('10','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-25','1411644231','chat','0','退出','0');");
E_D("replace into `imicms_behavior` values('11','0','99630ff411650cfa','oQqMOt85Ie2SIJ_qXS-qGmBt8OQs','2014-09-29','1411959331','chat','0','价格','0');");
E_D("replace into `imicms_behavior` values('12','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-09-29','1411981145','chat','0','安宁公交56','0');");
E_D("replace into `imicms_behavior` values('13','0','257e21c8a9e1be8c','oupbZjof1M0-rpqKcJcylfXzKGG8','2014-09-29','1411985457','chat','3','用户关注','0');");
E_D("replace into `imicms_behavior` values('14','0','257e21c8a9e1be8c','oupbZjof1M0-rpqKcJcylfXzKGG8','2014-09-30','1412041984','chat','3','用户关注','0');");
E_D("replace into `imicms_behavior` values('15','0','257e21c8a9e1be8c','oupbZjjTiGLyyb8O53r19zbii8yA','2014-09-30','1412044573','chat','0','用户关注','0');");
E_D("replace into `imicms_behavior` values('16','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-09-30','1412072815','chat','6','用户关注','0');");
E_D("replace into `imicms_behavior` values('17','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-09-30','1412072883','chat','0','功能','0');");
E_D("replace into `imicms_behavior` values('18','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-09-30','1412072894','chat','0','产品','0');");
E_D("replace into `imicms_behavior` values('19','0','257e21c8a9e1be8c','oupbZjgKmgDRCf0z_d4OTascySbI','2014-10-02','1412238170','chat','0','用户关注','0');");
E_D("replace into `imicms_behavior` values('20','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-10-03','1412322795','chat','0','吃','0');");
E_D("replace into `imicms_behavior` values('21','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-10-03','1412322806','chat','0','住','0');");
E_D("replace into `imicms_behavior` values('22','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-10-03','1412322808','chat','0','行','0');");
E_D("replace into `imicms_behavior` values('23','0','257e21c8a9e1be8c','oupbZjgKmgDRCf0z_d4OTascySbI','2014-10-03','1412332133','chat','0','产品','0');");
E_D("replace into `imicms_behavior` values('24','0','257e21c8a9e1be8c','oupbZjgKmgDRCf0z_d4OTascySbI','2014-10-03','1412332159','chat','0','商品','0');");
E_D("replace into `imicms_behavior` values('25','0','257e21c8a9e1be8c','oupbZjgKmgDRCf0z_d4OTascySbI','2014-10-04','1412396465','chat','0','产品','0');");
E_D("replace into `imicms_behavior` values('26','0','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','2014-10-04','1412396707','chat','0','双桥路','0');");
E_D("replace into `imicms_behavior` values('27','0','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','2014-10-04','1412396735','chat','0','昆明','0');");
E_D("replace into `imicms_behavior` values('28','0','257e21c8a9e1be8c','oupbZjgKmgDRCf0z_d4OTascySbI','2014-10-04','1412411922','chat','0','商品','0');");
E_D("replace into `imicms_behavior` values('29','0','257e21c8a9e1be8c','oupbZjgKmgDRCf0z_d4OTascySbI','2014-10-04','1412427136','chat','0','功能','0');");
E_D("replace into `imicms_behavior` values('30','0','257e21c8a9e1be8c','oupbZjof1M0-rpqKcJcylfXzKGG8','2014-10-06','1412567751','chat','0','只是','0');");
E_D("replace into `imicms_behavior` values('31','0','257e21c8a9e1be8c','oupbZjgKmgDRCf0z_d4OTascySbI','2014-10-08','1412745165','chat','0','加油','0');");
E_D("replace into `imicms_behavior` values('32','0','99630ff411650cfa','oQqMOt_oo2WOMI975KeuCjoV71iw','2014-10-10','1412873089','chat','1','2','0');");
E_D("replace into `imicms_behavior` values('33','0','257e21c8a9e1be8c','oupbZjuP48FhsAtYWMf4klbMitm0','2014-10-11','1413007619','chat','0','城都','0');");
E_D("replace into `imicms_behavior` values('34','0','257e21c8a9e1be8c','oupbZjuP48FhsAtYWMf4klbMitm0','2014-10-11','1413007676','chat','0','成都','0');");
E_D("replace into `imicms_behavior` values('35','0','257e21c8a9e1be8c','oupbZjof1M0-rpqKcJcylfXzKGG8','2014-10-15','1413376895','chat','0','杰成','0');");
E_D("replace into `imicms_behavior` values('36','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-10-16','1413444151','chat','0','jic','0');");
E_D("replace into `imicms_behavior` values('37','0','257e21c8a9e1be8c','oupbZjof1M0-rpqKcJcylfXzKGG8','2014-10-16','1413468507','chat','0','？？？？？？？？？？？？？','0');");
E_D("replace into `imicms_behavior` values('38','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-10-18','1413633868','chat','0','游戏','0');");
E_D("replace into `imicms_behavior` values('39','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-10-19','1413694219','chat','0','游戏库','0');");
E_D("replace into `imicms_behavior` values('40','0','99630ff411650cfa','oQqMOtxsGB71YIgtToEyq0FgF3ts','2014-10-19','1413704680','chat','0','商品','0');");
E_D("replace into `imicms_behavior` values('41','0','99630ff411650cfa','oQqMOt0EK6PZkHPttftYqCNzA5ug','2014-10-21','1413848106','chat','0','建水人才市场','0');");
E_D("replace into `imicms_behavior` values('42','0','99630ff411650cfa','oQqMOt0EK6PZkHPttftYqCNzA5ug','2014-10-21','1413848142','chat','0','/:?','0');");
E_D("replace into `imicms_behavior` values('43','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-10-31','1414746739','chat','0',' 营销','0');");
E_D("replace into `imicms_behavior` values('44','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-11-01','1414808073','chat','0','什么情况？','0');");
E_D("replace into `imicms_behavior` values('45','0','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','2014-11-01','1414830197','chat','0','生活常识','0');");
E_D("replace into `imicms_behavior` values('46','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-11-04','1415069475','chat','0','名片','0');");
E_D("replace into `imicms_behavior` values('47','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-11-04','1415087259','chat','0','？','0');");
E_D("replace into `imicms_behavior` values('48','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-11-05','1415165128','chat','0','好吃','0');");
E_D("replace into `imicms_behavior` values('49','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-11-05','1415165227','chat','3','聊天','0');");
E_D("replace into `imicms_behavior` values('50','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-11-05','1415165483','chat','0','lbs','0');");
E_D("replace into `imicms_behavior` values('51','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-11-05','1415166142','chat','0','昆明','0');");
E_D("replace into `imicms_behavior` values('52','0','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','2014-11-05','1415175285','chat','0','看全景','0');");
E_D("replace into `imicms_behavior` values('53','0','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','2014-11-05','1415193590','chat','0','sy','0');");
E_D("replace into `imicms_behavior` values('54','0','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','2014-11-06','1415206463','chat','0','微信墙','0');");
E_D("replace into `imicms_behavior` values('55','0','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','2014-11-07','1415304652','chat','0','KTV','0');");
E_D("replace into `imicms_behavior` values('56','0','257e21c8a9e1be8c','oupbZjhAKHCoiYLr7ap9VXbuFmL0','2014-11-17','1416215706','chat','0','手艺','0');");
E_D("replace into `imicms_behavior` values('57','0','c4448ac95e30a1eb','oaEFqs7ozoDgthoJlsVSYRana-lQ','2014-11-19','1416378929','chat','0','生活知识','0');");
E_D("replace into `imicms_behavior` values('58','0','c4448ac95e30a1eb','oaEFqs7ozoDgthoJlsVSYRana-lQ','2014-11-19','1416379152','chat','0','还没有配置出来','0');");
E_D("replace into `imicms_behavior` values('59','0','c4448ac95e30a1eb','oaEFqs7ozoDgthoJlsVSYRana-lQ','2014-11-20','1416472560','chat','0','更多','0');");
E_D("replace into `imicms_behavior` values('60','0','257e21c8a9e1be8c','oupbZjmFFuC-0ESsmkx_GNsmlAMc','2014-11-25','1416897674','chat','0','喝酒','0');");
E_D("replace into `imicms_behavior` values('61','0','257e21c8a9e1be8c','oupbZjmFFuC-0ESsmkx_GNsmlAMc','2014-11-25','1416897687','chat','0','喝茶','0');");
E_D("replace into `imicms_behavior` values('62','0','257e21c8a9e1be8c','oupbZjmFFuC-0ESsmkx_GNsmlAMc','2014-11-25','1416897699','chat','0','38度','0');");
E_D("replace into `imicms_behavior` values('63','0','c4448ac95e30a1eb','oaEFqs0Q4pJDSt_tva1bWNg2RIHM','2014-12-14','1418551367','chat','0','不会','0');");
E_D("replace into `imicms_behavior` values('64','0','c4448ac95e30a1eb','oaEFqs_sQHvmFvriTlLDHbg4R4uc','2014-12-19','1418953823','chat','2','在吗??我要推广、发联糸QQ來','0');");
E_D("replace into `imicms_behavior` values('65','0','2a94af5381fcc932','ock4Kj13F27xphYhCoK42hWIjFAQ','2015-02-13','1423838007','chat','0','用户关注','0');");
E_D("replace into `imicms_behavior` values('66','0','2a94af5381fcc932','ock4KjySMH3BPsoPCoFthNQiIFWA','2015-07-04','1435989695','chat','0','亲，给是说分享几天可以免费体验一次','0');");
E_D("replace into `imicms_behavior` values('67','0','2a94af5381fcc932','ock4KjySMH3BPsoPCoFthNQiIFWA','2015-07-04','1435990201','chat','0','/::)','0');");
E_D("replace into `imicms_behavior` values('68','0','1c5990460d702b81','olAiJt4ZVEaIsx0jL9zdyGjWWxe4','2015-09-02','1441179400','chat','0','http://pan.baidu.com/s/1c02kZpI\n','0');");
E_D("replace into `imicms_behavior` values('69','0','1c5990460d702b81','olAiJt0ktxsOUzz9O50NyGWlKavo','2015-09-09','1441796985','chat','1','她是/:?','0');");
E_D("replace into `imicms_behavior` values('70','0','1c5990460d702b81','olAiJt0ktxsOUzz9O50NyGWlKavo','2015-09-09','1441796985','chat','1','她是/:?','0');");

require("../../inc/footer.php");
?>