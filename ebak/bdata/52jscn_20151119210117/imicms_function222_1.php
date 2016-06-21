<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_function222`;");
E_C("CREATE TABLE `imicms_function222` (
  `id` int(11) NOT NULL auto_increment,
  `gid` tinyint(3) NOT NULL,
  `usenum` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `funname` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `isserve` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_function222` values('1','1','0','天气查询','tianqi','天气查询服务:例  城市名+天气','1','1');");
E_D("replace into `imicms_function222` values('2','1','0','糗事','qiushi','糗事　直接发送糗事','1','1');");
E_D("replace into `imicms_function222` values('3','1','0','计算器','jishuan','计算器使用方法　例：计算50-50　，计算100*100','1','1');");
E_D("replace into `imicms_function222` values('4','4','0','朗读','langdu','朗读＋关键词　例：朗读多用户营销系统','1','1');");
E_D("replace into `imicms_function222` values('5','3','0','健康指数查询','jiankang','健康指数查询　健康＋高，＋重　例：健康170,65','1','1');");
E_D("replace into `imicms_function222` values('6','1','0','快递查询','kuaidi','快递＋快递名＋快递号　例：快递顺丰117215889174','1','1');");
E_D("replace into `imicms_function222` values('7','1','0','笑话','xiaohua','笑话　直接发送笑话','1','1');");
E_D("replace into `imicms_function222` values('8','2','0','藏头诗','changtoushi',' 藏头诗+关键词　例：藏头诗我爱你','1','1');");
E_D("replace into `imicms_function222` values('9','1','0','陪聊','peiliao','聊天　直接输入聊天关键词即可','1','1');");
E_D("replace into `imicms_function222` values('10','1','0','小黄鸡','liaotian','直接输入聊天关键词即可 很黄很暴力','1','1');");
E_D("replace into `imicms_function222` values('11','3','0','周公解梦','mengjian','周公解梦　梦见+关键词　例如:梦见父母','1','1');");
E_D("replace into `imicms_function222` values('12','2','0','语音翻译','yuyinfanyi','翻译＋关键词 例：翻译你好','1','1');");
E_D("replace into `imicms_function222` values('13','2','0','火车查询','huoche','火车查询　火车＋城市＋目的地　例火车上海南京','1','1');");
E_D("replace into `imicms_function222` values('14','2','0','公交查询','gongjiao','公交＋城市＋公交编号　例：上海公交774','1','1');");
E_D("replace into `imicms_function222` values('15','2','0','身份证查询','shenfenzheng','身份证＋号码　　例：身份证342423198803015568','1','1');");
E_D("replace into `imicms_function222` values('16','1','0','手机归属地查询','shouji','手机归属地查询(吉凶 运势) 手机＋手机号码　例：手机13917778912','1','1');");
E_D("replace into `imicms_function222` values('17','3','0','音乐查询','yinle','音乐＋音乐名  例：音乐爱你一万年','1','1');");
E_D("replace into `imicms_function222` values('18','1','0','附近周边信息查询','fujin','附近周边信息查询(ＬＢＳ）','1','1');");
E_D("replace into `imicms_function222` values('19','7','0','幸运大转盘','choujiang','输入抽奖　即可参加幸运大转盘抽奖活动','2','1');");
E_D("replace into `imicms_function222` values('20','4','0','淘宝店铺','taobao','输入淘宝＋关键词　即可访问淘宝3g手机店铺','2','1');");
E_D("replace into `imicms_function222` values('21','4','0','会员资料管理','userinfo','会员资料管理　输入会员　即可参与','2','1');");
E_D("replace into `imicms_function222` values('22','1','0','翻译','fanyi','翻译＋关键词 例：翻译你好','1','1');");
E_D("replace into `imicms_function222` values('23','4','0','第三方接口','api','第三方接口整合模块，请在管理平台下载接口文件并配置接口信息，','1','1');");
E_D("replace into `imicms_function222` values('24','1','0','姓名算命','suanming','姓名算命 算命＋关键词　例：算命李白','1','1');");
E_D("replace into `imicms_function222` values('25','3','0','百度百科','baike','百度百科　使用方法：百科＋关键词　例：百科北京','2','1');");
E_D("replace into `imicms_function222` values('26','2','0','彩票查询','caipiao','回复内容:彩票+彩种即可查询彩票中奖信息,例：彩票双色球','1','1');");
E_D("replace into `imicms_function222` values('27','7','0','抽奖','choujiang','抽奖,输入抽奖即可参加幸运大转盘','1','1');");
E_D("replace into `imicms_function222` values('28','4','0','刮刮卡','guagua','刮刮卡抽奖活动','1','1');");
E_D("replace into `imicms_function222` values('29','4','0','3G首页','shouye','输入首页,访问微3g 网站','1','1');");
E_D("replace into `imicms_function222` values('30','1','0','DIY宣传页','adma','DIY宣传页,用于创建二维码宣传页权限开启','1','1');");
E_D("replace into `imicms_function222` values('31','4','0','会员卡','huiyuanka','尊贵享受vip会员卡,回复会员卡即可领取会员卡','1','1');");
E_D("replace into `imicms_function222` values('33','7','0','官网帐号审核','shenhe','官网帐号审核','1','1');");
E_D("replace into `imicms_function222` values('34','1','0','歌词查询','geci','回复歌词＋歌名即可查询一首歌曲的歌词,例：歌词醉清风','1','1');");
E_D("replace into `imicms_function222` values('35','2','0','域名whois查询','yuming','域名whois查询　回复域名 可查询网站备案信息,域名whois注册时间等等\r\n 例：163.com','1','1');");
E_D("replace into `imicms_function222` values('36','4','0','自定义菜单','diymen_set','自定义菜单','2','1');");
E_D("replace into `imicms_function222` values('37','4','0','快捷与版权','plugmenu_set','快捷与版权 提供一键拨号，一键导航，一键email等等快捷功','2','1');");
E_D("replace into `imicms_function222` values('38','4','0','团购系统','etuan','在线团购系统','2','1');");
E_D("replace into `imicms_function222` values('39','4','0','在线商城','shop','在线商城','1','1');");
E_D("replace into `imicms_function222` values('40','4','0','网络订餐','dx','无线网络订餐','1','1');");
E_D("replace into `imicms_function222` values('41','4','0','通用预订系统','host_kev','通用预订系统 可用于酒店预订，ktv订房，旅游预订等。','1','1');");
E_D("replace into `imicms_function222` values('42','4','0','微投票','vote','微投票','2','1');");
E_D("replace into `imicms_function222` values('43','4','0','微喜帖','wcard','微喜帖','2','1');");
E_D("replace into `imicms_function222` values('44','4','0','微房产','house','微房产','2','1');");
E_D("replace into `imicms_function222` values('46','4','0','360全景','Panoramic','360全景','2','1');");
E_D("replace into `imicms_function222` values('47','4','0','360°全景','Panorama','360°全景','1','1');");
E_D("replace into `imicms_function222` values('48','4','0','微贺卡','Heka','微贺卡','1','1');");
E_D("replace into `imicms_function222` values('49','4','0','摇一摇','Shake','摇一摇','1','1');");
E_D("replace into `imicms_function222` values('50','7','0','万能表单','diymen_set','万能表单','2','1');");
E_D("replace into `imicms_function222` values('51','1','0','水果机','LuckyFruit','水果机','1','1');");
E_D("replace into `imicms_function222` values('52','4','0','微汽车','Car','微汽车','2','1');");
E_D("replace into `imicms_function222` values('53','4','0','水果达人','LuckyFruit','水果达人','1','1');");
E_D("replace into `imicms_function222` values('54','4','0','新砸金蛋','GoldenEgg','新砸金蛋','1','1');");
E_D("replace into `imicms_function222` values('55','1','0','微预约','Yuyue','微预约','1','1');");
E_D("replace into `imicms_function222` values('56','1','0','微酒店','Jiudian','微酒店','1','1');");
E_D("replace into `imicms_function222` values('57','1','0','微医疗','Yiliao','微医疗','1','1');");
E_D("replace into `imicms_function222` values('58','1','0','微美容','Meirong','微美容','1','1');");
E_D("replace into `imicms_function222` values('59','1','0','微酒吧','Jiuba','微酒吧','1','1');");
E_D("replace into `imicms_function222` values('60','1','0','微食品','Shipin','微食品','1','1');");
E_D("replace into `imicms_function222` values('61','1','0','微旅游','Lvyou','微旅游','1','1');");
E_D("replace into `imicms_function222` values('62','1','0','微健身','Jianshen','微健身','1','1');");
E_D("replace into `imicms_function222` values('63','1','0','微花店','Huadian','微花店','1','1');");
E_D("replace into `imicms_function222` values('64','1','0','微教育','Jiaoyu','微教育','1','1');");
E_D("replace into `imicms_function222` values('65','1','0','微政务','Zhengwu','微政务','1','1');");
E_D("replace into `imicms_function222` values('66','1','0','微调研','Diaoyan','微调研','1','1');");
E_D("replace into `imicms_function222` values('67','1','0','微WIFI','rippleos','微WIFI','2','1');");
E_D("replace into `imicms_function222` values('68','1','0','在线客服','Kefu','在线客服','2','1');");
E_D("replace into `imicms_function222` values('69','7','0','微信墙','wxq','微信墙','1','1');");

require("../../inc/footer.php");
?>