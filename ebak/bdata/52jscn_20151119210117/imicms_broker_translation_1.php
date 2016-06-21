<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_broker_translation`;");
E_C("CREATE TABLE `imicms_broker_translation` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `description` varchar(30) NOT NULL COMMENT '身份介绍',
  `type` tinyint(1) unsigned NOT NULL default '0' COMMENT '0普通1经纪人2其他',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_broker_translation` values('1','公司员工','0');");
E_D("replace into `imicms_broker_translation` values('2','大众经纪人','0');");
E_D("replace into `imicms_broker_translation` values('3','中介公司','0');");
E_D("replace into `imicms_broker_translation` values('4','代理公司','0');");
E_D("replace into `imicms_broker_translation` values('5','合作伙伴','0');");
E_D("replace into `imicms_broker_translation` values('6','老业主','1');");
E_D("replace into `imicms_broker_translation` values('7','产品顾问','2');");

require("../../inc/footer.php");
?>