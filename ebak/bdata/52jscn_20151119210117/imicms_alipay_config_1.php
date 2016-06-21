<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_alipay_config`;");
E_C("CREATE TABLE `imicms_alipay_config` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `paytype` varchar(20) NOT NULL default '',
  `name` varchar(100) NOT NULL,
  `pid` varchar(200) NOT NULL,
  `key` varchar(200) NOT NULL,
  `partnerkey` varchar(100) NOT NULL default '',
  `appsecret` varchar(200) NOT NULL default '',
  `appid` varchar(60) NOT NULL default '',
  `paysignkey` varchar(200) NOT NULL default '',
  `partnerid` varchar(200) NOT NULL default '',
  `mchid` varchar(100) NOT NULL,
  `open` tinyint(1) NOT NULL default '0',
  `info` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `uid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_alipay_config` values('1','257e21c8a9e1be8c','alipay','1231546','56sadf1sdafasdfsa','dsfs456sadf1sdafasdfsa','','','','','','','0','');");
E_D("replace into `imicms_alipay_config` values('2','99630ff411650cfa','','','','','','3b587e8b9de27f3a78f90e7dd2bf2896','','','','1263868601','1','a:11:{s:7:\"is_open\";s:1:\"1\";s:6:\"weixin\";a:10:{s:4:\"open\";s:1:\"2\";s:6:\"is_old\";s:1:\"0\";s:9:\"new_appid\";s:18:\"wx95bed594d1903443\";s:9:\"appsecret\";s:32:\"3b587e8b9de27f3a78f90e7dd2bf2896\";s:5:\"mchid\";s:10:\"1263868601\";s:3:\"key\";s:32:\"3b587e8b9de27f3a78f90e7dd2bf2896\";s:5:\"appid\";s:0:\"\";s:10:\"paysignkey\";s:0:\"\";s:9:\"partnerid\";s:0:\"\";s:10:\"partnerkey\";s:0:\"\";}s:6:\"alipay\";a:4:{s:4:\"open\";s:1:\"1\";s:4:\"name\";s:0:\"\";s:3:\"pid\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"tenpay\";a:3:{s:4:\"open\";s:1:\"1\";s:9:\"partnerid\";s:0:\"\";s:10:\"partnerkey\";s:0:\"\";}s:8:\"allinpay\";a:3:{s:4:\"open\";s:1:\"1\";s:10:\"merchantId\";s:0:\"\";s:11:\"merchantKey\";s:0:\"\";}s:6:\"yeepay\";a:6:{s:4:\"open\";s:1:\"1\";s:15:\"merchantaccount\";s:0:\"\";s:18:\"merchantPrivateKey\";s:0:\"\";s:17:\"merchantPublicKey\";s:0:\"\";s:15:\"yeepayPublicKey\";s:0:\"\";s:15:\"product_catalog\";s:0:\"\";}s:7:\"CardPay\";a:1:{s:4:\"open\";s:1:\"1\";}s:5:\"daofu\";a:1:{s:4:\"open\";s:1:\"1\";}s:6:\"dianfu\";a:1:{s:4:\"open\";s:1:\"1\";}s:8:\"platform\";a:2:{s:4:\"open\";s:1:\"0\";s:12:\"platformName\";s:0:\"\";}s:6:\"button\";s:0:\"\";}');");

require("../../inc/footer.php");
?>