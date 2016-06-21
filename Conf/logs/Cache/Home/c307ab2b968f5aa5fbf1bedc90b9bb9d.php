<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo ($f_siteName); ?>-<?php echo ($f_siteTitle); ?></title>                                                                                                      
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="<?php echo ($f_metaDes); ?>"/>
<meta name="keywords" content="<?php echo ($f_metaKeyword); ?>"/>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,initial-scale=1" />
<link href="<?php echo RES;?>/css/com.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src='<?php echo RES;?>/js/jquery-1.6.2.min.js' ></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.logo').mousedown(function(){
        $(this).css('margin-top','11px');
        $(this).css('margin-left','1px');
    });	
    /*leftmenu*/
    function tabs(tabTit,active,tabCon){
	$(tabTit).children().eq(0).addClass(active);
        $(tabCon).children().eq(0).show().siblings().hide();
        $(tabTit).children().click(function(){
           $(this).addClass(active).siblings().removeClass(active);
           var index = $(tabTit).children().index(this);
           $(tabCon).children().eq(index).show().siblings().hide();
        });
    }
    tabs('#leftmenu','active','#leftmenucon');
});
</script>
<link href="favicon.ico" rel="Shortcut Icon"></link>
</head>
<body>
<!-- wrapper start -->
	<div class="wrapper">
		<!-- top start -->
		<div class="top">
			<!-- nav start -->
			<div class="nav">
				<div class="nav-inner">	
					<a class="logo f_l" href="<?php echo U('Home/Index/index');?>"><img src="<?php echo ($f_logo); ?>" alt="<?php echo ($f_siteName); ?>-多用户微信营销服务平台"></a>
					<div class="nav-list f_r">
					        <h2><a href="<?php echo U('Home/Index/index');?>" <?php if((ACTION_NAME) == "index"): ?>class="active"<?php endif; ?> >首页</a></h2>
							<h2><a href="<?php echo U('Home/Index/fc');?>" <?php if((ACTION_NAME) == "fc"): ?>class="active"<?php endif; ?> >功能介绍</a></h2>
							<h2><a href="<?php echo U('Home/Index/common');?>" <?php if((ACTION_NAME) == "common"): ?>class="active"<?php endif; ?> >客户案例</a></h2>
							<h2><a href="<?php echo U('Home/Index/price');?>" <?php if((ACTION_NAME) == "price"): ?>class="active"<?php endif; ?> >渠道合作</a></h2>
							<h2><a href="<?php echo U('Home/Index/help');?>" <?php if((ACTION_NAME) == "help"): ?>class="active"<?php endif; ?>  >帮助中心</a></h2>
							<h2><a href="<?php echo U('Home/Index/about');?>" <?php if((ACTION_NAME) == "about"): ?>class="active"<?php endif; ?>  >关于我们</a></h2>
                         <h2><a href="<?php echo C('server_discuz');?>" target="_blank">官方网站</a></span></h2>
					  <?php if($_SESSION[uid]==false): ?><span><a href="<?php echo U('Home/Index/login');?>">登录</a>&nbsp;
						<a class="sqty" href="<?php echo U('Home/Index/reg');?>">申请体验</a></span>
					   <?php else: ?>
					    <span><a href="<?php echo U('User/Index/index');?>">管理</a>&nbsp;
						<a href="javascript:void(0)" class="sqty" onClick="Javascript:window.open('<?php echo U('System/Admin/logout');?>','_top')" >安全退出</a><?php endif; ?>						
					</div>
				</div>
			</div>
                        <div class="ding yincang" id="ding"></div>

			<div class="bannerbg bj3"><div class="bannercon"><span>客户案例</span><br>已经有352家企业选择<?php echo ($f_siteName); ?></div></div>
		</div>
		<!-- top end -->
