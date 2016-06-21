<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_case`;");
E_C("CREATE TABLE `imicms_case` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `url` char(255) NOT NULL,
  `img` char(200) NOT NULL,
  `status` varchar(1) NOT NULL,
  `agentid` int(10) NOT NULL default '0',
  `timg` char(200) NOT NULL,
  `classid` varchar(200) NOT NULL,
  `class` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `agentid` USING BTREE (`agentid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_case` values('1','测试','http://wx.eake.cn/','http://wx.eake.cn/tpl/Home/weixin/common/css/img/erwei_big.jpg','1','0','','','0');");

require("../../inc/footer.php");
?>