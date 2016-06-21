<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_flash`;");
E_C("CREATE TABLE `imicms_flash` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `img` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `info` varchar(90) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort` varchar(6) NOT NULL,
  `tip` smallint(2) NOT NULL default '1',
  `did` int(11) NOT NULL default '0' COMMENT '分类ID',
  `fid` int(11) NOT NULL default '0' COMMENT '子分类ID',
  PRIMARY KEY  (`id`),
  KEY `tip` (`tip`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_flash` values('1','257e21c8a9e1be8c','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413312362786.jpg','http://wx.eake.cn/index.php?g=Wap&amp;amp;m=Index&amp;amp;a=lists&amp;amp;classid=9&amp;amp;token=257e21c8a9e1be8c&amp;amp;wecha_id=oupbZjof1M0-rpqKcJcylfXzKGG8','杰诚家政服务公司','','','1','0','0');");
E_D("replace into `imicms_flash` values('2','257e21c8a9e1be8c','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413361241799.jpg','http://wx.eake.cn/index.php?g=Wap&amp;amp;m=Index&amp;amp;a=lists&amp;amp;classid=9&amp;amp;token=257e21c8a9e1be8c','杰诚家政服务有限公司','','','1','0','0');");
E_D("replace into `imicms_flash` values('3','257e21c8a9e1be8c','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413373732686.jpg','http://wx.eake.cn/index.php?g=Wap&amp;amp;m=Index&amp;amp;a=lists&amp;amp;classid=9&amp;amp;token=257e21c8a9e1be8c','杰诚家政服务有限公司','','','1','0','0');");
E_D("replace into `imicms_flash` values('4','99630ff411650cfa','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141105/99630ff411650cfa2014110520595870575.jpg','{siteUrl}/index.php?g=Wap&amp;amp;amp;m=Index&amp;amp;amp;a=lists&amp;amp;amp;token=99630ff411650cfa&amp;amp;amp;wecha_id={wechat_id}&amp;amp;amp;classid=1','亚蓝产品','','','1','0','0');");
E_D("replace into `imicms_flash` values('5','99630ff411650cfa','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141008/99630ff411650cfa2014100815442952574.jpg','{siteUrl}/index.php?g=Wap&amp;amp;m=Index&amp;amp;a=lists&amp;amp;token=99630ff411650cfa&amp;amp;wecha_id={wechat_id}&amp;amp;classid=2','亚蓝功能查询','','','1','0','0');");
E_D("replace into `imicms_flash` values('6','99630ff411650cfa','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141105/99630ff411650cfa2014110521003113048.jpg','{siteUrl}/index.php?g=Wap&amp;amp;amp;amp;m=Index&amp;amp;amp;amp;a=lists&amp;amp;amp;amp;token=99630ff411650cfa&amp;amp;amp;amp;wecha_id={wechat_id}&amp;amp;amp;amp;classid=15','微信营销','','','1','0','0');");
E_D("replace into `imicms_flash` values('7','99630ff411650cfa','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141105/99630ff411650cfa2014110521010260803.jpg','{siteUrl}/index.php?g=Wap&amp;amp;amp;m=Index&amp;amp;amp;a=lists&amp;amp;amp;token=99630ff411650cfa&amp;amp;amp;wecha_id={wechat_id}&amp;amp;amp;classid=16','微博营销','','','1','0','0');");
E_D("replace into `imicms_flash` values('8','c4448ac95e30a1eb','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914435962844.jpg','','杰诚家政服务公司','','','1','0','0');");
E_D("replace into `imicms_flash` values('9','c4448ac95e30a1eb','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914441982523.jpg','','杰诚家政服务公司','','','1','0','0');");
E_D("replace into `imicms_flash` values('10','c4448ac95e30a1eb','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914443410689.jpg','','杰诚家政服务公司','','','1','0','0');");
E_D("replace into `imicms_flash` values('13','2a94af5381fcc932','http://wx.eake.cn/tpl/static/attachment/background/view/9.jpg','','','','','2','0','0');");
E_D("replace into `imicms_flash` values('14','1c5990460d702b81','http://wx.eake.cn/uploads/1/1c5990460d702b81/f/7/d/7/thumb_55e65dc3e1113.jpg','','让你的童年充满色彩','','','1','0','0');");
E_D("replace into `imicms_flash` values('15','1c5990460d702b81','http://wx.eake.cn/uploads/1/1c5990460d702b81/7/4/a/5/thumb_55e65ddb22551.jpg','','用相机留住你美好的童年','','','1','0','0');");
E_D("replace into `imicms_flash` values('16','1c5990460d702b81','http://wx.eake.cn/uploads/1/1c5990460d702b81/0/7/9/4/thumb_55e9221aa4083.jpg','','童话世界','','','1','0','0');");
E_D("replace into `imicms_flash` values('17','1c5990460d702b81','http://wx.eake.cn/uploads/1/1c5990460d702b81/5/6/b/5/thumb_55e9277a90f56.jpg','','萌宝贝','','','1','0','0');");
E_D("replace into `imicms_flash` values('18','1c5990460d702b81','http://wx.eake.cn/uploads/1/1c5990460d702b81/3/4/b/3/thumb_55e92ad7bebc2.jpg','','我的满足就是有你陪伴的日子','','','1','0','0');");
E_D("replace into `imicms_flash` values('19','1c5990460d702b81','http://wx.eake.cn/uploads/1/1c5990460d702b81/b/8/8/b/thumb_55e9336c07a12.jpg','','纯真年代','','','3','44','43');");

require("../../inc/footer.php");
?>