<div id="mainer" class="bg7">
<div class="leftcon f_l"></div>
<div class="case">
<div class="leftnav">
<h3>客户案例</h3>
<ul id="leftmenu">
<li class="active"><a href="#">服装/纺织</a></li>
<li><a href="#">餐饮/酒店</a></li>
<li><a href="#">休闲/酒吧/KTV</a></li>
<li><a href="#">广告/影视</a></li>
<li><a href="#">婚庆/摄影</a></li>
<li><a href="#">工程/机械/能源</a></li>
<li><a href="#">汽车服务</a></li>
<li><a href="#">娱乐/游戏</a></li>
<li><a href="#">贸易/零售</a></li>
<li><a href="#">企业/媒体</a></li>
</ul>
</div><!--end leftnav-->
<div class="casecon f_l">
<div id="leftmenucon">
<!--案例1-->
<div>
<div class="casebox">
<h3>曼妮莎女装</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/manisha001.jpg" class="f_l">
<img src="<?php echo RES;?>/images/manisha002.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>“曼妮莎女装”品牌设计理念源自江南的风情和生活文化。春装清新亮丽、夏装婉约飘逸、秋装时尚大气、冬装简约休闲。</p>
<p>在经营理念上，以市场需求为出发点，以服务市场和消费者为宗旨，及时、准确、快速地提供货品。在市场消费定位上，主要针对25-35岁的城市女性。</p>
</div><!--end casebox-->
</div><!--end-->
<!--案例2-->
<div>
<div class="casebox">
<h3>唯客港式茶餐厅</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/weike001.jpg" class="f_l">
<img src="<?php echo RES;?>/images/weike002.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>1998年，第一家唯客港式茶餐厅诞生于香港九龙，一年后，于深圳开了第一家分店，其地道的香港味道深受深圳人的欢迎，时至今日，唯客港式茶餐厅已在深圳成开了十家分店，而且一直在发展壮大中。</p>
<p>唯客有厨师们都是具备有创新精神的，经过不断地尝试，已发明了很多道独特的招牌菜。</p>
</div><!--end casebox-->
<div class="casebox">
<h3>热意餐厅</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/case1.jpg" class="f_l">
<img src="<?php echo RES;?>/images/ma1.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>热意无国界料理餐厅以创意融合菜流行于众食客间。热意的美食融合中国各地、意大利、法国、泰国、韩国等国家的美食文化，每道菜品都独具匠心。写意美味，热意浓浓。</p>
</div><!--end casebox-->  
</div><!--end-->
<!--案例3-->
<div>
<div class="casebox">
<h3>糖果量贩式KTV</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/tangguo001.jpg" class="f_l">
<img src="<?php echo RES;?>/images/tangguo002.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>糖果量贩KTV自2007年创立以来，致力成为两岸KTV娱乐市场第一领导品牌，所秉持的不但是硬设备的研发与创新；软 件服务的诚挚与精致，更是娱乐生活型态的前瞻风范。到目前为止，糖果量贩KTV在中国大陆总计已经开了18家门市，糖果量贩KTV以科技、娱乐、人性化元 素，融入聚会需求、欢唱事业与顶级休闲场域，成为21世纪休闲流行趋势。</p>
</div><!--end casebox-->
</div><!--end-->
<!--案例4-->
<div>
</div><!--end-->
<!--案例5-->
<div>
</div><!--end-->
<!--案例6-->
<div>
<div class="casebox">
<h3>香港永泰行</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/yongtaihang001.jpg" class="f_l">
<img src="<?php echo RES;?>/images/yongtaihang002.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>永泰行始创于一九六八年，主要从事进口金属加工刀具和各种研磨材料品牌代理与销售业务。旗下代理诸多日欧知名品牌，多数为中国地区之独家总代理。研磨产品种类丰富，涉及电子、钟表、制模、家具、汽车、船舶、航天等行业。</p>
</div><!--end casebox-->  
</div><!--end-->
<!--案例7-->
<div>
<div class="casebox">
<h3>新奇特车业</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/case3.jpg" class="f_l">
<img src="<?php echo RES;?>/images/ma3.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>近年来新奇特创造了斐然的业绩，经营网络遍布全国各地。目前，在全国各大中型城市已拥有40多家大型的车业服务广场，其中最大的经营面积达10000多平方米，并在这些中心城市开设了多家不同级别的专营店。新奇特车业服务为有车族提供维修保养、美容装饰、音响改装、轮胎铝圈和汽车用品销售等全方位的服务，改变人们对汽车维修保养的传统观念，融合“人车新生活”的概念，引领汽车售后消费新时尚。</p>
</div><!--end casebox-->  
</div><!--end-->
<!--案例8-->
<div>  
</div><!--end-->
<!--案例9-->
<div>
<div class="casebox">
<h3>富安娜鲜花店</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/fuanna001.jpg" class="f_l">
<img src="<?php echo RES;?>/images/fuanna002.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>富安娜鲜花店位于深圳市福田区莲花路，地铁蛇口线景田站的F出口，交通便利！ 本花店经营鲜花行业十多年，拥有成熟健全的配送体系，专车专人配送，拥有一批优秀的插花师及专业鲜花速递人员和场地布置人员，是一家大型的实体鲜花店，专业承办各类型的庆典活动。</p>
</div><!--end casebox-->
<div class="casebox">
<h3>张裕葡萄酒</h3>
<div class="imgcon">
<img src="<?php echo RES;?>/images/zhangyu001.jpg" class="f_l">
<img src="<?php echo RES;?>/images/zhangyu002.jpg" class="erwei f_r">
</div><!--end imgcon-->
<p>1892年，著名的爱国侨领张弼士先生为了实现"实业兴邦"的梦想，先后投资300万两白银在烟台创办了"张裕酿酒公司"，中国葡萄酒工业化的序幕由此拉开。</p>
<p>经过一百多年的发展，张裕已经发展成为中国乃至亚洲最大的葡萄酒生产经营企业。1997年和2000年张裕B股和A股先后成功发行并上市，2002 年7月，张裕被中国工业经济联合会评为"最具国际竞争力向世界名牌进军的16家民族品牌之一"。在中国社会科学院等权威机构联合进行的2004年度企业竞 争力监测中，张裕综合竞争力指数居位列中国上市公司食品酿酒行业的第八名，成为进入前十强的唯一一家葡萄酒企业。</p>
</div><!--end casebox-->
</div><!--end-->
<!--案例10-->
<div></div><!--end-->
</div><!--leftmenucon-->
<div class="casebox">
<h3>更多相关案例…</h3>
<ul>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic1.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic2.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic3.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic4.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic5.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic6.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic7.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic8.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic9.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic10.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic11.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic12.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic13.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic14.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic15.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic16.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic17.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic18.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic19.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic20.jpg"></a></li>
<li><a href="#"><img src="<?php echo RES;?>/images/casepic21.jpg"></a></li>
</ul>
</div><!--end casebox-->
<br><br>
</div><!--end casecon-->
</div><!--end case-->
</div><!--end mainer-->
<div id="header" style="z-index:30; position:relative; margin-top: -14px;"><div class="dibg"><div class="dimg"><img src="<?php echo RES;?>/images/dimg.png"></div></div></div>
<div id="footer">
    <div class="di">
        <span class="banQ f_l">&copy;<?php echo ($f_siteName); ?>&nbsp;&nbsp;</span>
	    <span class="downav f_l">
	    <a href="<?php echo U('Home/Index/index');?>">首页</a>
		<a href="<?php echo U('Home/Index/fc');?>">功能介绍</a>
		<a href="<?php echo U('Home/Index/common');?>">客户案例</a>
		<a href="<?php echo U('Home/Index/price');?>">渠道合作</a>
        <a href="<?php echo U('Home/Index/help');?>">帮助中心</a>
		<a href="<?php echo U('Home/Index/about');?>">关于我们</a></span>
		
     <span class="houT f_r">
    <a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo C('ipc');?></a></span></div>
</div>

</div><!--end footer-->
</div><!-- wrapper end -->
</body>
</html>
<script>
$.fn.smartFloat = function() {
	var position = function(element) {
		$(window).scroll(function() {
			$('#ding').removeClass('yincang');
			var scrolls = $(this).scrollTop();
				if (window.XMLHttpRequest) {
					element.css({
						position: "fixed",
						top:0
					});	
				} else {
					element.css({
						top: scrolls
					});	
				}
		});
};
	return $(this).each(function() {
		position($(this));						 
	});
};
//绑定
$(".nav").smartFloat();
</script>
<script type="text/javascript" src="<?php echo RES;?>/js/scrolltopcontrol.js"></script>