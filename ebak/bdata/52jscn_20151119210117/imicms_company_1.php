<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_company`;");
E_C("CREATE TABLE `imicms_company` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `shortname` varchar(50) NOT NULL default '',
  `mp` varchar(11) NOT NULL default '',
  `tel` varchar(20) NOT NULL default '',
  `address` varchar(200) NOT NULL default '',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `intro` text NOT NULL,
  `catid` mediumint(3) NOT NULL default '0',
  `taxis` int(10) NOT NULL default '0',
  `isbranch` tinyint(1) NOT NULL default '0',
  `logourl` varchar(200) NOT NULL default '',
  `display` tinyint(1) NOT NULL default '1',
  `username` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `area_id` int(11) NOT NULL default '0',
  `cate_id` int(11) NOT NULL default '0',
  `market_id` int(11) NOT NULL default '0',
  `mark_url` varchar(200) NOT NULL default '',
  `add_time` char(10) NOT NULL default '0',
  `amapid` varchar(50) NOT NULL default '',
  `province` char(30) NOT NULL,
  `city` char(30) NOT NULL,
  `district` char(30) NOT NULL,
  `location_id` int(11) NOT NULL,
  `cat_name` char(50) NOT NULL,
  `business_type` varchar(100) NOT NULL,
  `recommend` varchar(500) NOT NULL,
  `special` varchar(500) NOT NULL,
  `introduction` varchar(800) NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `avg_price` int(11) NOT NULL,
  `opentime` int(11) NOT NULL,
  `closetime` int(11) NOT NULL,
  `available_state` tinyint(1) NOT NULL,
  `categories` varchar(100) NOT NULL,
  `sid` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_company` values('1','99630ff411650cfa','亚蓝信息技术有限公司','亚蓝信息技术有限公司（以下简称：亚蓝）一家专注于高品质网站视觉设计以及程序开发的提供商，多年来活跃于','18987133915','0871-68651126','云南.安宁昆钢罗白小区综合楼一楼','24.908252','102.503845','&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;white-space:normal;&quot;&gt;\r\n	&lt;img src=&quot;http://www.eake.cn/templets/moban/images/LOGO3.gif&quot; style=&quot;margin:0px;padding:0px;vertical-align:middle;font-size:12px;&quot; /&gt;\r\n&lt;/p&gt;\r\n&lt;br style=&quot;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;line-height:normal;white-space:normal;&quot; /&gt;\r\n&lt;h1 style=&quot;margin:0px;padding:0px;border:0px;font-size:25px;font-weight:normal;color:#00784C;height:60px;font-family:微软雅黑;line-height:normal;white-space:normal;&quot;&gt;\r\n	让浏览者喜欢，让客户受益，将艺术与商业完美结合&lt;span style=&quot;font-size:12px;font-style:italic;color:#999999;display:block;margin-top:2px;&quot;&gt;The friend of browers,the bonus of clients, the excenllent combination of arts and commerce.&lt;/span&gt;\r\n&lt;/h1&gt;\r\n&lt;h3 style=&quot;margin:12px 0px;padding:6px 0px;border:0px;font-family:微软雅黑;font-size:15px;color:#585858;line-height:normal;white-space:normal;background-image:url(http://www.eake.cn/templets/moban/images/dot.gif);background-position:0px 100%;background-repeat:repeat-x;&quot;&gt;\r\n	关于亚蓝\r\n&lt;/h3&gt;\r\n&lt;div class=&quot;item&quot; style=&quot;margin:0px;padding:0px 0px 10px;border:0px;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;line-height:normal;white-space:normal;&quot;&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		亚蓝信息技术有限公司（以下简称：亚蓝）一家专注于高品质网站视觉设计以及程序开发的提供商，多年来活跃于各个领域。得益于团队的优质素养和良好合作，我们的产品一贯重视勃然迸发的创意理念和高效的客户回报率。我们坚信&quot;设计提升品质&quot;的理念，一直秉承国际化创作观念和富有成效的专业操作。始终从市场的角度和客户的需求出发，融合视觉美学及有效策略，提升企业与产品的内在品质，为品牌创造独到的形象，拓展市场竞争空间与竞争优势。\r\n	&lt;/p&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		我们的客户遍布北京、上海、天津、深圳、广州、云南、香港等全国一线城市，我们优秀的网站设计理念、卓有成效的网络营销推广方式以及丰富的网站开发经验将为您打造一个全新的网络品牌形象。\r\n	&lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;h3 style=&quot;margin:12px 0px;padding:6px 0px;border:0px;font-family:微软雅黑;font-size:15px;color:#585858;line-height:normal;white-space:normal;background-image:url(http://www.eake.cn/templets/moban/images/dot.gif);background-position:0px 100%;background-repeat:repeat-x;&quot;&gt;\r\n	为什么选择我们\r\n&lt;/h3&gt;\r\n&lt;div class=&quot;item&quot; style=&quot;margin:0px;padding:0px 0px 10px;border:0px;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;line-height:normal;white-space:normal;&quot;&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		众多企业对亚蓝的选择，充分验证了亚蓝的策划能力与设计能力，凭借对市场趋势敏锐的洞察和对消费者、企业形态深刻的理解，让亚蓝获得丰富的设计灵感，这正是我们给予每位客户的期待，为企业提升品牌价值一直是领城互动的企业理念。\r\n	&lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;h3 style=&quot;margin:12px 0px;padding:6px 0px;border:0px;font-family:微软雅黑;font-size:15px;color:#585858;line-height:normal;white-space:normal;background-image:url(http://www.eake.cn/templets/moban/images/dot.gif);background-position:0px 100%;background-repeat:repeat-x;&quot;&gt;\r\n	我们的观点\r\n&lt;/h3&gt;\r\n&lt;div class=&quot;item&quot; style=&quot;margin:0px;padding:0px 0px 10px;border:0px;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;line-height:normal;white-space:normal;&quot;&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		我们从消费者的观点出发，让消费者在弹指之间为您驻足，让消费者在行进中被你吸引，告诉你更多他想要的东西和想做的事。让浏览者喜欢，让客户获益是最高原则。将艺术与商业完美结合是我们永恒的追求。\r\n	&lt;/p&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		作为专业的设计公司，亚蓝不遗余力地为客户提供令同业望其项背的一流服务。我们立志做企业最可信赖的网络服务商，一个真正能为客户提供优秀的设计，并且真正能让设计为企业带来利润的合作伙伴，为您解决品牌推广所遇到的问题。\r\n	&lt;/p&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		我们的创意设计宗旨是让浏览者喜欢，让客户受益，将艺术与商业完美结合。\r\n	&lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;h3 style=&quot;margin:12px 0px;padding:6px 0px;border:0px;font-family:微软雅黑;font-size:15px;color:#585858;line-height:normal;white-space:normal;background-image:url(http://www.eake.cn/templets/moban/images/dot.gif);background-position:0px 100%;background-repeat:repeat-x;&quot;&gt;\r\n	旗下运营网站\r\n&lt;/h3&gt;\r\n&lt;div class=&quot;item&quot; style=&quot;margin:0px;padding:0px 0px 10px;border:0px;color:#585858;font-family:微软雅黑, Verdana, 宋体, Arial, Sans;font-size:13px;line-height:normal;white-space:normal;&quot;&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		亚蓝微营销服务&lt;a href=&quot;http://wx.eake.cn/&quot; style=&quot;text-decoration:none;color:#575757;&quot;&gt;主页&lt;/a&gt;--微营销是现代一种低成本、高性价比的营销手段，主要表现在微博、微信等平台。与传统营销方式相比，“微营销”主张通过“虚拟”与“现实”的互动，建立一个涉及研发、产品、渠道、市场、品牌传播、促销、客户关系等更“轻”、更高效的营销全链条，整合各类营销资源，达到了以小博大、以轻博重的营销效果。\r\n	&lt;/p&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		微博云南内容同步工具&lt;a href=&quot;http://wx.eake.cn/&quot; style=&quot;text-decoration:none;color:#575757;&quot;&gt;主页&lt;/a&gt;--微博云南同步工具是一个能将您的内容发送到新浪微博、腾讯微博、人人网、开心网内容同步功能，只需要登录到为云南发布一条内容即可同步到以上各大社交平台的工具。\r\n	&lt;/p&gt;\r\n	&lt;p style=&quot;margin-top:10px;margin-bottom:10px;padding:0px;border:0px;line-height:22px;text-indent:2em;&quot;&gt;\r\n		自媒体&lt;a href=&quot;http://www.175886.com/&quot; style=&quot;text-decoration:none;color:#575757;&quot;&gt;主页&lt;/a&gt;--是中国领先的科技新媒体，我们报道最新的互联网科技新闻以及最有潜力的互联网创业企业。我们的目标是，通过对互联网行业及最新创业企业的关注，为中文互联网读者提供一个最佳的了解互联网行业当下与未来的科技媒体\r\n	&lt;/p&gt;\r\n&lt;/div&gt;','0','0','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141002/99630ff411650cfa2014100217443137078.png','1','','','0','0','0','','0','','','','','0','','','','','','','0','0','0','0','','');");

require("../../inc/footer.php");
?>