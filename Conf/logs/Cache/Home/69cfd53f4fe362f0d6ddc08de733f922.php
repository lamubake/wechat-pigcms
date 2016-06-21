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

			<div class="bannerbg bj2"><div class="bannercon"><span>帮助中心</span><br>深度整合行业特性，提供专业解决方案</div></div>
		</div>
		<!-- top end -->
<div id="mainer" class="bg7">
<div class="leftcon f_l"></div>
<div class="case">
<div class="leftnav">
<h3>帮助中心</h3>
<ul id="leftmenu">
<li class="active"><a href="#">第一步</a></li>
<li><a href="#">第二步</a></li>

</ul>
</div><!--end leftnav-->
<div class="casecon f_l">
<div id="leftmenucon">
<!--第一步-->
<div>
<div class="solutionbox">
<h2>如何为微信公众号配置接口？</h2>
<p>请务必认真阅读以下2步内容，才能更有效的完成配置工作，有疑问的请联系QQ：<?php echo C('site_qq');?>提问。<br/>
<?php if($_GET['token'] != ''): ?>[你的接口URL是]：<br>
<font color="red"><?php echo C('site_url');?>/index.php?g=Home&m=Weixin&a=index&token=<?php echo $_GET['token']; ?></font><br>[若失败请用如下URL]：
<font color="red"><?php echo C('site_url');?>/index.php/api/<?php echo $_GET['token']; ?></font>
<br>您的token是：<font color="red"><?php echo $_GET['token']; ?></font><?php endif; ?></p>

<br>
<h2>第一步、在<?php echo ($f_siteName); ?>绑定你的微信公众号。</h2>

 <p>1、注册并登录<a href="<?php echo U('Index/login');?>"><?php echo ($f_siteName); ?>接口平台</a></p>
 <p>2、添加公众号 → 功能管理 → 勾选要开启的功能</p>

<img src="<?php echo STATICS;?>/help/help01.jpg">
<img src="<?php echo STATICS;?>/help/help0.jpg">
</div><!--end casebox-->
</div><!--end-->
<!--第二步-->
<div>
<div class="solutionbox">
<h2>第二步、到微信公众平台设置接口。</h2>
<p>1、登录 <a href="http://mp.weixin.qq.com/">微信公众平台</a>（<a href="http://mp.weixin.qq.com/">http://mp.weixin.qq.com/</a>），登录之后点击左侧菜单的“开发者中心”，下图红框中所示。</p>
<img src="<?php echo STATICS;?>/help/1.jpg">
<p>2、进入开发者中心之后，点击下图中绿色的“启用”按钮（<span style="color:red">如果按钮是红色的就忽略这个直接进入下一步</span>），然后在弹出的页面里点击确定，然后点击下图中的“修改配置”（<span style="color:red">订阅号的开发者中心可能没有开发者ID、AppId和AppSecret，这属于正常的</span>）</p>
<img src="<?php echo STATICS;?>/help/2.jpg">
<p>3、按照下面要求填写接口配置信息，填写后提交即可</p>
<?php if($_GET['token'] == ''): ?><p>比如你<?php echo ($f_siteName); ?>平台上的地址是<?php echo ($f_siteUrl); ?>/index.php/api/demo</p>
<p>那么URL就是<?php echo ($f_siteUrl); ?>/INDEX.PHP/api/demo</p>
<?php else: ?>
<p>你的URL是 <font color="red"><?php echo ($f_siteUrl); ?>/index.php/api/<?php echo $_GET['token']; ?></font></p><?php endif; ?>
<p>Token填写 <font color="red"><?php echo $_GET['token']; ?></font></p>
<p><img src="<?php echo STATICS;?>/help/5.jpg"</p><br>
<p>4、在手机上用微信给你的公众号输入"帮助"，如果能接收到信息就接入成功，否则请按照以上步骤重新配置</p><br>									
</div><!--end imgcon-->
</div><!--end casebox-->
</div><!--end-->
<!--案例10-->
<div></div><!--end-->
</div><!--leftmenucon-->
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