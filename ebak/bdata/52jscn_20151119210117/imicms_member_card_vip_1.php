<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_vip`;");
E_C("CREATE TABLE `imicms_member_card_vip` (
  `id` int(11) NOT NULL auto_increment,
  `cardid` int(10) NOT NULL default '0',
  `token` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `group` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `info` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `usetime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`),
  KEY `cardid` (`cardid`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_member_card_vip` values('22','32','11e16464d80e87e0','特权来了','0','0','1398873600','1400687999','特权来了特权来了特权来了','1399165483','0');");
E_D("replace into `imicms_member_card_vip` values('23','32','11e16464d80e87e0','特权来了1','0','1','0','0','特权来了','1398938424','0');");
E_D("replace into `imicms_member_card_vip` values('24','32','11e16464d80e87e0','1111','0','0','1399132800','1399478399','1111','1399166007','0');");
E_D("replace into `imicms_member_card_vip` values('25','55','a4cd4f614aa99519',' 特权名','0','1','0','0','特权名特权名特权名','1399751013','0');");

require("../../inc/footer.php");
?>