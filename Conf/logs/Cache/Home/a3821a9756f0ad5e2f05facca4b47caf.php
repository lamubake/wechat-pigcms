<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($f_siteTitle); ?></title>
<meta name="keywords" content="<?php echo ($f_metaKeyword); ?>"/>
<meta name="description" content="<?php echo ($f_metaDes); ?>"/>
<link href="<?php echo RES;?>/css/com.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src='<?php echo RES;?>/js/jquery-1.6.2.min.js' ></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.logo').mousedown(function(){
        $(this).css('margin-top','11px');
        $(this).css('margin-left','1px');
    });	
});
</script>

<script>

	var pc_style = ""

	var browser = {

	versions: function () {

	var u = navigator.userAgent, app = navigator.appVersion;

	return {

	trident: u.indexOf('Trident') > -1,

	presto: u.indexOf('Presto') > -1,

	webKit: u.indexOf('AppleWebKit') > -1,

	gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,

	mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/) && u.indexOf('QIHU') && u.indexOf('Chrome') < 0,

	ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),

	android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,

	iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1,

	iPad: u.indexOf('iPad') > -1,

	webApp: u.indexOf('Safari') == -1,

	ua: u

	};

	}(),

	language: (navigator.browserLanguage || navigator.language).toLowerCase()

	}

	if (browser.versions.mobile && !browser.versions.iPad) {

	this.location = "<?php echo C('site_url');?>/index.php?g=Wap&m=Index&a=index&token=<?php echo ($token); ?>&wecha_id={wechat_id}";

	}

</script>


<script type="text/javascript" src="<?php echo RES;?>/js/carouse.js"></script>
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

			<!-- banner start -->
			<div class="banner">
				<ul class="list-span fn-clear">
					<li class="banner-img" style="display:none">
						<img class="loadimg" src="<?php echo RES;?>/images/pic1.jpg" alt="商城App" width="1900" height="519" />
					</li>
                                        <li class="banner-img" style="display:none">
						<img class="loadimg" src="<?php echo RES;?>/images/pic0.jpg" alt="<?php echo ($f_siteName); ?>" width="1900" height="519" />
					</li>
					<li class="banner-img" style="display:none">
						<img class="loadimg" src="<?php echo RES;?>/images/pic2.jpg" alt="商城客户端" width="1900" height="519" />
					</li>
					<li class="banner-img" style="display:none">
						<img class="loadimg" src="<?php echo RES;?>/images/pic3.jpg" alt="移动商城" width="1900" height="519" />
					</li>
				</ul>
				<div id="loading" style="text-align: center; width: 100%; height: 520px; line-height: 520px; font-size:36px; margin-top: -520px;"><img src="<?php echo RES;?>/images/loading.gif" alt="Loading" width="32" height="32" retina="true"></div>
				<div class="list-cont fn-clear"> 
					<div class="list-info">
						<div class="banner-word banner-word-init">
							<p>强化品牌特性，<br>缔造移动互联网时代新品牌形象。</p>
						</div>
						<img class="banner-phone-init banner-phone" src="<?php echo RES;?>/images/phone1.png" alt="移动商城" height="640" width="303" retina="true">
					</div>
                                        <div class="list-info">
						<div class="banner-word banner-word-init">
							<p>整合完备的客户关系管理和<br>客服成本控制机制，<br>建立官方权威客服中心。</p>
						</div>
						<img class="banner-phone-init banner-phone" src="<?php echo RES;?>/images/phone0.png" alt="移动商城" height="642" width="303" retina="true">
					</div>
					<div class="list-info">
						<div class="banner-word banner-word-init">
							<p>连结线上线下，<br>轻松实现O2O创新营销。</p>
						</div>
						<img class="banner-phone-init banner-phone" src="<?php echo RES;?>/images/phone2.png" alt="移动商城" height="640" width="303" retina="true">
					</div>
					<div class="list-info">
						<div class="banner-word banner-word-init">
							<p>快速地搭建移动电子商务平台，<br>低成本地开展移动营销。</p>
						</div>
						<img class="banner-phone-init banner-phone" src="<?php echo RES;?>/images/phone3.png" alt="移动商城" height="640" width="303" retina="true">
					</div>
					<span id="pre" class="carouse-pre" retina="true"><<</span>
					<span id="next" class="carouse-next" retina="true">>></span>
					<div id="carouse-btn" class="carouse-btn" retina="true">
						<span id="cur-ic" class="cur-img"><a href="#">1</a></span>
						<span><a href="#">2</a></span>
						<span><a href="#">3</a></span>
                        <span><a href="#">4</a></span>
					</div>
				</div>
			</div>
			<!-- banner end -->
			<!-- product-info start -->
			<div class="product-bg bdline">
				<div class="product-cont">
					<div class="product-info">
						<p><?php echo ($f_siteName); ?><br>市场上最具性价比的企业微信营销平台，能够帮助<br>企业快速地进行微信营销，并节省大量的成本。</p>
						<a href="<?php echo U('Index/reg');?>" target="_blank" retina="true"><img src="<?php echo RES;?>/images/btn1.jpg" width="212" id="tiyan"></a>
					</div>
				</div>
			</div>
		</div>
		<!-- top end -->
