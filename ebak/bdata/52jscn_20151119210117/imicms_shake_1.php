<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shake`;");
E_C("CREATE TABLE `imicms_shake` (
  `id` int(8) NOT NULL auto_increment,
  `isact` int(1) NOT NULL default '0',
  `acttitle` varchar(40) NOT NULL,
  `account` varchar(40) NOT NULL,
  `isopen` int(1) NOT NULL default '0',
  `clienttime` int(11) NOT NULL default '3',
  `showtime` int(10) NOT NULL default '3',
  `shownum` int(11) NOT NULL default '10',
  `pass` varchar(150) default NULL,
  `joinnum` int(11) default NULL,
  `shaketype` int(11) NOT NULL default '1',
  `token` varchar(40) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `endtime` varchar(13) default NULL,
  `pass2` varchar(150) default NULL,
  `pass3` varchar(150) default NULL,
  `starttime` int(11) NOT NULL,
  `endshake` int(11) NOT NULL,
  `qrcode` varchar(150) default NULL,
  `keyword` varchar(100) NOT NULL default '',
  `intro` varchar(400) NOT NULL default '',
  `thumb` varchar(200) NOT NULL default '',
  `logo` char(100) NOT NULL,
  `cheer` varchar(350) NOT NULL,
  `background` varchar(150) default NULL,
  `backgroundmusic` varchar(150) default NULL,
  `music` varchar(150) default NULL,
  `usetime` int(10) NOT NULL default '0',
  `rule` varchar(600) NOT NULL default '',
  `info` varchar(600) NOT NULL default '',
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_shake` values('3','1','默认活动','dc','1','3','3','10','',NULL,'1','49ca6b5b1f0e6f41','1412773436',NULL,'','','3','133','','','','','','',NULL,NULL,NULL,'0','','','0');");
E_D("replace into `imicms_shake` values('5','0','摇一摇','亚蓝网公众服务','2','3','3','100','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141105/99630ff411650cfa2014110513511147578.jpg','0','1','99630ff411650cfa','1415167410','1415357892','','','3','133','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141105/99630ff411650cfa2014110513492758619.jpg','','','','','',NULL,NULL,NULL,'0','','','0');");

require("../../inc/footer.php");
?>