<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_selfform_input`;");
E_C("CREATE TABLE `imicms_selfform_input` (
  `id` int(10) NOT NULL auto_increment,
  `formid` int(10) NOT NULL default '0',
  `displayname` varchar(30) NOT NULL default '',
  `fieldname` varchar(30) NOT NULL default '',
  `inputtype` varchar(20) NOT NULL default '',
  `options` varchar(200) NOT NULL default '',
  `require` tinyint(1) NOT NULL default '0',
  `regex` varchar(100) NOT NULL default '',
  `taxis` mediumint(4) NOT NULL default '0',
  `errortip` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `formid` (`formid`,`taxis`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_selfform_input` values('1','1','输入姓名','yuye','text','','1','/^[0-9]{1,30}\$/','1','输入错误，请重新输入');");
E_D("replace into `imicms_selfform_input` values('2','1','输入您的电话','tel','text','','1','/^13[0-9]{9}\$|^15[0-9]{9}\$|^18[0-9]{9}\$/','2','');");

require("../../inc/footer.php");
?>