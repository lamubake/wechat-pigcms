<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_pay_record`;");
E_C("CREATE TABLE `imicms_member_card_pay_record` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `orderid` char(30) NOT NULL,
  `ordername` varchar(1000) NOT NULL,
  `transactionid` varchar(100) default NULL,
  `paytype` char(30) default NULL,
  `createtime` int(11) NOT NULL,
  `paytime` int(11) default NULL,
  `paid` tinyint(4) NOT NULL default '0',
  `price` double(10,2) unsigned NOT NULL default '0.00',
  `token` char(50) NOT NULL,
  `wecha_id` char(50) NOT NULL,
  `module` varchar(30) NOT NULL default 'Card',
  `type` tinyint(4) NOT NULL default '1',
  `company_id` int(11) NOT NULL,
  `cardid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_member_card_pay_record` values('1','201411051243217918','10001 充值',NULL,NULL,'1415162601',NULL,'0','1000.00','99630ff411650cfa','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','Card','1','0','0');");

require("../../inc/footer.php");
?>