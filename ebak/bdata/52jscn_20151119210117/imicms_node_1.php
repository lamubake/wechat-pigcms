<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_node`;");
E_C("CREATE TABLE `imicms_node` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL COMMENT '节点名称',
  `title` varchar(50) NOT NULL COMMENT '菜单名称',
  `status` tinyint(1) NOT NULL default '0' COMMENT '是否激活 1：是 2：否',
  `remark` varchar(255) default NULL COMMENT '备注说明',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父ID',
  `level` tinyint(1) unsigned NOT NULL COMMENT '节点等级',
  `data` varchar(255) default NULL COMMENT '附加参数',
  `sort` smallint(6) unsigned NOT NULL default '0' COMMENT '排序权重',
  `display` tinyint(1) unsigned NOT NULL default '0' COMMENT '菜单显示类型 0:不显示 1:导航菜单 2:左侧菜单',
  PRIMARY KEY  (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=212 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_node` values('125','upsave','更新数据库','1','0','88','3','','0','0');");
E_D("replace into `imicms_node` values('124','del','删除功能','1','0','88','3','','0','0');");
E_D("replace into `imicms_node` values('123','upsave','更新数据库','1','0','117','3','','0','0');");
E_D("replace into `imicms_node` values('122','insert','写入数据库','1','0','117','3','','0','0');");
E_D("replace into `imicms_node` values('121','edit','编辑横幅','1','0','117','3','','0','0');");
E_D("replace into `imicms_node` values('120','add','添加横幅','1','0','117','3','','0','0');");
E_D("replace into `imicms_node` values('119','index','图片列表','1','0','117','3','','0','2');");
E_D("replace into `imicms_node` values('118','insert','写入数据库','1','0','111','3','','0','0');");
E_D("replace into `imicms_node` values('117','Images','横幅图片','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('116','add','添加新闻','1','0','111','3','','0','0');");
E_D("replace into `imicms_node` values('115','upsave','更新数据库','1','0','111','3','','0','0');");
E_D("replace into `imicms_node` values('114','del','删除新闻','1','0','111','3','','0','0');");
E_D("replace into `imicms_node` values('113','edit','编辑新闻','1','0','111','3','','0','0');");
E_D("replace into `imicms_node` values('112','index','新闻列表','1','0','111','3','','0','2');");
E_D("replace into `imicms_node` values('111','News','新闻管理','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('110','upsave','更新数据库','1','0','104','3','','0','0');");
E_D("replace into `imicms_node` values('109','insert','写入数据库','1','0','104','3','','0','0');");
E_D("replace into `imicms_node` values('108','del','删除更新','1','0','104','3','','0','0');");
E_D("replace into `imicms_node` values('107','edit','编辑更新','1','0','104','3','','0','0');");
E_D("replace into `imicms_node` values('106','add','添加功能','1','0','104','3','','0','2');");
E_D("replace into `imicms_node` values('105','index','功能列表','1','0','104','3','','0','2');");
E_D("replace into `imicms_node` values('104','Renew','功能更新进程','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('103','del','删除幻灯片','1','0','97','3','','0','0');");
E_D("replace into `imicms_node` values('102','upsave','更新数据库','1','0','97','3','','0','0');");
E_D("replace into `imicms_node` values('101','insert','插入数据库','1','0','97','3','','0','0');");
E_D("replace into `imicms_node` values('100','edit','编辑幻灯片','1','0','97','3','','0','0');");
E_D("replace into `imicms_node` values('99','add','添加幻灯片','1','0','97','3','','0','2');");
E_D("replace into `imicms_node` values('98','index','幻灯片列表','1','0','97','3','','0','2');");
E_D("replace into `imicms_node` values('97','Banners','首页幻灯片','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('96','add','添加','1','0','88','3','','0','2');");
E_D("replace into `imicms_node` values('95','Aboutus','关于我们','1','0','45','2','','0','2');");
E_D("replace into `imicms_node` values('94','platform','平台支付配置','1','0','6','3','','9','2');");
E_D("replace into `imicms_node` values('93','index','对帐列表','1','0','92','3','','0','2');");
E_D("replace into `imicms_node` values('92','Platform','平台支付','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('88','Funintro','功能介绍','1','0','45','2','','0','2');");
E_D("replace into `imicms_node` values('87','sms','短信接口','1','','6','3','','6','2');");
E_D("replace into `imicms_node` values('83','alipay','在线支付接口','1','0','6','3','','5','2');");
E_D("replace into `imicms_node` values('81','Token','公众号管理','1','0','80','2','','0','2');");
E_D("replace into `imicms_node` values('79','del','删除友情链接','1','0','73','3','','0','0');");
E_D("replace into `imicms_node` values('78','upsave','更新数据库','1','0','73','3','','0','0');");
E_D("replace into `imicms_node` values('77','insert','插入数据库','1','0','73','3','','0','0');");
E_D("replace into `imicms_node` values('75','add','添加链接','1','0','73','3','','0','2');");
E_D("replace into `imicms_node` values('76','edit','编辑链接','1','0','73','3','','0','0');");
E_D("replace into `imicms_node` values('74','index','友情链接','1','0','73','3','','0','2');");
E_D("replace into `imicms_node` values('73','Links','友情链接','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('72','upsave','更新数据库','1','0','66','3','','0','0');");
E_D("replace into `imicms_node` values('71','insert','写入数据库','1','0','66','3','','0','0');");
E_D("replace into `imicms_node` values('70','del','删除案例','1','0','66','3','','0','0');");
E_D("replace into `imicms_node` values('69','edit','编辑案例','1','0','66','3','','0','0');");
E_D("replace into `imicms_node` values('68','add','添加案例','1','0','66','3','','0','2');");
E_D("replace into `imicms_node` values('67','index','案例列表','1','0','66','3','','0','2');");
E_D("replace into `imicms_node` values('65','index','充值列表','1','0','64','3','','0','2');");
E_D("replace into `imicms_node` values('66','Case','客户案例','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('64','Records','充值记录','1','0','4','2','','0','2');");
E_D("replace into `imicms_node` values('62','edit','编辑','1','0','60','3','','0','0');");
E_D("replace into `imicms_node` values('63','del','删除','1','0','60','3','','0','0');");
E_D("replace into `imicms_node` values('61','index','列表','1','0','60','3','','0','2');");
E_D("replace into `imicms_node` values('60','Custom','自定义页面','1','0','5','2','','0','2');");
E_D("replace into `imicms_node` values('59','del','删除','1','0','57','3','','0','0');");
E_D("replace into `imicms_node` values('58','index','文本列表','1','0','57','3','','0','2');");
E_D("replace into `imicms_node` values('57','Text','微信文本','1','0','5','2','','0','2');");
E_D("replace into `imicms_node` values('56','upsave','更新客户','1','0','50','3','','0','0');");
E_D("replace into `imicms_node` values('55','insert','写入数据库','1','0','50','3','','0','0');");
E_D("replace into `imicms_node` values('54','del','删除客户','1','0','50','3','','0','0');");
E_D("replace into `imicms_node` values('53','edit','编辑客户','1','0','50','3','','0','0');");
E_D("replace into `imicms_node` values('52','add','添加客户','1','0','50','3','','0','2');");
E_D("replace into `imicms_node` values('51','index','客户列表','1','0','50','3','','0','0');");
E_D("replace into `imicms_node` values('50','Users','客户管理','1','0','3','2','','0','2');");
E_D("replace into `imicms_node` values('49','add','添加套餐','1','0','48','3','','0','2');");
E_D("replace into `imicms_node` values('48','User_group','套餐管理','1','0','3','2','','0','2');");
E_D("replace into `imicms_node` values('47','add','添加模块','1','0','46','3','','0','2');");
E_D("replace into `imicms_node` values('46','Function','功能模块','1','0','45','2','','0','2');");
E_D("replace into `imicms_node` values('45','Function','功能模块','1','0','1','0','','5','1');");
E_D("replace into `imicms_node` values('80','token','公众号管理','1','0','1','0','','3','1');");
E_D("replace into `imicms_node` values('42','del','微信图文删除','1','0','38','3','','0','0');");
E_D("replace into `imicms_node` values('41','edit','微信图文编辑','1','0','38','3','','0','0');");
E_D("replace into `imicms_node` values('39','index','图文列表','1','0','38','3','','0','2');");
E_D("replace into `imicms_node` values('40','add','图文添加','1','0','38','3','','0','2');");
E_D("replace into `imicms_node` values('38','Article','微信图文','1','0','5','2','','0','2');");
E_D("replace into `imicms_node` values('37','main','右侧栏目','1','0','35','3','','0','0');");
E_D("replace into `imicms_node` values('35','System','首页','1','0','2','2','','0','0');");
E_D("replace into `imicms_node` values('36','menu','左侧栏','1','0','35','3','','0','0');");
E_D("replace into `imicms_node` values('32','insert','保存','1','0','6','3','','0','0');");
E_D("replace into `imicms_node` values('148','role_sort','排序','1','','25','3','','0','0');");
E_D("replace into `imicms_node` values('30','insert','写入数据库','1','0','25','3','','0','0');");
E_D("replace into `imicms_node` values('29','del','删除套餐','1','0','25','3','','0','0');");
E_D("replace into `imicms_node` values('28','edit','编辑套餐','1','0','25','3','','0','0');");
E_D("replace into `imicms_node` values('27','index','管理组列表','1','0','25','3','','0','2');");
E_D("replace into `imicms_node` values('26','add','创建管理组','1','0','25','3','','0','2');");
E_D("replace into `imicms_node` values('25','Group','管理组','1','0','3','2','','0','2');");
E_D("replace into `imicms_node` values('24','del','删除客户','1','0','18','3','','0','0');");
E_D("replace into `imicms_node` values('23','update','更新客户','1','0','18','3','','0','0');");
E_D("replace into `imicms_node` values('22','insert','写入数据库','1','0','18','3','','0','0');");
E_D("replace into `imicms_node` values('21','edit','编辑客户','1','0','18','3','','0','0');");
E_D("replace into `imicms_node` values('20','index','站长列表','1','0','18','3','','0','2');");
E_D("replace into `imicms_node` values('19','add','添加站长','1','0','18','3','','0','2');");
E_D("replace into `imicms_node` values('18','User','站长管理','1','0','3','2','','0','2');");
E_D("replace into `imicms_node` values('17','del','删除节点','1','0','11','3','','0','0');");
E_D("replace into `imicms_node` values('16','update','更新节点','1','0','11','3','','0','0');");
E_D("replace into `imicms_node` values('15','edit','编辑节点','1','0','11','3','','0','0');");
E_D("replace into `imicms_node` values('14','insert','写入','1','0','11','3','','0','0');");
E_D("replace into `imicms_node` values('13','index','节点列表','1','','11','3','','0','2');");
E_D("replace into `imicms_node` values('12','add','添加节点','1','','11','3','','0','2');");
E_D("replace into `imicms_node` values('11','Node','节点管理','1','','2','2','','2','2');");
E_D("replace into `imicms_node` values('10','upfile','附件设置','1','','6','3','','4','2');");
E_D("replace into `imicms_node` values('9','email','邮箱设置','1','','6','3','','3','2');");
E_D("replace into `imicms_node` values('8','safe','安全设置','1','','6','3','','2','2');");
E_D("replace into `imicms_node` values('7','index','基本信息设置','1','','6','3','','1','2');");
E_D("replace into `imicms_node` values('6','Site','站点设置','1','','2','2','','1','2');");
E_D("replace into `imicms_node` values('5','article','内容管理','1','','1','0','','4','1');");
E_D("replace into `imicms_node` values('4','extent','扩展管理','1','','1','0','','6','1');");
E_D("replace into `imicms_node` values('3','User','客户管理','1','','1','0','','2','1');");
E_D("replace into `imicms_node` values('2','Site','站点管理','1','','1','0','','1','1');");
E_D("replace into `imicms_node` values('1','cms','根节点','1','','0','1','','0','0');");
E_D("replace into `imicms_node` values('126','insert','写入数据库','1','0','88','3','','0','0');");
E_D("replace into `imicms_node` values('127','index','功能列表','1','0','88','3','','1','2');");
E_D("replace into `imicms_node` values('128','edit','修改功能','1','0','88','3','','0','0');");
E_D("replace into `imicms_node` values('129','addclass','添加分类','1','0','88','3','','2','2');");
E_D("replace into `imicms_node` values('130','indexs','分类管理','1','0','88','3','','3','2');");
E_D("replace into `imicms_node` values('131','indexs','分类管理','1','0','66','3','','0','2');");
E_D("replace into `imicms_node` values('132','addclass','添加分类','1','0','66','3','','0','2');");
E_D("replace into `imicms_node` values('133','SystemIndex','后台首页','1','0','2','2','','4','2');");
E_D("replace into `imicms_node` values('134','Agent','代理商管理','1','0','1','2','','5','1');");
E_D("replace into `imicms_node` values('135','Agent','代理商管理','1','0','134','2','','0','2');");
E_D("replace into `imicms_node` values('136','add','添加','1','0','135','3','','0','2');");
E_D("replace into `imicms_node` values('137','AgentPrice','优惠套餐包','1','0','134','2','','0','2');");
E_D("replace into `imicms_node` values('138','add','添加','1','0','137','3','','0','2');");
E_D("replace into `imicms_node` values('139','AgentBuyRecords','消费记录','1','0','134','2','','0','2');");
E_D("replace into `imicms_node` values('140','add','添加','1','0','95','3','','0','2');");
E_D("replace into `imicms_node` values('141','index','后台管理','1','','35','3','','0','0');");
E_D("replace into `imicms_node` values('142','Index','回滚','1','','35','2','','0','0');");
E_D("replace into `imicms_node` values('143','rollback','回滚程序','1','','142','3','','0','0');");
E_D("replace into `imicms_node` values('144','checkUpdate','升级程序','1','','35','3','','0','0');");
E_D("replace into `imicms_node` values('145','doSqlUpdate','升级数据库','1','','35','3','','0','0');");
E_D("replace into `imicms_node` values('146','index','后台管理','1','','133','3','','0','0');");
E_D("replace into `imicms_node` values('147','mysqlajax','优化修复数据库','1','','6','3','','0','0');");
E_D("replace into `imicms_node` values('149','access','权限浏览','1','','25','3','','0','0');");
E_D("replace into `imicms_node` values('150','access_edit','权限编辑','1','','25','3','','0','0');");
E_D("replace into `imicms_node` values('151','index','管理组列表','1','','48','3','','0','2');");
E_D("replace into `imicms_node` values('152','edit','修改套餐','1','','48','3','','0','0');");
E_D("replace into `imicms_node` values('153','del','删除套餐','1','','48','3','','0','0');");
E_D("replace into `imicms_node` values('154','search','客户搜索','1','','50','3','','0','0');");
E_D("replace into `imicms_node` values('155','syname','设为系统用户','1','','50','3','','0','0');");
E_D("replace into `imicms_node` values('156','sysname','取消系统用户','1','','50','3','','0','0');");
E_D("replace into `imicms_node` values('157','index','公众号列表','1','','81','3','','0','2');");
E_D("replace into `imicms_node` values('158','del','公众号删除','1','','81','3','','0','0');");
E_D("replace into `imicms_node` values('159','index','列表','1','','95','3','','0','2');");
E_D("replace into `imicms_node` values('160','edit','修改','1','','95','3','','0','0');");
E_D("replace into `imicms_node` values('161','del','删除','1','','95','3','','0','0');");
E_D("replace into `imicms_node` values('162','adds','执行添加分类','1','','88','3','','0','0');");
E_D("replace into `imicms_node` values('163','edits','分类修改','1','','88','3','','0','0');");
E_D("replace into `imicms_node` values('164','dels','分类删除','1','','88','3','','0','0');");
E_D("replace into `imicms_node` values('165','upsaves','执行分类修改','1','','88','3','','0','0');");
E_D("replace into `imicms_node` values('166','search','确认设置分类','1','','88','3','','0','0');");
E_D("replace into `imicms_node` values('167','index','列表','1','','46','3','','0','2');");
E_D("replace into `imicms_node` values('168','edit','修改','1','','46','3','','0','0');");
E_D("replace into `imicms_node` values('169','del','删除','1','','46','3','','0','0');");
E_D("replace into `imicms_node` values('170','paid','处理账单','1','','92','3','','0','0');");
E_D("replace into `imicms_node` values('171','paid_all','一键处理','1','','92','3','','0','0');");
E_D("replace into `imicms_node` values('172','add','添加','1','','92','3','','0','0');");
E_D("replace into `imicms_node` values('173','del','删除','1','','92','3','','0','0');");
E_D("replace into `imicms_node` values('174','edit','修改','1','','92','3','','0','0');");
E_D("replace into `imicms_node` values('175','insert','插入数据库','1','','92','3','','0','0');");
E_D("replace into `imicms_node` values('176','upsave','更新数据库','1','','92','3','','0','0');");
E_D("replace into `imicms_node` values('177','adds','执行分类添加','1','','66','3','','0','0');");
E_D("replace into `imicms_node` values('178','edits','分类修改','1','','66','3','','0','0');");
E_D("replace into `imicms_node` values('179','upsaves','执行分类修改','1','','66','3','','0','0');");
E_D("replace into `imicms_node` values('180','dels','分类删除','1','','66','3','','0','0');");
E_D("replace into `imicms_node` values('181','send','确认充值','1','','64','3','','0','0');");
E_D("replace into `imicms_node` values('185','Database','数据库维护','1','0','2','2','','3','2');");
E_D("replace into `imicms_node` values('186','themes','模板设置','1','0','6','3','','7','2');");
E_D("replace into `imicms_node` values('187','rippleos_key','微WIFI','1','0','6','3','','8','2');");
E_D("replace into `imicms_node` values('188','wechat_api','登录授权','1','0','6','3','','10','2');");
E_D("replace into `imicms_node` values('189','Examine_image','图片审核','1','','4','2','','0','2');");
E_D("replace into `imicms_node` values('190','index','图片列表','1','','189','3','','0','2');");
E_D("replace into `imicms_node` values('191','del','删除','1','','189','3','','0','0');");
E_D("replace into `imicms_node` values('192','set','审核','1','','189','3','','0','0');");
E_D("replace into `imicms_node` values('193','set_all','一键审核','1','','189','3','','0','0');");
E_D("replace into `imicms_node` values('194','Susceptible','敏感词过滤','1','','4','2','','0','2');");
E_D("replace into `imicms_node` values('195','index','列表','1','','194','3','','0','2');");
E_D("replace into `imicms_node` values('196','add','添加','1','','194','3','','0','2');");
E_D("replace into `imicms_node` values('197','adds','批量添加','1','','194','3','','0','2');");
E_D("replace into `imicms_node` values('198','del','删除','1','','194','3','','0','0');");
E_D("replace into `imicms_node` values('199','set','开启关闭','1','','194','3','','0','0');");
E_D("replace into `imicms_node` values('200','set_all','一键开启关闭','1','','194','3','','0','0');");
E_D("replace into `imicms_node` values('201','edit','修改','1','','194','3','','0','0');");
E_D("replace into `imicms_node` values('202','CheckUpdate','更新程序','1','','2','2','','0','0');");
E_D("replace into `imicms_node` values('203','DoSqlUpdate','更新数据库','1','','2','2','','0','0');");
E_D("replace into `imicms_node` values('204','info','查看详情','1','','189','3','','0','0');");
E_D("replace into `imicms_node` values('205','Use','数据统计','1','','4','2','','0','2');");
E_D("replace into `imicms_node` values('206','index','统计图表','1','','205','3','','0','2');");
E_D("replace into `imicms_node` values('207','Customs','自定义导航','1','','4','2','','0','2');");
E_D("replace into `imicms_node` values('208','index','导航列表','1','','207','3','','0','2');");
E_D("replace into `imicms_node` values('210','index','日志浏览','1','0','209','3','','0','0');");
E_D("replace into `imicms_node` values('209','Errlogs','日志浏览','1','0','4','2','','0','0');");
E_D("replace into `imicms_node` values('211','Clear','清理缓存','1','0','2','2',NULL,'0','2');");

require("../../inc/footer.php");
?>