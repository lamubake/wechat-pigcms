<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_vcard_list`;");
E_C("CREATE TABLE `imicms_vcard_list` (
  `id` int(12) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) default NULL,
  `tel` varchar(100) default NULL,
  `mobile` varchar(100) default NULL,
  `work` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `qq` varchar(100) default NULL,
  `sort` int(11) NOT NULL default '0',
  `banner` varchar(200) NOT NULL,
  `jianjie` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_vcard_list` values('1','99630ff411650cfa','尹可玺','http://wx.eake.cn/uploads/9/99630ff411650cfa/5/b/7/1/thumb_543ce3286cfb2.png','','18987133915','技术部','cneake@qq.com','61091793','0','http://wx.eake.cn/uploads/9/99630ff411650cfa/6/6/9/8/thumb_543ce47e5a318.jpg','十年互联网建站经验，三年微信营销经验。');");

require("../../inc/footer.php");
?>