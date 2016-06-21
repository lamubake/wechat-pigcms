<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_error_logs`;");
E_C("CREATE TABLE `imicms_error_logs` (
  `id` int(100) NOT NULL auto_increment,
  `message` varchar(1000) NOT NULL,
  `file` varchar(1000) NOT NULL,
  `line` varchar(1000) NOT NULL,
  `time` varchar(1000) NOT NULL,
  `hostname` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `now_url` varchar(1000) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `post` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_error_logs` values('176','非法操作:template','D:\\\\wwwroot\\\\weakecn\\\\wwwroot\\\\Lib\\\\Core\\\\Action.class.php','251','1446480690','106.58.247.23','http://w.eake.cn/index.php?g=System&m=Site&a=index&pid=6&level=3','http://w.eake.cn:80/index.php?g=System&m=Site&a=template&pid=6&level=3','106.58.247.23','');");
E_D("replace into `imicms_error_logs` values('177','syntax error, unexpected &apos;\$this&apos; (T_VARIABLE), expecting function (T_FUNCTION) D:\\\\wwwroot\\\\weakecn\\\\wwwroot\\\\PigCms\\\\Lib\\\\Action\\\\User\\\\ZhaopinAction.class.php 第 13 行.','D:\\\\wwwroot\\\\weakecn\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447033250','112.115.155.88','http://w.eake.cn/index.php?g=User&m=Function&a=welcome&token=99630ff411650cfa&id=1','http://w.eake.cn:80/index.php?g=User&m=Zhaopin&a=index&token=99630ff411650cfa','112.115.155.88','');");
E_D("replace into `imicms_error_logs` values('178','syntax error, unexpected &apos;\$this&apos; (T_VARIABLE), expecting function (T_FUNCTION) D:\\\\wwwroot\\\\weakecn\\\\wwwroot\\\\PigCms\\\\Lib\\\\Action\\\\User\\\\ZhaopinAction.class.php 第 13 行.','D:\\\\wwwroot\\\\weakecn\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447033265','112.115.155.88','http://w.eake.cn/index.php?g=User&m=Function&a=welcome&token=99630ff411650cfa&id=1','http://w.eake.cn:80/index.php?g=User&m=Zhaopin&a=index&token=99630ff411650cfa','112.115.155.88','');");
E_D("replace into `imicms_error_logs` values('179','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Driver\\\\Db\\\\DbMysql.class.php 第 104 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447870779','127.0.0.1','http://demo8.52jscn.com/index.php?g=System&m=Site&a=index&pid=6&level=3','http://demo8.52jscn.com:80/index.php?g=System&m=Site&a=mysqlajax','127.0.0.1','');");
E_D("replace into `imicms_error_logs` values('180','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Common\\\\functions.php 第 1097 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447872040','127.0.0.1','http://demo8.52jscn.com/index.php?g=System&m=Site&a=index&pid=6&level=3','http://demo8.52jscn.com:80/index.php?g=System&m=Site&a=themes&pid=6&level=3','127.0.0.1','');");
E_D("replace into `imicms_error_logs` values('181','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Common\\\\functions.php 第 1097 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447872040','127.0.0.1','http://demo8.52jscn.com/index.php?g=System&m=Admin&a=index','http://demo8.52jscn.com:80/index.php?g=System&m=System&a=index','127.0.0.1','');");
E_D("replace into `imicms_error_logs` values('182','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Driver\\\\Db\\\\DbMysql.class.php 第 104 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447877499','127.0.0.1','http://demo8.52jscn.com/index.php?g=System&m=Site&a=index&pid=6&level=3','http://demo8.52jscn.com:80/index.php?g=System&m=Site&a=mysqlajax','127.0.0.1','');");
E_D("replace into `imicms_error_logs` values('183','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Common\\\\functions.php 第 1097 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447877499','127.0.0.1','http://demo8.52jscn.com/index.php?g=User&m=CustomTmpls&a=dynamic&token=kaiqpo1447853601','http://demo8.52jscn.com:80/index.php?g=User&m=Hongbaoqiye&a=zzs&token=kaiqpo1447853601','127.0.0.1','');");
E_D("replace into `imicms_error_logs` values('184','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Common\\\\functions.php 第 1097 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447877499','127.0.0.1','http://demo8.52jscn.com/index.php?g=User&m=CustomTmpls&a=dynamic&token=kaiqpo1447853601','http://demo8.52jscn.com:80/index.php?g=User&m=SeniorScene&a=highLive&token=kaiqpo1447853601','127.0.0.1','');");
E_D("replace into `imicms_error_logs` values('185','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Driver\\\\Db\\\\DbMysql.class.php 第 104 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447878564','127.0.0.1','http://demo8.52jscn.com/index.php?g=System&m=Site&a=index&pid=6&level=3','http://demo8.52jscn.com:80/index.php?g=System&m=Site&a=mysqlajax','127.0.0.1','');");
E_D("replace into `imicms_error_logs` values('186','Maximum execution time of 60 seconds exceeded D:\\\\Site\\\\demo8\\\\wwwroot\\\\Common\\\\functions.php 第 1097 行.','D:\\\\Site\\\\demo8\\\\wwwroot\\\\Lib\\\\Core\\\\Think.class.php','246','1447878564','127.0.0.1','http://demo8.52jscn.com/index.php?g=System&m=Admin&a=index','http://demo8.52jscn.com:80/index.php?g=System&m=System&a=index','127.0.0.1','');");

require("../../inc/footer.php");
?>