<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_weixin_account`;");
E_C("CREATE TABLE `imicms_weixin_account` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `appId` char(45) NOT NULL,
  `appSecret` char(45) NOT NULL,
  `token` varchar(100) default NULL,
  `encodingAesKey` varchar(70) default NULL,
  `type` tinyint(3) default NULL COMMENT '1开放平台公众号服务，2其他',
  `date_time` char(15) default NULL,
  `component_verify_ticket` varchar(100) default NULL,
  `component_access_token` varchar(150) NOT NULL,
  `token_expires` char(15) NOT NULL,
  `agentid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>