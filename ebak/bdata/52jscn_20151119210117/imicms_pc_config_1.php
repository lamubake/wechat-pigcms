<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_config`;");
E_C("CREATE TABLE `imicms_pc_config` (
  `token` varchar(50) NOT NULL,
  `site_name` varchar(50) NOT NULL,
  `site_logo` varchar(150) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_address` varchar(200) NOT NULL,
  `site_qq` varchar(100) NOT NULL,
  `site_phone` varchar(100) NOT NULL,
  `site_email` varchar(100) NOT NULL,
  `site_count` text NOT NULL,
  `site_icp` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL,
  `seo_keywords` varchar(200) NOT NULL,
  `seo_description` text NOT NULL,
  `site_tpl` text NOT NULL,
  `tplid` int(11) NOT NULL,
  `other_info` text NOT NULL COMMENT '模板自定义配置项，序列化存储',
  PRIMARY KEY  (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `imicms_pc_config` values('kaiqpo1447853601','锦尚中国','http://demo8.52jscn.com/tpl/static/attachment/icon/canyin/canyin_red/1.png','锦尚中国','锦尚中国','8888888','15588888888','52jscn@163.com','','','锦尚中国','锦尚中国','锦尚中国','','1','');");

require("../../inc/footer.php");
?>