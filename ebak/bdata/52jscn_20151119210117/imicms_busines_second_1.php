<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_busines_second`;");
E_C("CREATE TABLE `imicms_busines_second` (
  `sid` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `type` char(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mid_id` int(11) NOT NULL,
  `picurl` varchar(200) NOT NULL default '',
  `learntime` varchar(100) NOT NULL,
  `datatype` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL,
  `second_desc` text NOT NULL,
  `oneprice` decimal(10,2) default '0.00',
  `googsnumber` bigint(20) NOT NULL default '0',
  `havenumber` bigint(20) default NULL,
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_busines_second` values('1','257e21c8a9e1be8c','housekeeper','家庭，公司保洁，工程开荒，花园设计施工','1','http://wx.eake.cn/uploads/2/257e21c8a9e1be8c/c/f/5/6/thumb_542e624aadc55.jpg','30/小时，3元/平米','点击可以预定所有服务地面，门窗，厨房，卫生间，家具，玻璃，不包括装饰灯具，抽油烟机内部，锅碗等','1','','280.00','9988',NULL);");
E_D("replace into `imicms_busines_second` values('2','257e21c8a9e1be8c','housekeeper','保洁','1','http://wx.eake.cn/uploads/2/257e21c8a9e1be8c/c/f/5/6/thumb_542e624aadc55.jpg','3元/平米','卫生清扫、沙发保养等等','2','在测试等待修改','288.00','9996',NULL);");

require("../../inc/footer.php");
?>