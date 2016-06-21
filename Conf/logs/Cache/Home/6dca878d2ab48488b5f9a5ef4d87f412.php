<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
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

			<div class="bannerbg bj4"><div class="bannercon" style="padding-left:20px;"><span>渠道合作</span><br><?php echo ($f_siteName); ?>面向全国诚聘渠道代理商</div></div>
		</div>
		<!-- top end -->
<div id="mainer" class="bg4">
<div class="channel">
<h2><?php echo ($f_siteName); ?>代理优势</h2>
<ul>
<li class="oneli"><span class="titler">合作运营的创业平台</span><span class="contenter">成为<?php echo ($f_siteName); ?>的地区合作伙伴，不仅仅是一个代理商，更是一个可以共同创业、共同成长的整体。和<?php echo ($f_siteName); ?>一起成长，快速占领本地微信营销市场。</span></li>
<li><span class="titler">巨大的利润空间</span><span class="contenter">低投资，高回报，利润空间高达300%，做一单成一单。市场有多大，利润就有多大。</span></li>
<li class="oneli"><span class="titler">极具特色和竞争力的产品</span><span class="contenter"><?php echo ($f_siteName); ?>团队凭借多年的互联网经验，针对客户需求做个性化微信营销定制服务，充分保证产品的行业内的竞争力，进而保障代理商在市场中的竞争力。</span></li>
<li><span class="titler">完善的支持体系</span><span class="contenter"><?php echo ($f_siteName); ?>为代理商提供运营过程中客服、技术、培训等一系列完善的支持，让代理商可以全身心的投入到市场开发中。</span></li>
</ul>
<br>
<h2>代理条件</h2>
<p>1、认同微信的发展趋势及美好前景，认同总部对形势的判断;</p> 
<p>2、具备从业互联网基础应用业务的基本能力，包括营销、服务的基本条件和素质；</p>
<p>3、接受<?php echo ($f_siteName); ?>业务监督、市场指导和业绩考核，并遵守协议相互约定的条款；</p>
<p>4、具备一定的本地化客户资源和客户服务能力。</p>
<br><br>
<h2><?php echo ($f_siteName); ?>给予合作伙伴的支持</h2>
<ul>
<li class="oneli"><span class="titler">产品技术支持</span>
<span class="contenter">
<p>1、提供产品系统支持，<?php echo ($f_siteName); ?>使用操作培训</p>
<p>2、提供现场或远程技术培训，让合作伙伴掌握操作流程与技术</p>
<p>3、不定期召集相关技术人员到深圳总部接受产品培训</p></span></li>
<li><span class="titler">宣传物料支持</span>
<span class="contenter">
<p>1、提供产品宣传资料，操作手册等资料；</p>
<p>2、共享已有各行业客户策划方案</p></span></li>
</ul>
<br><br>
<h2>代理流程</h2>
<div class="liucheng">
<div class="liucon" style="width:250px;">
<div class="tuwen">
<img src="<?php echo RES;?>/images/tu1.jpg">
<h3>在线提交代理申请</h3>
<p>1、提交企业信息<br>2、获取合作资料</p>
</div>
</div><!--end liucon-->
<div class="liucon">
<div class="tuwen">
<img src="<?php echo RES;?>/images/tu2.jpg">
<h3 style="padding-left:14px;">合作资质审核</h3>
<p style="padding-left:14px;">1、目标市场销售策略<br>2、目标市场确认</p>
</div>
</div><!--end liucon-->
<div class="liucon">
<div class="tuwen">
<img src="<?php echo RES;?>/images/tu3.jpg">
<h3 style="padding-left:8px;">签署合作协议</h3>
<p style="padding-left:8px;">1、签署正式合作协议<br>2、免加盟费，少量预付款</p>
</div>
</div><!--end liucon-->
<div class="liucon nobg" style="width:226px;">
<div class="tuwen">
<img src="<?php echo RES;?>/images/tu4.jpg">
<h3>开展区域市场业务</h3>
<p>1、远程、现场培训指导<br>
2、产品技术支持、宣传物料支持</p>
</div>
</div><!--end liucon-->
</div><!--end liucheng-->
<br><br><br>
<h2>立即联系</h2>
<div class="connect">
<img src="<?php echo RES;?>/images/phone.jpg" class="f_l">
<span>
<p>服务热线</p>
<h3><?php echo C('site_mp');?></h3>
周一至周六：AM 09:00-PM 18:00
</span>
<a href="<?php echo U('Index/reg');?>"><img src="<?php echo RES;?>/images/online1.jpg" class="online" width="210"></a>
</div><!--end connect-->
</div><!--end channel-->
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
<script type="text/javascript">
  $('.online').mouseenter(function(){
      $(this).attr('src','<?php echo RES;?>/images/online2.jpg');
  });
  $('.online').mouseleave(function(){
      $(this).attr('src','<?php echo RES;?>/images/online1.jpg');
  });
  $('.online').mousedown(function(){
      $(this).attr('src','<?php echo RES;?>/images/online3.jpg');
  });
</script>