<div class="indexcon">
<ul>
<li><img src="<?php echo RES;?>/images/demo1.png" height="205" class="f_l"></li>
<li><img src="<?php echo RES;?>/images/demo2.png" height="205"></li>
<li><img src="<?php echo RES;?>/images/demo3.png" height="205" class="f_r"></li>
</ul>
</div><!--end indexcon-->
<div class="conmenter">
<div class="conmentcon">
<div class="conbox">
<p>在全民微信的时代，我们找到了<?php echo ($f_siteName); ?>，双方的合作非常愉快，
这是一个用心服务的团队，帮助我们搭建了一个企业微信营销平台，让我们快速地开展移动营销。
<?php echo ($f_siteName); ?>的系统操作特别方便，希望未来有更多深入的合作。</p>
<div class="people"><img src="<?php echo RES;?>/images/ren1.jpg" class="f_l"><span><span class="hisname">Cindy</span><span class="profiler">香港永泰行市场负责人</span></span></div>
</div><!--end conbox-->
<div class="conbox">
<p>目前市面上的微信营销软件真的是见了太多了，也许现在大家真正比拼的就是细节了，
<?php echo ($f_siteName); ?>是个诚意十足的产品，可视化的操作界面，多套精美的网站模板以及自助式组件组合方式等等，
处处体现着产品的用心。赞！</p>
<div class="people"><img src="<?php echo RES;?>/images/ren2.jpg" class="f_l"><span><span class="hisname">张晓华</span><span class="profiler">深圳聆诗设计设计总监</span></span></div>
</div><!--end conbox-->
<div class="conbox">
<p><?php echo ($f_siteName); ?>是基于微信公众平台做二次开发，与微信完美对接，交互体验完整。
其中还整合了互动营销小游戏，在我们做活动策划时，<?php echo ($f_siteName); ?>的运营团队还提了不少专业的建议，
使我们活动一上来，就捕获了众多粉丝的心。</p>
<div class="people"><img src="<?php echo RES;?>/images/ren3.jpg" class="f_l"><span><span class="hisname">刘美佳</span><span class="profiler">曼妮莎女装运营经理</span></span></div>
</div><!--end conbox-->
</div><!--end conmentcon-->
</div><!--end conmenter-->
<div id="header" class="bg4"><div class="dibg"><div class="dimg"><img src="<?php echo RES;?>/images/dimg.png"></div></div></div>
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
<script type="text/javascript">
  var imgdefereds = [];
  $('.banner-img img').each(function(){
    imgdefereds.push($.loadImage(this.src));
  });
  $('.banner-phone-init').each(function(){
    imgdefereds.push($.loadImage(this.src));
  });
  $.when.apply(null, imgdefereds).done(function(){
    $('#loading').hide();
  	$.slideImg('#carouse-btn','.list-span');
  });
  $('#tiyan').mouseenter(function(){
      $(this).attr('src','<?php echo RES;?>/images/btn2.jpg');
  });
  $('#tiyan').mouseleave(function(){
      $(this).attr('src','<?php echo RES;?>/images/btn1.jpg');
  });
  $('#tiyan').mousedown(function(){
      $(this).attr('src','<?php echo RES;?>/images/btn3.jpg');
  });
</script>
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