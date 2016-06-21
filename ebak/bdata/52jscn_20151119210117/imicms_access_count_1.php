<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_access_count`;");
E_C("CREATE TABLE `imicms_access_count` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(60) NOT NULL default 'alltoken',
  `module` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `count` int(10) unsigned NOT NULL default '1',
  `update_time` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `module` (`module`),
  KEY `controller` (`controller`),
  KEY `action` (`action`),
  KEY `count` (`count`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COMMENT='访问统计表'");
E_D("replace into `imicms_access_count` values('1','99630ff411650cfa','user','home','set','3','1446478223','1446478117');");
E_D("replace into `imicms_access_count` values('2','alltoken','user','home','set','3','1446478223','1446478117');");
E_D("replace into `imicms_access_count` values('3','99630ff411650cfa','user','index','index','3','1446478573','1446478158');");
E_D("replace into `imicms_access_count` values('4','alltoken','user','index','index','3','1446478573','1446478158');");
E_D("replace into `imicms_access_count` values('5','99630ff411650cfa','user','img','index','1','1446478435','1446478435');");
E_D("replace into `imicms_access_count` values('6','alltoken','user','img','index','1','1446478435','1446478435');");
E_D("replace into `imicms_access_count` values('7','99630ff411650cfa','user','index','switchtpl','3','1446478569','1446478560');");
E_D("replace into `imicms_access_count` values('8','alltoken','user','index','switchtpl','3','1446478569','1446478560');");
E_D("replace into `imicms_access_count` values('9','99630ff411650cfa','user','function','welcome','2','1446478932','1446478576');");
E_D("replace into `imicms_access_count` values('10','alltoken','user','function','welcome','2','1446478932','1446478576');");
E_D("replace into `imicms_access_count` values('11','99630ff411650cfa','user','upyun','upload','6','1446480623','1446480576');");
E_D("replace into `imicms_access_count` values('12','alltoken','user','upyun','upload','6','1446480623','1446480576');");
E_D("replace into `imicms_access_count` values('13','99630ff411650cfa','user','function','welcome','10','1447033336','1447000031');");
E_D("replace into `imicms_access_count` values('14','alltoken','user','function','welcome','10','1447033336','1447000031');");
E_D("replace into `imicms_access_count` values('15','99630ff411650cfa','user','api','index','2','1447000290','1447000222');");
E_D("replace into `imicms_access_count` values('16','alltoken','user','api','index','2','1447000290','1447000222');");
E_D("replace into `imicms_access_count` values('17','99630ff411650cfa','user','numqueue','index','2','1447033328','1447000350');");
E_D("replace into `imicms_access_count` values('18','alltoken','user','numqueue','index','2','1447033328','1447000350');");
E_D("replace into `imicms_access_count` values('19','99630ff411650cfa','user','sentiment','index','1','1447032552','1447032552');");
E_D("replace into `imicms_access_count` values('20','alltoken','user','sentiment','index','1','1447032552','1447032552');");
E_D("replace into `imicms_access_count` values('21','99630ff411650cfa','user','index','index','3','1447033352','1447033254');");
E_D("replace into `imicms_access_count` values('22','alltoken','user','index','index','3','1447033352','1447033254');");
E_D("replace into `imicms_access_count` values('23','99630ff411650cfa','user','zhaopin','index','1','1447033323','1447033323');");
E_D("replace into `imicms_access_count` values('24','alltoken','user','zhaopin','index','1','1447033323','1447033323');");
E_D("replace into `imicms_access_count` values('25','99630ff411650cfa','user','dishout','index','1','1447033331','1447033331');");
E_D("replace into `imicms_access_count` values('26','alltoken','user','dishout','index','1','1447033331','1447033331');");
E_D("replace into `imicms_access_count` values('27','kaiqpo1447853601','user','function','welcome','4','1447878225','1447853620');");
E_D("replace into `imicms_access_count` values('28','alltoken','user','function','welcome','1','1447853620','1447853620');");
E_D("replace into `imicms_access_count` values('29','kaiqpo1447853601','user','diymen','index','1','1447853629','1447853629');");
E_D("replace into `imicms_access_count` values('30','alltoken','user','diymen','index','1','1447853629','1447853629');");
E_D("replace into `imicms_access_count` values('31','kaiqpo1447853601','user','hongbaoqiye','index','1','1447853638','1447853638');");
E_D("replace into `imicms_access_count` values('32','alltoken','user','hongbaoqiye','index','1','1447853638','1447853638');");
E_D("replace into `imicms_access_count` values('33','kaiqpo1447853601','user','index','index','4','1447934171','1447872081');");
E_D("replace into `imicms_access_count` values('34','alltoken','user','index','index','4','1447934171','1447872081');");
E_D("replace into `imicms_access_count` values('35','alltoken','user','function','welcome','3','1447878225','1447872087');");
E_D("replace into `imicms_access_count` values('36','kaiqpo1447853601','user','web','set','3','1447872306','1447872103');");
E_D("replace into `imicms_access_count` values('37','alltoken','user','web','set','3','1447872306','1447872103');");
E_D("replace into `imicms_access_count` values('38','kaiqpo1447853601','user','upyun','upload','1','1447872272','1447872272');");
E_D("replace into `imicms_access_count` values('39','alltoken','user','upyun','upload','1','1447872272','1447872272');");
E_D("replace into `imicms_access_count` values('40','kaiqpo1447853601','user','attachment','my','1','1447872274','1447872274');");
E_D("replace into `imicms_access_count` values('41','alltoken','user','attachment','my','1','1447872274','1447872274');");
E_D("replace into `imicms_access_count` values('42','kaiqpo1447853601','user','attachment','index','2','1447872280','1447872277');");
E_D("replace into `imicms_access_count` values('43','alltoken','user','attachment','index','2','1447872280','1447872277');");
E_D("replace into `imicms_access_count` values('44','kaiqpo1447853601','user','web','choose_tpl','3','1447872410','1447872309');");
E_D("replace into `imicms_access_count` values('45','alltoken','user','web','choose_tpl','3','1447872410','1447872309');");
E_D("replace into `imicms_access_count` values('46','kaiqpo1447853601','user','web','nav','1','1447872435','1447872435');");
E_D("replace into `imicms_access_count` values('47','alltoken','user','web','nav','1','1447872435','1447872435');");
E_D("replace into `imicms_access_count` values('48','kaiqpo1447853601','user','phone','baseset','2','1447872507','1447872439');");
E_D("replace into `imicms_access_count` values('49','alltoken','user','phone','baseset','2','1447872507','1447872439');");
E_D("replace into `imicms_access_count` values('50','kaiqpo1447853601','user','phone','downloadfile','2','1447872510','1447872452');");
E_D("replace into `imicms_access_count` values('51','alltoken','user','phone','downloadfile','2','1447872510','1447872452');");
E_D("replace into `imicms_access_count` values('52','kaiqpo1447853601','wap','index','index','1','1447872563','1447872563');");
E_D("replace into `imicms_access_count` values('53','alltoken','wap','index','index','1','1447872563','1447872563');");
E_D("replace into `imicms_access_count` values('54','kaiqpo1447853601','user','home','set','1','1447872576','1447872576');");
E_D("replace into `imicms_access_count` values('55','alltoken','user','home','set','1','1447872576','1447872576');");
E_D("replace into `imicms_access_count` values('56','kaiqpo1447853601','user','customtmpls','dynamic','4','1447878266','1447872583');");
E_D("replace into `imicms_access_count` values('57','alltoken','user','customtmpls','dynamic','4','1447878266','1447872583');");
E_D("replace into `imicms_access_count` values('58','kaiqpo1447853601','wap','tmpls','show','8','1447878282','1447872594');");
E_D("replace into `imicms_access_count` values('59','alltoken','wap','tmpls','show','8','1447878282','1447872594');");
E_D("replace into `imicms_access_count` values('60','kaiqpo1447853601','user','micrstore','index','1','1447876648','1447876648');");
E_D("replace into `imicms_access_count` values('61','alltoken','user','micrstore','index','1','1447876648','1447876648');");
E_D("replace into `imicms_access_count` values('62','kaiqpo1447853601','user','micrstore','api','1','1447876651','1447876651');");
E_D("replace into `imicms_access_count` values('63','alltoken','user','micrstore','api','1','1447876651','1447876651');");
E_D("replace into `imicms_access_count` values('64','kaiqpo1447853601','user','shakearound','page_index','1','1447877757','1447877757');");
E_D("replace into `imicms_access_count` values('65','alltoken','user','shakearound','page_index','1','1447877757','1447877757');");
E_D("replace into `imicms_access_count` values('66','kaiqpo1447853601','user','wechat_group','index','1','1447878247','1447878247');");
E_D("replace into `imicms_access_count` values('67','alltoken','user','wechat_group','index','1','1447878247','1447878247');");
E_D("replace into `imicms_access_count` values('68','kaiqpo1447853601','user','yulan','index','2','1447878280','1447878258');");
E_D("replace into `imicms_access_count` values('69','alltoken','user','yulan','index','2','1447878280','1447878258');");

require("../../inc/footer.php");
?>