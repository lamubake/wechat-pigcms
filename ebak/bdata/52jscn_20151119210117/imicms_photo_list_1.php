<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_photo_list`;");
E_C("CREATE TABLE `imicms_photo_list` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `info` varchar(120) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_photo_list` values('1','99630ff411650cfa','2','5','0','http://wx.eake.cn/Data/upload/99630ff411650cfa/ab196bbfaad123e6cf0f69599c7cab50.jpg','1443145083','1','5');");
E_D("replace into `imicms_photo_list` values('2','99630ff411650cfa','2','6','0','http://wx.eake.cn/Data/upload/99630ff411650cfa/5c1744159edb730e11ab6876f5429fa2.jpg','1443145083','1','6');");
E_D("replace into `imicms_photo_list` values('3','99630ff411650cfa','2','9','0','http://wx.eake.cn/Data/upload/99630ff411650cfa/bfd0dfc9c2c4ae407d6c955d0c98baed.jpg','1443145083','1','9');");
E_D("replace into `imicms_photo_list` values('4','99630ff411650cfa','2','10','0','http://wx.eake.cn/Data/upload/99630ff411650cfa/632dcc2ac9b2cab0112e9e49e9e8f318.jpg','1443145083','1','10');");
E_D("replace into `imicms_photo_list` values('5','99630ff411650cfa','2','12','0','http://wx.eake.cn/Data/upload/99630ff411650cfa/0bce7d6b586cdfce553cf2d388aafd33.jpg','1443145083','1','12');");

require("../../inc/footer.php");
?>