<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wxq`;");
E_C("CREATE TABLE `imicms_wxq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL default '' COMMENT '活动名称',
  `keyword` varchar(255) NOT NULL COMMENT '关键字',
  `enter_tips` varchar(300) NOT NULL default '' COMMENT '进入提示',
  `quit_tips` varchar(300) NOT NULL default '' COMMENT '退出提示',
  `send_tips` varchar(300) NOT NULL default '' COMMENT '发表提示',
  `quit_command` varchar(10) NOT NULL default '' COMMENT '退出指令',
  `description` text NOT NULL COMMENT '描述',
  `timeout` int(10) unsigned NOT NULL default '0' COMMENT '超时时间',
  `isshow` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否需要审核',
  `createtime` varchar(13) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `qrcode` char(255) NOT NULL COMMENT '二维码',
  `background` char(255) NOT NULL COMMENT '墻背景',
  `showtime` int(11) NOT NULL COMMENT '每张墻轮换时间',
  `logourl` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_wxq` values('1','49ca6b5b1f0e6f41','10','sdf','我要上墙 上墙 毕业典礼墻','sadf','sdfds','afdsa','dsd','','444','1','1412773769','','http://wx.eake.cn/tpl/Static/kindeditors/attached/49ca6b5b1f0e6f41/image/20141008/49ca6b5b1f0e6f412014100821092746386.png','','444','http://wx.eake.cn/tpl/Static/kindeditors/attached/49ca6b5b1f0e6f41/image/20141008/49ca6b5b1f0e6f412014100821091839899.png','1');");
E_D("replace into `imicms_wxq` values('3','99630ff411650cfa','1','我们都上墙！','墙','欢迎您上墙','成功退出墙，欢迎下次再来','上墙成功','退出墙','亲，您好：\r\n    一起来拿奖！！！！','600','0','1415166700','','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141105/99630ff411650cfa2014110513492758619.jpg','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141105/99630ff411650cfa2014110513511147578.jpg','300','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141030/99630ff411650cfa2014103019372119008.jpg','0');");

require("../../inc/footer.php");
?>