<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_game_config`;");
E_C("CREATE TABLE `imicms_game_config` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(30) NOT NULL default '',
  `wxid` varchar(40) NOT NULL default '',
  `wxname` varchar(50) NOT NULL default '',
  `wxlogo` varchar(150) NOT NULL default '',
  `link` varchar(150) NOT NULL default '',
  `uid` int(11) NOT NULL default '0',
  `key` varchar(40) NOT NULL default '',
  `attentionText` char(150) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_game_config` values('1','99630ff411650cfa','gh_56dc11cd5df9','亚蓝网公众服务','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20140912/99630ff411650cfa2014091214262498428.jpg','','128784','30446e3da1d4c688dd4fe5204119de61',NULL);");

require("../../inc/footer.php");
?>