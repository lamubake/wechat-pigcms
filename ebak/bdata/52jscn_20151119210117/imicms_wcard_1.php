<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wcard`;");
E_C("CREATE TABLE `imicms_wcard` (
  `id` bigint(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `coverurl` text NOT NULL,
  `openpic` text NOT NULL,
  `picurl` text NOT NULL,
  `man` varchar(20) NOT NULL,
  `woman` varchar(20) NOT NULL,
  `who_first` tinyint(1) NOT NULL default '1',
  `phone` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `hour` varchar(2) NOT NULL,
  `min` varchar(2) NOT NULL,
  `place` text NOT NULL,
  `lng` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `video` text NOT NULL,
  `mp3url` text NOT NULL,
  `password` varchar(20) NOT NULL,
  `word` text NOT NULL,
  `qr_code` text NOT NULL,
  `copr` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `sort` tinyint(4) NOT NULL default '0',
  `click` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_wcard` values('1','99630ff411650cfa','我们结婚了','结婚','http://wx.eake.cn/tpl/static/images/wxt.png','http://wx.eake.cn/tpl/static/images/02.jpg','http://wx.eake.cn/tpl/static/images/02.jpg','袁秋明','任雯','1','087168651126','2014-11-30','17','00','安宁市昆钢宾馆','102.499868','24.909026','http://static.video.qq.com/TPout.swf?vid=e0141yxs5xr&amp;auto=0','http://lianjie.92cc.com/mp3/f95b31ee696e3452e5a243f2bd5e60955.mp3','888888','亲爱的朋友，我要结婚了，希望能在我的婚礼上得到你的祝福，并祝愿你也幸福。','','亚蓝微营销提供','0','1415168682','0','32');");

require("../../inc/footer.php");
?>