<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_tools`;");
E_C("CREATE TABLE `imicms_tools` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `info` text NOT NULL,
  `url` varchar(200) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_tools` values('1','大家来找同','微信小游戏，找出2副图片中的相同部分','http://resource.duopao.com/duopao/games/small_games/weixingame/sameclick/sameclick.htm','1');");
E_D("replace into `imicms_tools` values('2','神秘方块','微信小游戏，将相同颜色的箱子推到一起消除...','http://resource.duopao.com/duopao/games/small_games/weixingame/unitem/Unitem.htm','2');");
E_D("replace into `imicms_tools` values('3','梦幻农场连连看','微信小游戏，农场作物连连看','http://resource.duopao.com/duopao/games/small_games/weixingame/DreamFarmLink/DreamFarmLink.htm','3');");
E_D("replace into `imicms_tools` values('4','小怪物吃饼干','微信小游戏，吃光画面上的全部饼干即可过关','http://resource.duopao.com/duopao/games/small_games/weixingame/bouffecookie/bouffecookie.htm','4');");
E_D("replace into `imicms_tools` values('5','爱星座','星盘星座每日星运查询','http://infoapp.3g.qq.com/g/s?g_f=22207&aid=astro#home','5');");
E_D("replace into `imicms_tools` values('6','中国天气网','三小时天气预报，一周天气预报，天气资讯，...','http://mobile.weather.com.cn/','6');");
E_D("replace into `imicms_tools` values('7','下厨房','精美菜谱大全','http://m.xiachufang.com/','7');");
E_D("replace into `imicms_tools` values('8','好大夫','医药专家在线咨询','http://m.haodf.com/touch','8');");
E_D("replace into `imicms_tools` values('9','艺龙酒店预订','在线酒店预订','http://m.elong.com/hotel/','9');");
E_D("replace into `imicms_tools` values('10','彩票开奖查询','福彩、足彩开奖信息在线查询','http://loto.sina.cn/i/open.do?label=1&sid=fc055b3a-d72c-41bf-96bc-b8e436ea79ea&agentId=233258&vt=3','10');");
E_D("replace into `imicms_tools` values('11','快递查询','快递单号在线查询','http://hao.uc.cn/bst/index?uc_param_str=prdnfrpfbivelabtbmntpvsscp#!/expressCheck','11');");
E_D("replace into `imicms_tools` values('12','航班查询','航班航线在线查询','http://hao.uc.cn/bst/index?uc_param_str=prdnfrpfbivelabtbmntpvsscp#!/flightCheck','12');");
E_D("replace into `imicms_tools` values('13','火车余票查询','火车票余票在线查询','http://wap.tieyou.com/sina/index_yupiao.html?pos=63&vt=4','13');");
E_D("replace into `imicms_tools` values('14','租房','在线搜索租房信息','http://m.soufun.com/zf/bj/','14');");
E_D("replace into `imicms_tools` values('15','运势','查看近期星座运势','http://dp.sina.cn/dpool/astro/starent/xingyun.php?pos=19&vt=4','15');");
E_D("replace into `imicms_tools` values('16','算命','姓名评分、测八字','http://www.aqioo.cn/free','16');");
E_D("replace into `imicms_tools` values('17','解梦','梦境快速解梦','http://www.aqioo.cn/dream','17');");
E_D("replace into `imicms_tools` values('18','房贷计算','各种房贷计算工具','http://tools.iask.cn/iask/tools/house_index.php?pos=63&vt=4','18');");
E_D("replace into `imicms_tools` values('19','股票','股票信息快速查看','http://finance.sina.cn/?sa=t60d13v512&pos=63&vt=4','19');");
E_D("replace into `imicms_tools` values('20','个税计算','各省市的个税计算工具','http://dp.sina.cn/dpool/tools/money/single.php?city_id=1&flag=per_tax&pos=63&vt=4','20');");
E_D("replace into `imicms_tools` values('21','医疗保险计算','各省市的医疗保险计算工具','http://dp.sina.cn/dpool/tools/money/single.php?flag=health_per&pos=63&vt=4','21');");
E_D("replace into `imicms_tools` values('22','养老保险计算','各省市的养老保险计算工具','http://dp.sina.cn/dpool/tools/money/single.php?flag=old_per&pos=63&vt=4','22');");
E_D("replace into `imicms_tools` values('23','住房公积金计算','各省市的住房公积金计算工具','http://dp.sina.cn/dpool/tools/money/single.php?flag=house_per&pos=63&vt=4','23');");
E_D("replace into `imicms_tools` values('24','常用电话号码','常用电话号码快速查询','http://m.46644.com/tool/tel/','24');");
E_D("replace into `imicms_tools` values('25','塔罗占卜','塔罗牌使用指南及占卜','http://ast.sina.cn/?sa=t282d771v166&pos=19&vt=4','25');");
E_D("replace into `imicms_tools` values('26','旅游大全','中国最大的旅游网站','http://m.cncn.com/','26');");

require("../../inc/footer.php");
?>