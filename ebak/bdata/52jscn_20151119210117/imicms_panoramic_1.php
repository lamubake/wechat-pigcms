<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_panoramic`;");
E_C("CREATE TABLE `imicms_panoramic` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL default '全景相册展示',
  `picurl` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL default '1',
  `click` int(10) NOT NULL default '0',
  `frontpic` varchar(255) NOT NULL,
  `rightpic` varchar(255) NOT NULL,
  `backpic` varchar(255) NOT NULL,
  `leftpic` varchar(255) NOT NULL,
  `toppic` varchar(255) NOT NULL,
  `bottompic` varchar(255) NOT NULL,
  `intro` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_panoramic` values('2','99630ff411650cfa','全景展示','全景','全景展示测试','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141006/99630ff411650cfa2014100613284260885.jpg','1','8','http://wx.eake.cn/tpl/User/default/common/Panoramic/sample/pano_f.jpg','http://wx.eake.cn/tpl/User/default/common/Panoramic/sample/pano_r.jpg','http://wx.eake.cn/tpl/User/default/common/Panoramic/sample/pano_b.jpg','http://wx.eake.cn/tpl/User/default/common/Panoramic/sample/pano_l.jpg','http://wx.eake.cn/tpl/User/default/common/Panoramic/sample/pano_u.jpg','http://wx.eake.cn/tpl/User/default/common/Panoramic/sample/pano_d.jpg','很高兴您能访问到我们的360全景展示，如有问题或建议请联系我们','0');");

require("../../inc/footer.php");
?>