<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_estate`;");
E_C("CREATE TABLE `imicms_estate` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `matchtype` tinyint(11) NOT NULL,
  `cover` varchar(200) NOT NULL,
  `panorama_id` int(11) default NULL,
  `classify_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `banner` varchar(200) NOT NULL,
  `video` varchar(200) default NULL,
  `house_banner` varchar(200) NOT NULL,
  `place` varchar(80) NOT NULL default '',
  `lng` varchar(15) NOT NULL,
  `lat` varchar(15) NOT NULL,
  `estate_desc` text NOT NULL,
  `project_brief` text NOT NULL,
  `traffic_desc` text NOT NULL,
  `path` varchar(3000) default '0',
  `tpid` int(11) default '36',
  `conttpid` int(11) default NULL,
  `slide1` char(100) NOT NULL,
  `slide2` char(100) NOT NULL,
  `slide3` char(100) NOT NULL,
  `slide4` char(100) NOT NULL,
  `slide5` char(100) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `token_2` (`token`),
  FULLTEXT KEY `token` (`token`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `keyword` (`keyword`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_estate` values('1','99630ff411650cfa','微房产演示','房','0','http://wx.eake.cn/uploads/9/99630ff411650cfa/2/6/7/b/thumb_5605546e31975.jpg','2','18','1','http://wx.eake.cn/uploads/9/99630ff411650cfa/5/0/9/b/thumb_560554be8d24d.jpg','http://static.video.qq.com/TPout.swf?vid=a0127iyoxsd&auto=0','http://wx.eake.cn/uploads/9/99630ff411650cfa/e/7/3/1/thumb_560554deb34a7.jpg','本楼盘为测试楼盘演示','102.503998','24.908072','<h3 style=\"font-family:微软雅黑;font-size:15px;color:#585858;\">\r\n	关于亚蓝\r\n</h3>\r\n<div class=\"item\" style=\"margin:0px;padding:0px 0px 10px;border:0px;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;\">\r\n	<p style=\"text-indent:2em;\">\r\n		亚蓝信息技术有限公司（以下简称：亚蓝）成立于2014年10月，是一家专注于高品质网站视觉设计以及程序开发的提供商，多年来活跃于各个领域。亚蓝旗下拥有微营销、内容同步平台、自主开发的信息网核心等，其中亚蓝微营销为全国上万家企业、个人开发了独有的微信公众号。亚蓝发展到今天得益于团队的优质素养和良好合作，我们的产品一贯重视勃然迸发的创意理念和高效的客户回报率。我们坚信\"设计提升品质\"的理念，一直秉承国际化创作观念和富有成效的专业操作。始终从市场的角度和客户的需求出发，融合视觉美学及有效策略，提升企业与产品的内在品质，为品牌创造独到的形象，拓展市场竞争空间与竞争优势。<br />\r\n目前公司拥有员工60余人，下设：技术部、市场部、综合部、运营部、广告部、置业部6大部门。公司秉承诚信、共赢、共同进步的理念和企业文化为客户提供优质的服务。\r\n	</p>\r\n	<p style=\"text-indent:2em;\">\r\n		我们的客户遍布北京、上海、天津、深圳、广州、云南、香港等全国一线城市，我们优秀的网站设计理念、卓有成效的网络营销推广方式以及丰富的网站开发经验将为您打造一个全新的网络品牌形象。\r\n	</p>\r\n</div>\r\n<h3 style=\"font-family:微软雅黑;font-size:15px;color:#585858;\">\r\n	为什么选择我们\r\n</h3>\r\n<div class=\"item\" style=\"margin:0px;padding:0px 0px 10px;border:0px;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;\">\r\n	<p style=\"text-indent:2em;\">\r\n		众多企业对亚蓝的选择，充分验证了亚蓝的策划能力与设计能力，凭借对市场趋势敏锐的洞察和对消费者、企业形态深刻的理解，让亚蓝获得丰富的设计灵感，这正是我们给予每位客户的期待，为企业提升品牌价值一直是领城互动的企业理念。\r\n	</p>\r\n</div>\r\n<h3 style=\"font-family:微软雅黑;font-size:15px;color:#585858;\">\r\n	我们的观点\r\n</h3>\r\n<div class=\"item\" style=\"margin:0px;padding:0px 0px 10px;border:0px;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;\">\r\n	<p style=\"text-indent:2em;\">\r\n		我们从消费者的观点出发，让消费者在弹指之间为您驻足，让消费者在行进中被你吸引，告诉你更多他想要的东西和想做的事。让浏览者喜欢，让客户获益是最高原则。将艺术与商业完美结合是我们永恒的追求。\r\n	</p>\r\n	<p style=\"text-indent:2em;\">\r\n		作为专业的设计公司，亚蓝不遗余力地为客户提供令同业望其项背的一流服务。我们立志做企业最可信赖的网络服务商，一个真正能为客户提供优秀的设计，并且真正能让设计为企业带来利润的合作伙伴，为您解决品牌推广所遇到的问题。\r\n	</p>\r\n	<p style=\"text-indent:2em;\">\r\n		我们的创意设计宗旨是让浏览者喜欢，让客户受益，将艺术与商业完美结合。\r\n	</p>\r\n</div>','<span>本案是为了亚蓝微营销展示给客户观看使用。</span>','<span>本案是为了亚蓝微营销展示给客户观看使用</span>','0','36',NULL,'http://wx.eake.cn/uploads/9/99630ff411650cfa/1/b/4/3/thumb_5604a3f2b34a7.jpg','http://wx.eake.cn/uploads/9/99630ff411650cfa/b/c/6/9/thumb_5604a41d3567e.png','http://wx.eake.cn/uploads/9/99630ff411650cfa/0/9/7/4/thumb_5604a4311e848.png','http://wx.eake.cn/uploads/9/99630ff411650cfa/3/e/e/8/thumb_5604a477e8b25.png','http://wx.eake.cn/uploads/9/99630ff411650cfa/9/2/0/3/thumb_5604a487ec82e.png');");

require("../../inc/footer.php");
?>