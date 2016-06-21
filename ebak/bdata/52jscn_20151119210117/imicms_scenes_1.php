<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_scenes`;");
E_C("CREATE TABLE `imicms_scenes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(200) default NULL,
  `title` varchar(255) default NULL,
  `picurl` varchar(5000) NOT NULL,
  `info` varchar(255) NOT NULL,
  `name` char(11) NOT NULL,
  `ca` varchar(255) NOT NULL,
  `catp` varchar(255) NOT NULL,
  `tph` varchar(255) NOT NULL,
  `musicurl` varchar(255) NOT NULL,
  `share` varchar(255) NOT NULL,
  `sharean` varchar(255) NOT NULL,
  `sharetp` varchar(255) NOT NULL,
  `sharetstp` varchar(255) NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `miaosu` varchar(255) NOT NULL,
  `click` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_scenes` values('1','99630ff411650cfa','展','亚蓝微营销','http://wx.eake.cn/uploads/9/99630ff411650cfa/6/c/1/b/thumb_54534fb81db10.jpg','我们让互联网变得如此简单，诚信、共赢、共同进步！','亚蓝-让营销更简','','http://wx.eake.cn/uploads/9/99630ff411650cfa/6/1/c/b/thumb_545715c5ab978.jpg','http://wx.eake.cn/uploads/9/99630ff411650cfa/a/5/4/6/thumb_5457160332819.jpg','http://wx.eake.cn/tpl/static/attachment/music/default/4.mp3','','','','','动动你的手指转发到你朋友圈！','亚蓝微营销场景展示','168');");

require("../../inc/footer.php");
?>