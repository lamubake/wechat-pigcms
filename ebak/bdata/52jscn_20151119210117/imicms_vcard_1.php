<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_vcard`;");
E_C("CREATE TABLE `imicms_vcard` (
  `id` int(12) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `company` varchar(100) default NULL,
  `company_tel` varchar(100) default NULL,
  `baiduapi` varchar(100) default NULL,
  `address` varchar(200) default NULL,
  `info` varchar(500) default NULL COMMENT '˾',
  `fax` varchar(100) default NULL,
  `title` text NOT NULL,
  `jianjie` text NOT NULL,
  `tp` char(255) NOT NULL,
  `logo` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_vcard` values('1','99630ff411650cfa','亚蓝信息技术有限公司','18987133915','','云南.安宁市昆钢罗白小区综合楼一楼','亚蓝信息技术有限公司（以下简称：亚蓝）一家专注于高品质网站视觉设计以及程序开发的提供商，多年来活跃于各个领域。得益于团队的优质素养和良好合作，我们的产品一贯重视勃然迸发的创意理念和高效的客户回报率。我们坚信&quot;设计提升品质&quot;的理念，一直秉承国际化创作观念和富有成效的专业操作。始终从市场的角度和客户的需求出发，融合视觉美学及有效策略，提升企业与产品的内在品质，为品牌创造独到的形象，拓展市场竞争空间与竞争优势。','18987133915','亚蓝信息技术有限公司','亚蓝信息技术有限公司（以下简称：亚蓝）一家专注于高品质网站视觉设计以及程序开发的提供商，多年来活跃于各个领域。','','');");

require("../../inc/footer.php");
?>