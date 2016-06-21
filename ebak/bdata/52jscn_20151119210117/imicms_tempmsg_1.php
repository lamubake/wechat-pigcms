<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_tempmsg`;");
E_C("CREATE TABLE `imicms_tempmsg` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tempkey` char(50) NOT NULL,
  `name` char(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `topcolor` char(10) NOT NULL default '#029700',
  `textcolor` char(10) NOT NULL default '#000000',
  `token` char(40) NOT NULL,
  `tempid` char(100) default NULL,
  `status` tinyint(4) NOT NULL default '0',
  `industry` char(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT '模板类型（0：系统自带的，1：自己增加的）',
  PRIMARY KEY  (`id`),
  KEY `tempkey` (`tempkey`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_tempmsg` values('1','TM00130','预约看房通知','{{first.DATA}}\r\n预约楼盘：{{apartmentName.DATA}}\r\n房号：{{roomNumber.DATA}}\r\n楼盘地址：{{address.DATA}}\r\n预约时间：{{time.DATA}}\r\n{{remark.DATA}}','#029700','#000000','neazzp1406534391','','0','','0');");
E_D("replace into `imicms_tempmsg` values('2','TM00785','开奖结果通知','\r\n{{first.DATA}}\r\n开奖项目：{{program.DATA}}\r\n中奖详情：{{result.DATA}}\r\n{{remark.DATA}}','#029700','#000000','neazzp1406534391','','0','','0');");
E_D("replace into `imicms_tempmsg` values('3','TM00820','服务完成通知','\r\n{{first.DATA}}\r\n完成情况：{{keynote1.DATA}}\r\n完成日期：{{keynote2.DATA}}\r\n{{remark.DATA}}','#029700','#000000','neazzp1406534391','','0','','0');");

require("../../inc/footer.php");
?>