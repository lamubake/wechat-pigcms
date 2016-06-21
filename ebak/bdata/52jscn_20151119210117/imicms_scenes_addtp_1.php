<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_scenes_addtp`;");
E_C("CREATE TABLE `imicms_scenes_addtp` (
  `id` int(11) NOT NULL auto_increment,
  `bd` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `info` varchar(120) NOT NULL,
  `style1` tinyint(1) NOT NULL default '0',
  `cover` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `lng` double NOT NULL,
  `lat` double NOT NULL,
  `address` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `wechat` varchar(255) NOT NULL,
  `bdname` varchar(255) NOT NULL,
  `bdtitle` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_scenes_addtp` values('19','0','99630ff411650cfa','1','12345','9','http://wx.eake.cn/uploads/9/99630ff411650cfa/5/6/f/0/5457211b81dc6.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('18','0','99630ff411650cfa','1','12345','8','http://wx.eake.cn/uploads/9/99630ff411650cfa/8/e/2/2/5457213331d27.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('16','0','99630ff411650cfa','1','12345','6','http://wx.eake.cn/uploads/9/99630ff411650cfa/4/6/0/7/54572150a7ec4.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('17','0','99630ff411650cfa','1','12345','7','http://wx.eake.cn/uploads/9/99630ff411650cfa/d/7/c/2/545721424c9d2.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('15','0','99630ff411650cfa','1','12345','5','http://wx.eake.cn/uploads/9/99630ff411650cfa/2/6/a/2/5457215985ebf.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('14','0','99630ff411650cfa','1','12345','4','http://wx.eake.cn/uploads/9/99630ff411650cfa/5/3/5/f/54572163815c2.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('13','0','99630ff411650cfa','1','12345','3','http://wx.eake.cn/uploads/9/99630ff411650cfa/d/a/2/3/5457216eeda06.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('12','0','99630ff411650cfa','1','12345','2','http://wx.eake.cn/uploads/9/99630ff411650cfa/7/a/2/4/54572177c663f.jpg','1414996387','1','','0','','','0','0','','','','','','');");
E_D("replace into `imicms_scenes_addtp` values('11','0','99630ff411650cfa','1','12345','1','http://wx.eake.cn/uploads/9/99630ff411650cfa/d/8/7/a/545721824c48c.jpg','1414996387','1','','0','','','0','0','','','','','','');");

require("../../inc/footer.php");
?>