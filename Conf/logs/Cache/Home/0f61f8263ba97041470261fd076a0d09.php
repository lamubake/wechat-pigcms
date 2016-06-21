<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo ($f_siteName); ?>-<?php echo ($f_siteTitle); ?></title>                                                                                                      
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="<?php echo ($f_metaDes); ?>"/>
<meta name="keywords" content="<?php echo ($f_metaKeyword); ?>"/>
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

			<div class="bannerbg bj5"><div class="bannercon" style="padding-left:20px;"><span>关于我们</span><br>专注于以创新为核心的互联网及移动应用开发</div></div>
		</div>
		<!-- top end -->
<div id="mainer" class="bg4">
<div class="about">
<h2><?php echo ($f_siteName); ?>是什么</h2>
<p>微信本身是简洁的通讯工具，通过开放的API接口,让一个超过6亿用户（高速增长中）的即时通讯工具，通过二次开发可以成为给客户提供更好的服务的CRM工具，以及效果惊人的客户引流渠道。而<?php echo ($f_siteName); ?>，就是一个针对微信公众账号进行技术开发和产品设计的第三方平台，我们的目的是把企业微信变成一个与众不同的、有针对性的会员服务和营销推广工具。</p>
<p><?php echo ($f_siteName); ?>，整合了完备的客户关系管理系统和客服成本控制机制，让企业迅速拥有高效、低成本的微信呼叫中心；同时也整合了品牌微网站、移动商城、CRM系统等产品，打造前所未用的客户服务体验，助力企业进入移动营销时代。</p>
<p>通过<?php echo ($f_siteName); ?>，不懂技术的个人或企业通过简单的配置，即可拥有强大的功能。它是一个快速的，可视化的无线建站工具，在几分钟内可以完成微网站的配置，还提供了在线抽奖活动（大转盘，刮刮卡，优惠券），在线投票，在线报名，在线预约，在线试驾，在线预定，图文回复，文本回复，LBS位置回复，地图，电话，视频，相册、会员卡等模块，还支持在线支付、商品管理、订单管理等商城功能，我们的系统几乎做到每周更新，有更多精彩的功能等您来体验。</p>
<br><br>
<h2>我们的价值观</h2>
<p>执行简洁透明，解决问题不仅仅靠创意、信任最大、客户第一位，创新来源于业务的纯熟、敢赚钱在客户之后。</p>
<br><br>
<h2>联系我们</h2>
<p><?php echo ($f_siteName); ?>信息科技有限公司<br>
地址：<?php echo C('address');?><br>
QQ：<?php echo C('site_qq');?><br>
网址：<?php echo C('site_url');?></p>
<div class="contact">
<img src="<?php echo RES;?>/images/ewm2.jpg" class="f_l">
<div class="contactcon f_l">
<img src="<?php echo RES;?>/images/phone.jpg" class="f_l">
<span>
<p>服务热线</p>
<h3><?php echo C('site_mp');?></h3>
周一至周六：AM 09:00-PM 18:00
</span>
</div><!--end contactcon-->
</div><!--end contact-->
<br>
</div><!--end about-->
</div><!--end mainer-->
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