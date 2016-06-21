<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> <?php echo ($f_siteTitle); ?> <?php echo ($f_siteName); ?></title>
<meta name="keywords" content="<?php echo ($f_metaKeyword); ?>" />
<meta name="description" content="<?php echo ($f_metaDes); ?>" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<script>var SITEURL='';</script>
<link rel="stylesheet" type="text/css" href="./tpl/User/default/common/css/style_2_common.css?BPm" />
<script src="<?php echo RES;?>/js/common.js" type="text/javascript"></script>
<link rel=​"stylesheet" type=​"text/​css" href=​"/​tpl/​User/​default/​common/​css/style_tanceng.css?BPm" />
<link rel=​"stylesheet" type=​"text/​css" href=​"/tpl/User/default/common/css/animate.css?BPm" />
</head>
<body id="nv_member" class="pg_CURMODULE">
<div class="topbg">
<div class="top">
<div class="toplink">
<style>
<?php if($usertplid != 0): ?>.topbg{background:url(<?php echo ($staticPath); ?>/tpl/static/newskin/images/top.gif) repeat-x; height:101px; /*box-shadow:0 0 10px #000;-moz-box-shadow:0 0 10px #000;-webkit-box-shadow:0 0 10px #000;*/}
.top {
    margin: 0px auto; width: 988px; height: 101px;
}

.top .toplink{ height:30px; line-height:27px; color:#999; font-size:12px;}
.top .toplink .welcome{ float:left;}
.top .toplink .memberinfo{ float:right;}
.top .toplink .memberinfo a{ color:#999;}
.top .toplink .memberinfo a:hover{ color:#F90}
.top .toplink .memberinfo a.green{ background:none; color:#0C0}

.top .logo {width: 990px; color: #333; height:70px; font-size:16px;}
.top h1{ font-size:25px;float:left; width:230px; margin:0; padding:0; margin-top:6px; }
.top .navr {WIDTH:750px; float:right; overflow:hidden;}
.top .navr ul{ width:850px;}
.navr li {text-align:center; float: right; height:70px; font-size:1em; width:103px; margin-right:5px;}
.navr li a {width:103px; line-height:70px; float: left; height:100%; color: #333; font-size: 1em; text-decoration:none;}
.navr li a:hover { background:#ebf3e4;}
.navr li.menuon {background:#ebf3e4; display:block; width:103px;}
.navr li.menuon a{color:#333;}
.navr li.menuon a:hover{color:#333;}
.banner{height:200px; text-align:center; border-bottom:2px solid #ddd;}
.hbanner{ background: url(img/h2003070126.jpg) center no-repeat #B4B4B4;}
.gbanner{ background: url(img/h2003070127.jpg) center no-repeat #264C79;}
.jbanner{ background: url(img/h2003070128.jpg) center no-repeat #E2EAD5;}
.dbanner{ background: url(img/h2003070129.jpg) center no-repeat #009ADA;}
.nbanner{ background: url(img/h2003070130.jpg) center no-repeat #ffca22;}
<?php else: ?>

.topbg{BACKGROUND-IMAGE: url(<?php echo ($staticPath); echo ltrim(RES,'.');?>/images/top.png);BACKGROUND-REPEAT: repeat-x; height:124px; box-shadow:0 0 10px #000;-moz-box-shadow:0 0 10px #000;-webkit-box-shadow:0 0 10px #000;}
.top {
    MARGIN: 0px auto; WIDTH: 988px; HEIGHT: 124px;
}

.top .toplink{ height:27px; line-height:27px; color:#999; font-size:12px;}
.top .toplink .welcome{ float:left;}
.top .toplink .memberinfo{ float:right;}
.top .toplink .memberinfo a{ color:#999;}
.top .toplink .memberinfo a:hover{ color:#F90}
.top .toplink .memberinfo a.green{ background:none; color:#0C0}

.top .logo {WIDTH: 990px;COLOR: #333; height:70px;  FONT-SIZE:16px; PADDING-TOP:25px}
.top h1{ font-size:25px; margin-top:20px; float:left; width:230px; margin:0; padding:0;}
.top .navr {WIDTH:750px; float:right; overflow:hidden; margin-top:10px;}
.top .navr ul{ width:850px;}
.navr LI {TEXT-ALIGN:center;FLOAT: left;HEIGHT:32px;FONT-SIZE:1em;width:103px; margin-right:5px;}
.navr LI A {width:103px;TEXT-ALIGN:center; LINE-HEIGHT:30px; FLOAT: left;HEIGHT:32px;COLOR: #333; FONT-SIZE: 1em;TEXT-DECORATION: none
}
.navr LI A:hover {BACKGROUND: url(<?php echo ($staticPath); echo ltrim(RES,'.');?>/images/navhover.gif) center no-repeat;color:#009900;}
.navr LI.menuon {BACKGROUND: url(<?php echo ($staticPath); echo ltrim(RES,'.');?>/images/navon.gif) center no-repeat; display:block;width:103px;HEIGHT:32px;}
.navr LI.menuon a{color:#FFF;}
.navr LI.menuon a:hover{BACKGROUND: url(img/navon.gif) center no-repeat;}
.banner{height:200px; text-align:center; border-bottom:2px solid #ddd;}
.hbanner{ background: url(img/h2003070126.jpg) center no-repeat #B4B4B4;}
.gbanner{ background: url(img/h2003070127.jpg) center no-repeat #264C79;}
.jbanner{ background: url(img/h2003070128.jpg) center no-repeat #E2EAD5;}
.dbanner{ background: url(img/h2003070129.jpg) center no-repeat #009ADA;}
.nbanner{ background: url(img/h2003070130.jpg) center no-repeat #ffca22;}<?php endif; ?>
</style>
<div class="welcome">欢迎使用<?php echo ($f_siteName); ?>!</div>
    <div class="memberinfo"  id="destoon_member">   
        <?php if($_SESSION[uid]==false): ?><a href="<?php echo U('Index/login');?>">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="<?php echo U('Index/login');?>">注册</a>
            <?php else: ?>
            你好,<a href="/#" hidefocus="true"  ><span style="color:red"><?php echo (session('uname')); ?></span></a>（uid:<?php echo (session('uid')); ?>）
            <a href="/#" onclick="Javascript:window.open('<?php echo U('System/Admin/logout');?>','_blank')" >退出</a><?php endif; ?>   
    </div>
</div>
    <div class="logo">
        <div style="float:left"><h1><a href="/" title="多用户微信营销服务平台"><img src="<?php echo ($f_logo); ?>" height="55" /></a></h1></div>
            
            <div class="navr">
            <ul id="topMenu">
                <li style="width:85px"></li>
                <?php if($typsz['help'] == 1): if(is_array($zdydh)): $i = 0; $__LIST__ = $zdydh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pigvo): $mod = ($i % 2 );++$i; if($pigvo['type'] == 6): ?><li <?php if((ACTION_NAME) == "help"): ?>class="menuon"<?php endif; ?> <?php if($pigvo['dspl'] == 1): ?>style="display:none;"<?php endif; ?>>
                    <a <?php if($pigvo['open'] == '1'): ?>target="_blank"<?php endif; ?> href="<?php if($pigvo['url'] == ''): echo U('Home/Index/help'); else: echo ($pigvo['url']); endif; ?>"><?php if($pigvo['name'] == ''): ?>帮助中心<?php else: echo ($pigvo['name']); endif; ?></a>
                </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                <li <?php if((ACTION_NAME) == "help"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/help');?>">帮助中心</a></li><?php endif; ?>
                <?php if($typsz['login'] == 1): if(is_array($zdydh)): $i = 0; $__LIST__ = $zdydh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pigvo): $mod = ($i % 2 );++$i; if($pigvo['type'] == 5): ?><li <?php if((GROUP_NAME) == "User"): ?>class="menuon"<?php endif; ?> <?php if($pigvo['dspl'] == 1): ?>style="display:none;"<?php endif; ?>>
                        <a <?php if($pigvo['open'] == '1'): ?>target="_blank"<?php endif; ?> href="<?php if($pigvo['url'] == ''): echo U('User/Index/index'); else: echo ($pigvo['url']); endif; ?>"><?php if($pigvo['name'] == ''): ?>管理中心<?php else: echo ($pigvo['name']); endif; ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                    <li <?php if((GROUP_NAME) == "User"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('User/Index/index');?>">管理中心</a></li><?php endif; ?>
                <?php if($typsz['common'] == 1): if(is_array($zdydh)): $i = 0; $__LIST__ = $zdydh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pigvo): $mod = ($i % 2 );++$i; if($pigvo['type'] == 4): ?><li <?php if((ACTION_NAME) == "common"): ?>class="menuon"<?php endif; ?> <?php if($pigvo['dspl'] == 1): ?>style="display:none;"<?php endif; ?>>
                        <a <?php if($pigvo['open'] == '1'): ?>target="_blank"<?php endif; ?> href="<?php if($pigvo['url'] == ''): echo U('Home/Index/common'); else: echo ($pigvo['url']); endif; ?>"><?php if($pigvo['name'] == ''): ?>产品案例<?php else: echo ($pigvo['name']); endif; ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                    <li <?php if((ACTION_NAME) == "common"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/common');?>">产品案例</a></li><?php endif; ?>
                <?php if($typsz['prize'] == 1): if(is_array($zdydh)): $i = 0; $__LIST__ = $zdydh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pigvo): $mod = ($i % 2 );++$i; if($pigvo['type'] == 3): ?><li <?php if((ACTION_NAME) == "price"): ?>class="menuon"<?php endif; ?> <?php if($pigvo['dspl'] == 1): ?>style="display:none;"<?php endif; ?>>
                        <a <?php if($pigvo['open'] == '1'): ?>target="_blank"<?php endif; ?> href="<?php if($pigvo['url'] == ''): echo U('Home/Index/price'); else: echo ($pigvo['url']); endif; ?>"><?php if($pigvo['name'] == ''): ?>资费说明<?php else: echo ($pigvo['name']); endif; ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                    <li <?php if((ACTION_NAME) == "price"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/price');?>">资费说明</a></li><?php endif; ?>
                <?php if($typsz['about'] == 1): if(is_array($zdydh)): $i = 0; $__LIST__ = $zdydh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pigvo): $mod = ($i % 2 );++$i; if($pigvo['type'] == 2): ?><li <?php if((ACTION_NAME) == "about"): ?>class="menuon"<?php endif; ?> <?php if($pigvo['dspl'] == 1): ?>style="display:none;"<?php endif; ?>>
                        <a <?php if($pigvo['open'] == '1'): ?>target="_blank"<?php endif; ?> href="<?php if($pigvo['url'] == ''): echo U('Home/Index/about'); else: echo ($pigvo['url']); endif; ?>"><?php if($pigvo['name'] == ''): ?>关于我们<?php else: echo ($pigvo['name']); endif; ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                    <li <?php if((ACTION_NAME) == "about"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/about');?>">关于我们</a></li><?php endif; ?>
                <?php if($typsz['fc'] == 1): if(is_array($zdydh)): $i = 0; $__LIST__ = $zdydh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pigvo): $mod = ($i % 2 );++$i; if($pigvo['type'] == 1): ?><li <?php if((ACTION_NAME) == "fc"): ?>class="menuon"<?php endif; ?> <?php if($pigvo['dspl'] == 1): ?>style="display:none;"<?php endif; ?>>
                        <a <?php if($pigvo['open'] == '1'): ?>target="_blank"<?php endif; ?> href="<?php if($pigvo['url'] == ''): echo U('Home/Index/fc'); else: echo ($pigvo['url']); endif; ?>"><?php if($pigvo['name'] == ''): ?>功能介绍<?php else: echo ($pigvo['name']); endif; ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>     
                <?php else: ?>
                    <li <?php if((ACTION_NAME) == "fc"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/fc');?>">功能介绍</a></li><?php endif; ?>                
                <li <?php if((ACTION_NAME == 'index') and (GROUP_NAME == 'Home')): ?>class="menuon"<?php endif; ?> ><a href="/">首页</a></li>       
            </ul>
        </div>
        </div>
    </div>
</div>
<div id="aaa"></div>


<div id="mu" class="cl"></div>
</div>
</div>
<div id="aaa"></div>

<div id="wp" class="wp">
    <?php if($usertplid == 0): ?><link href="./tpl/User/default/common/css/style.css?id=103" rel="stylesheet" type="text/css" />
  <?php else: ?>
    <link href="./tpl/User/default/common/css/style-<?php echo ($usertplid); ?>.css?id=103" rel="stylesheet" type="text/css" /><?php endif; ?>
 <div class="contentmanage">
    <div class="developer">
       <div class="appTitle normalTitle">
        <h2>管理平台</h2>
        <div class="accountInfo">
        
        </div>
        <div class="clr"></div>
      </div>
      <div class="tableContent">
        <!--左侧功能菜单-->
        <div class="sideBar">
          <div class="catalogList">
            <ul class="<?php if($usertplid != 0): ?>newskin<?php endif; ?>">
            	<li class="subCatalogList"> <a class="secnav_1" href="<?php echo U('Index/useredit');?>">修改密码</a> </li>
				<li class=" subCatalogList "> <a class="secnav_2" href="<?php echo U('Index/index');?>">我的公众号</a></li>
				<li class=" subCatalogList "> <a class="secnav_3" href="<?php if(($hasWeixin) == "0"): ?>javascript:alert('您不可以使用此功能，请联系您的网站管理员处理');<?php else: echo U('Index/add'); endif; ?>">添加公众号</a> </li>
				<li class=" subCatalogList "> <a class="secnav_4" href="<?php echo U('Alipay/index');?>">会员充值</a> </li>
				<li class=" subCatalogList "> <a class="secnav_6" href="<?php echo U('Sms/index');?>">短信管理</a> </li>
				<?php if($thisUser['invitecode']): ?><li class=" subCatalogList "> <a class="secnav_7" href="<?php echo U('Index/invite');?>">邀请朋友</a> </li><?php endif; ?>
        <li class=" subCatalogList "> <a class="secnav_8" href="<?php echo U('Index/switchTpl');?>">切换模板</a> </li>
        <li class=" subCatalogList "> <a class="secnav_9" href="<?php if(C('open_biz') == 0): ?>javascript:alert('请联系站长在后台开启企业号');<?php else: echo U('Index/add',array('biz'=>1)); endif; ?>">添加企业号</a> </li>
            </ul>
          </div>
        </div>

<script src="/tpl/static/jquery-1.4.2.min.js"></script>
<script src="/tpl/static/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/tpl/static/artDialog/plugins/iframeTools.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Common;?>/default_user_com.css" media="all">
<script language="JavaScript">
if (window.top != self){
  window.top.location = self.location;
}
</script>
<script>
function addFee(){
  art.dialog.open('?g=User&m=Alipay&a=add',{lock:true,title:'充值续费',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}
function showApiInfo(id,name){
  art.dialog.open('?g=User&m=Index&a=apiInfo&id='+id,{lock:true,title:name+'接口信息',width:830,height:270,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<div class="content">
<div class="usercontent">

<ul>

  <li class="addli">
    <a class="addwx" style="background-color:#7CBAE5" title="添加公众号" href="#" onclick="addWeixin()">绑定公众号</a>

  </li>

  <li>
    <a onclick="addFuwu()" title="绑定服务窗" class="goldbuy" href="#" style="background-color:#79CBE5">绑定服务窗</a>
  </li>

  <li class="addli">
    <a class="addbiz" title="添加企业号" onclick="addbiz()" href="#" style="background-color:#66D2C6">添加企业号</a>
  </li>

  <li>
    <a href="index.php?g=User&m=Alipay&a=index" class="gold" title="查看资金" style="background-color:#60D295" >
      <p><strong>账户余额：</strong><?php echo ($thisUser["moneybalance"]); ?></p>
      <p>点击充值</p>
    </a>
  </li>

  <li>
  <a href="###">
    <div class="qqqun" style="background-color:#4CC15D">
      <div class="qt">官方QQ号</div>
      <div class="qt2"><?php echo ($f_qq); ?></div>
    </div>
  </a>
  </li>


<script type="text/javascript">
  function addbiz(){
    <?php if(C('open_biz') == 0): ?>alert('请联系站长在后台开启企业号');
    <?php else: ?>
      location.href="<?php echo U('Index/add',array('biz'=>1));?>";<?php endif; ?>
  }

  function addFuwu(){
     <?php if(($hasFuwu) == "0"): ?>alert('您不可以使用服务窗功能，请联系您的网站管理员处理');
     <?php else: ?>
        location.href="<?php echo U('Index/add',array('token'=>$token,'goldbuy'=>1));?>";<?php endif; ?>
  }

  function addWeixin(){
    <?php if(($hasWeixin) == "0"): ?>alert('您不可以使用此功能，请联系您的网站管理员处理');
    <?php else: ?>
         <?php if($oauthUrl == ''): ?>location.href="<?php echo U('Index/add');?>";
          <?php else: ?>
             location.href="<?php echo ($oauthUrl); ?>";<?php endif; endif; ?>
  }
</script>

<div class="clr"></div>
</ul>
        <div class="clr"></div>
      </div>
<?php if($usertplid == 2): ?><script src="<?php echo ($staticPath); ?>/tpl/static/new/js/jquery-2.1.1.js"></script>
      <div class="ibox-content">
          <table class="table table-hover">
              <thead>
              <tr>
                  <th>公众号名称</th>
                  <th>VIP等级</th>
                  <th>创建时间/到期时间</th>
                  <th>已定义/上限</th>
                  <th>请求数</th>
                  <th>操作</th>
              </tr>
              </thead>
           <TBODY>
                <TR></TR>
                 <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><TR>
            <TD>
        <?php if($vo['qr'] == ""){?>
        <p><a href="<?php echo U('Function/index',array('id'=>$vo['id'],'token'=>$vo['token']));?>" ><img src="<?php echo ($vo["headerpic"]); ?>" width="40" height="40"></a></p><br><p style="float:left;margin-top:-15px;<?php if($vo['error'] != 0){echo "color:red";}?>" ><?php echo ($vo["wxname"]); ?></p>
        <?php }else{?>
        <p><a href="<?php echo U('Function/index',array('id'=>$vo['id'],'token'=>$vo['token']));?>" ><div class="cateimg"><img src="<?php echo ($vo["headerpic"]); ?>" width="40" height="40" style="width:40px;height:40px" class="cateimg_small"><img src="<?php echo ($vo["qr"]); ?>" class="cateimg_big"  /></div></a></p><br><br><br><p style="float:left;margin-top:-15px;<?php if($vo['error'] != 0){echo "color:red";}?>"><?php echo ($vo["wxname"]); ?></p>
        <?php }?>
      </TD>
      <TD align="center" style="line-height: 93px;"><?php echo ($thisGroup["name"]); ?></TD>
      <TD><p>创建时间:<?php echo (date("Y-m-d",$vo["createtime"])); ?></p><p>到期时间:<?php echo (date("Y-m-d",$viptime)); ?></p><p><a href="###" onclick="addFee()" id="smemberss" class="green"><em>升级vip续费</em></a></p></Td>
      <TD align="center" style="line-height: 93px;">图文：<?php echo $_SESSION['diynum'].'/'.$group[$_SESSION['gid']]['did']; ?></TD>
      <TD><p style="margin-top: 18px;">总请求数:<?php echo $_SESSION['connectnum'] ?></p><p> 本月请求数:<?php echo $group[$_SESSION['gid']]['cid']; ?></p></TD>

      <TD class="norightborder">　
      <div style="">
      <a class="btnGrayS" href="<?php echo U('Index/edit',array('id'=>$vo['id'])); if($vo["ifbiz"] == 1): ?>&biz=1<?php endif; if($vo["goldbuy"] != ""): ?>&goldbuy=1<?php endif; ?>">编辑</a>
      <a class="btnGrayS" class="btnGrayS" href="javascript:drop_confirm('您确定要删除吗?', '<?php echo U('Index/del',array('id'=>$vo['id']));?>');">删除</a>
          <?php if($vo["ifbiz"] == 1): ?><a class="btnGrayS" target="_blank" href="<?php echo U('Index/qiye',array('id'=>$vo['id']));?>" class="btnGreens" >功能管理</a>
            <a class="btnGrayS" target="_blank" href="<?php echo U('Index/qiye',array('id'=>$vo['id']));?>" class="btnGreens" >企业号</a>
          <?php else: ?>
  <?php if($vo['qr'] != '' || $topdomain == 'pigcms.cn'){?>
      <a class="btnGrayS" <?php if($usertplid == 1): ?>href="<?php echo U('Function/welcome',array('id'=>$vo['id'],'token'=>$vo['token']));?>" <?php elseif($usertplid == 2): ?>href="<?php echo U('Function/welcome',array('id'=>$vo['id'],'token'=>$vo['token']));?>"<?php else: ?>href="<?php echo U('Function/index',array('id'=>$vo['id'],'token'=>$vo['token']));?>"<?php endif; ?>class="btnGreens" >功能管理</a>
    <?php }else{?>
      <a class="btnGrayS" href="javascript:alert('请上传您的公众号二维码！');window.location.href='<?php echo U("User/Index/edit",array('id'=>$vo['id']));?>'" class="btnGreens" >功能管理</a>
    <?php }?>
      <a class="btnGrayS" onclick="showApiInfo(<?php echo ($vo["id"]); ?>,'<?php echo ($vo["wxname"]); ?>')" href="###" class="btnGreens" >API接口</a>
     <!--自行增加 场景接入开始-->
           <?php if($vo["eqx"] == '1'): ?><a  href="<?php echo U('Index/reeqx',array('id'=>$vo['id'],'token'=>$vo['token']));?>" class="btnGreens"  target="_blank">重新同步</a><?php else: ?> <a  href="<?php echo U('Index/eqx',array('id'=>$vo['id'],'token'=>$vo['token']));?>" class="btnGreens"  target="_blank">同步场景</a><?php endif; ?>
      <!--自行增加 场景接入结束-->
      <?php if($vo["type"] == 1 and $oauthUrl != ''): if($vo["authorizer_refresh_token"] == ''): ?><a class="btnGrayS" href="<?php echo ($oauthUrl); ?>&ac_id=<?php echo ($vo["id"]); ?>" class="btnGreens">重新授权</a><?php endif; ?>
      <?php elseif($vo["type"] == 0 and $oauthUrl != '' and $vo["fuwuappid"] != ''): ?>
        <a class="btnGrayS" href="<?php echo ($oauthUrl); ?>&ac_id=<?php echo ($vo["id"]); ?>" class="btnGreens">登陆授权</a><?php endif; endif; ?>
      </div>
            </TD>
          </TR><?php endforeach; endif; else: echo "" ;endif; ?>

              </TBODY>
          </table>
      </div>
<?php else: ?>
<div class="msgWrap">
            <TABLE class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
              <THEAD>
                <TR>
                  <TH>公众号名称</TH>
                  <TH style="text-align:center">VIP等级</TH>
                  <TH>创建时间/到期时间</TH>
                   <TH>已定义/上限</TH>
                   <TH>请求数</TH>
                  <TH>操作</TH>
                </TR>
              </THEAD>
              <TBODY>
                <TR></TR>
                 <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><TR>
            <TD>
        <?php if($vo['qr'] == ""){?>
        <p><a href="<?php echo U('Function/index',array('id'=>$vo['id'],'token'=>$vo['token']));?>" ><img src="<?php echo ($vo["headerpic"]); ?>" width="40" height="40"></a></p><br><p style="float:left;margin-top:-15px;<?php if($vo['error'] != 0){echo "color:red";}?>" ><?php echo ($vo["wxname"]); ?></p>
        <?php }else{?>
        <p><a href="<?php echo U('Function/index',array('id'=>$vo['id'],'token'=>$vo['token']));?>" ><div class="cateimg"><img src="<?php echo ($vo["headerpic"]); ?>" width="40" height="40" style="width:40px;height:40px" class="cateimg_small"><img src="<?php echo ($vo["qr"]); ?>" class="cateimg_big"  /></div></a></p><br><br><br><p style="float:left;margin-top:-15px;<?php if($vo['error'] != 0){echo "color:red";}?>"><?php echo ($vo["wxname"]); ?></p>
        <?php }?>
      </TD>
      <TD align="center"><?php echo ($thisGroup["name"]); ?></TD>
            <TD><p>创建时间:<?php echo (date("Y-m-d",$vo["createtime"])); ?></p><p>到期时间:<?php echo (date("Y-m-d",$viptime)); ?></p><p><a href="###" onclick="addFee()" id="smemberss" class="green"><em>升级vip续费</em></a></p></Td>
            <TD><p>图文：<?php echo $_SESSION['diynum'].'/'.$group[$_SESSION['gid']]['did']; ?></p></TD>
             <TD><p>总请求数:<?php echo $_SESSION['connectnum'] ?></p><p> 本月请求数:<?php echo $group[$_SESSION['gid']]['cid']; ?></p></TD>

            <TD class="norightborder">　

      <a href="<?php echo U('Index/edit',array('id'=>$vo['id'])); if($vo["ifbiz"] == 1): ?>&biz=1<?php endif; if($vo["goldbuy"] != ""): ?>&goldbuy=1<?php endif; ?>">编辑</a>
      <a  href="javascript:drop_confirm('您确定要删除吗?', '<?php echo U('Index/del',array('id'=>$vo['id']));?>');">删除</a>
          <?php if($vo["ifbiz"] == 1): ?><a target="_blank" href="<?php echo U('Index/qiye',array('id'=>$vo['id']));?>" class="btnGreens" >功能管理</a>
            <a target="_blank" href="<?php echo U('Index/qiye',array('id'=>$vo['id']));?>" class="btnGreens" >企业号</a>
          <?php else: ?>
  <?php if($vo['qr'] != '' || $topdomain == 'pigcms.cn' || $vo['fuwuappid'] != ''){?>
  <a href="javascript:" class="btnGreens" onclick="bomb_window('<?php echo ($vo[id]); ?>','<?php echo ($vo[token]); ?>');">功能管理</a>
    <?php }else{?>
      <a href="javascript:alert('请上传您的公众号二维码！');window.location.href='<?php echo U("User/Index/edit",array('id'=>$vo['id']));?>'" class="btnGreens" >功能管理</a>
    <?php }?>
      <a onclick="showApiInfo(<?php echo ($vo["id"]); ?>,'<?php echo ($vo["wxname"]); ?>')" href="###" class="btnGreens" >API接口</a>
   <!--自行增加 场景接入开始-->
           <?php if($vo["eqx"] == '1'): ?><a  href="<?php echo U('Index/reeqx',array('id'=>$vo['id'],'token'=>$vo['token']));?>" class="btnGreens"  target="_blank">重新同步</a><?php else: ?> <a  href="<?php echo U('Index/eqx',array('id'=>$vo['id'],'token'=>$vo['token']));?>" class="btnGreens"  target="_blank">同步场景</a><?php endif; ?>
      <!--自行增加 场景接入结束-->
      <?php if($vo["type"] == 1 and $oauthUrl != ''): if($vo["authorizer_refresh_token"] == ''): ?><a href="<?php echo ($oauthUrl); ?>&ac_id=<?php echo ($vo["id"]); ?>" class="btnGreens">重新授权</a><?php endif; ?>
      <?php elseif($vo["type"] == 0 and $oauthUrl != '' and $vo["fuwuappid"] != ''): ?>
        <a href="<?php echo ($oauthUrl); ?>&ac_id=<?php echo ($vo["id"]); ?>" class="btnGreens">登陆授权</a><?php endif; endif; ?>

            </TD>
          </TR><?php endforeach; endif; else: echo "" ;endif; ?>

              </TBODY>
            </TABLE>

          </div><?php endif; ?>
          <br>
          <?php if($demo == 1): ?><div class="alert">
          <p><b>欢迎试用小猪CMS微信多用户营销系统，为了您测试方便，我们已经自动创建了公众号并填充了各类数据，您只需要按照下面步骤操作即可进行测试：</b></p>
          <p>1、<a href="<?php echo U('Index/edit',array('id'=>$wxinfo['id']));?>">点击这里修改您的公众号信息</a></p>
          <p>2、登录您的微信公众平台，按照说明绑定您的微信公众号(<a href="<?php echo U('User/Index/bindHelp',array('id'=>$wxinfo['id'],'token'=>$wxinfo['token']));?>" target="_blank">点击这里查看帮助说明</a>)</p>
          <p>3、如果您需要测试自定义菜单功能，请<a href="<?php echo U('Function/index',array('id'=>$wxinfo['id'],'token'=>$wxinfo['token']));?>">进入功能管理</a>，然后生成自定义菜单，重新关注公众号就会看到自定义菜单了</p>
          <p>就这些，如果碰到任何问题，请您给我们留言，QQ：800022936</p>
          </div><?php endif; ?>
          <div class="cLine">
            <div class="pageNavigator right">
              <div class="pages" <?php if($usertplid == 2): ?>style="margin-right: 130px;"<?php endif; ?>><?php echo ($page); ?></div>
            </div>
            <div class="clr"></div>
          </div>
        </div>

        <div class="clr"></div>
      </div>
    </div>
  </div>
  <!--底部-->
    </div>
    <!--ad start-->
    <?php if($thisAD): ?><div id="ad1" style="width: 100%; height: 100%; position: fixed; z-index: 1997; top: 0px; left: 0px; overflow: hidden;"><div style="height: 100%; background: none repeat scroll 0% 0% rgb(0, 0, 0); opacity: 0.65;filter:alpha(opacity=65);">

    </div></div>
    <div id="ad2" style="position:fixed; text-align:center; width:100%; top:140px; z-index:30001">
    <a href="<?php if ($thisAD['url']){ echo ($thisAD["url"]); }else{?>###<?php };?>" target="_blank"><img src="<?php echo ($thisAD["imgs"]); ?>" /></a>
    </div>
    <div id="ad3" style="position:fixed; text-align:center; width:100%; top:140px;z-index:30012; background:#f80; opacity:0;filter:alpha(opacity=0);">
    <div style="height:40px;width:700px;margin:0 auto;z-index:30012;">
    <div onclick="closeAD()" style="height:45px;width:45px;margin:0 0 0 655px;cursor:pointer;"></div>
    </div>
    </div>
    <script>
    function closeAD(){
      $('#ad1').animate({opacity: "hide"}, "slow");
      $('#ad2').animate({opacity: "hide"}, "slow");
      $('#ad3').animate({opacity: "hide"}, "slow");
      $.ajax({url: "/index.php?g=User&m=Index&a=closeAD",dataType: "json"});
    }
    </script><?php endif; ?>




<script type="text/javascript">
  function bomb_window(id,token){
    var id=id;
    var token=token;
    var dmheight=$(document).height();
      $('.rightBtn').attr({
        id:id,
        token:token
      })
      $.ajax({
        type:"post",
        url:"<?php echo U('User/Index/bomb_ajax');?>&id="+id+"&token="+token,
        datatype:"json",
        data:{
          id:id,
          token:token
        },
        success:function(sta){
          if(sta.length<'4'){//判断是否超时
            var obj = JSON.parse(sta);
            if(<?php echo ($usertplid); ?>==1 || <?php echo ($usertplid); ?>==2){//判断选择的模板
              if(obj=='1'){
                location="<?php echo U('Function/welcome');?>&id="+id+"&token="+token;
              }else{
                $('.window').css('display','block');
                $('.fullBg').css({
                  'display':'block',
                  'height':dmheight
                });
                $('body,html').css('overflow', 'hidden');

              }
            }else{
              if(obj=='1'){
                location="<?php echo U('Function/index');?>&id="+id+"&token="+token;
              }else{
                $('.window').css('display','block');
                $('.fullBg').css({
                  'display':'block',
                  'height':dmheight
                });
                $('body,html').css('overflow', 'hidden');
              }
            }
          }else{
            location="<?php echo U('Home/Index/login');?>";
          }


        }
      })
  }
</script>

<?php if($pid_zw == 1): ?><div class="fullBg"></div>
<div class="window w0">
    <div class="windowPo">
        <img src="<?php echo ($staticPath); ?>/tpl/User/default/common/images/bomb_window/yun.png" class="yun bounceInDown" />
        <img src="<?php echo ($staticPath); ?>/tpl/User/default/common/images/bomb_window/pigImg.png" class="pigImg bounceInLeft" />
        <img src="<?php echo ($staticPath); ?>/tpl/User/default/common/images/bomb_window/text00.png" class="text00"/>
        <img src="<?php echo ($staticPath); ?>/tpl/User/default/common/images/bomb_window/text01.png" class="text01"/>
        <a href="javascript:;" class="leftBtn">查看如何设置</a>
        <a href="javascript:;" class="rightBtn">我设置过了</a>
        <a href="javascript:;" class="xClosed" onclick="xClosed()" style="display: none;"></a>
    </div>
</div><?php endif; ?>

<script type="text/javascript">
  $('.rightBtn').click(function(){
    var id=$('.rightBtn').attr('id');
    var token=$('.rightBtn').attr('token');
    $.ajax({
      type:"post",
      url:"<?php echo U('User/Index/bomb_ajax');?>&id="+id+"&token="+token,
      datatype:"json",
      data:{
        id:id,
        token:token,
        set_id:1
      },
      success:function(shh){
        if(shh){
              $(".window").fadeOut();
          $(".fullBg").css({
            'display':'none',
            'height':'0'
          });
          $('body,html').css('overflow', 'auto');
          location="<?php echo U('Function/index');?>&id="+id+"&token="+token;
        }
      }
      })
  })
</script>
<script type="text/javascript">
  $('.leftBtn').click(function(){
    art.dialog.open('http://www.meihua.com/waphelp/pigcms.php',{lock:true,title:'设置帮助',width:800,height:600,yesText:'Y',background: '#000',opacity: 0.45});

  })
</script>
<script type="text/javascript">
  centerWindow(".window");
</script>


    <!--ad end-->
</div>
</div>
</div>
</div>
</div>
</div>
<?php if($_SESSION['is_syn']== 0): ?><style>
.IndexFoot {
	BACKGROUND-COLOR: #333; WIDTH: 100%; HEIGHT: 39px
}
.foot{ width:988px; margin:0px auto; font-size:12px; line-height:39px;}
.foot .foot_page{ float:left; width:600px;color:white;}
.foot .foot_page a{ color:white; text-decoration:none;}
#copyright{ float:right; width:380px; text-align:right; font-size:12px; color:#FFF;}
.alert-success{color: #993300;background-color: #fcf8e3;border-color: #faebcc;}
</style>

<?php if($ischild == 1){ if($usertplid == 2){ $usertplid =1; } } ?>
<?php if($usertplid != 2): ?><div class="IndexFoot" style="height:120px;clear:both">
<div class="foot" style="padding-top:20px;">
<div class="foot_page" >
<a href="<?php echo ($f_siteUrl); ?>"><?php echo ($f_siteName); ?>,微信公众平台营销</a><br/>
帮助您快速搭建属于自己的营销平台,构建自己的客户群体。
</div>
<div id="copyright" style="color:white;">
	<?php echo ($f_siteName); ?>(c)版权所有 <a href="http://www.miibeian.gov.cn" target="_blank"><?php echo ($f_ipc); ?></a><br/>
	技术支持：<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($f_qq); ?>&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo ($f_qq); ?>:51" alt="联系我吧" title="联系我吧"/></a>

</div>
    </div>
</div><?php endif; ?>
<!-- 帮助悬浮开始 -->
<?php  $data_g=GROUP_NAME; $server = getUpdateServer(); $users=M('Users')->where(array('id'=>$_SESSION['uid']))->find(); if(C('close_help') == 1 && $users['sysuser'] == 0){ $data_g='notingh'; }else{ $textHelp=0; } ?>     
<?php if($usertplid == 2): if(C('close_help') != 1){ $data = array( 'key' => C('server_key'), 'domain' => C('server_topdomain'), 'is_text' => $textHelp, 'data_g' => $data_g, 'data_m' => MODULE_NAME, 'data_a' => ACTION_NAME ); if(!C('emergent_mode')): if(function_exists('curl_init')){ $ch = curl_init(); curl_setopt($ch, CURLOPT_TIMEOUT, 4); curl_setopt($ch, CURLOPT_URL, 'http://sharp2008.vhost024.dns345.cn/up/admin.php?m=help&c=view&a=get_list&'.http_build_query($data)); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_HEADER, 0); $content = curl_exec($ch);curl_close($ch); }else{ $opts = array( 'http'=>array( 'method'=>'GET', 'timeout'=>4, ) ); $fp= stream_context_create($opts); $content = file_get_contents( 'http://sharp2008.vhost024.dns345.cn/up/admin.php?m=help&c=view&a=get_list&'.http_build_query($data), false, $fp); fpassthru($fp); } endif; $content = json_decode($content,true); } ?>
</div>
</div>
<style>
	.tab ul li{padding:0 11px}
	.alert h4 {color: #000;}
	#tags .btnGreen{background-color: #44b549;}
	#tags .btnGreen:hover,#tags .btnGreen:focus,#tags .btnGreenactive{background-color: #44b549;border-color: #44b549;color: #FFFFFF;}
	.mini-navbar .nav > li:nth-last-child(13) ul{margin-top: -421px;}
	.mini-navbar .nav > li:nth-last-child(3) ul{margin-top: -159px;}
	.mini-navbar .nav > li:nth-last-child(4) ul{margin-top: -427px;}
	.mini-navbar .nav > li:nth-last-child(10) ul{margin-top: -85px;}
	#js_editform{width:618px;}
	.lianjie{background: #44b549;color: #fff;margin: 0px 15px;padding: 5px 10px;border-radius: 6px;font-size: 11px;line-height: 14px;margin-top: 3px;}
	.lianjie a:link{color: #fff;}
	.lianjie a:hover {color: #000;}
</style>
<div class="small-chat-box fadeInRight animated" style="margin-right: 100px;margin-bottom:100px;">
        <div class="heading" draggable="true">
             <center><a style="height: auto;width: auto;display: initial;background:#2f4050;padding: 0px 0px 0px 50px;text-align:center;color:#fff;border-radius:0;margin-right:0px;margin-bottom: 0px;" class="open-small-chat">相关帮助&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></center>	
        </div>
        <div class="content" style="height:220px;">
		<span class="help_content"></span>
			<span class="loading" >
				<img  style="margin-left:50px;" src="./tpl/static/cutprice/js/artDialog/skins/icons/loading.gif" /> 正在加载帮助教程...
			</span>
        </div>
        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($f_qq); ?>&site=qq&menu=yes" target="_blank"><div class="form-chat btn btn-primary" style="  text-align: center;">
        在线客服
        </div></a>
    </div>
    <div id="small-chat">
        <span class="badge badge-warning pull-right">不懂就点我</span>
        <a class="open-small-chat">
            <i class="fa fa-weixin" style="width:70px;font-size:40px;"></i>帮助
        </a>
    </div>
</div>
<script type="text/javascript">
		var oDiv1 = document.getElementById('small-chat');
		oDiv1.onclick = function(){
		var flag = true;
			if(flag) {
				$.ajax({
					type : 'GET',
					url : '<?php echo U('User/Index/ajax_help', array('group'=>GROUP_NAME,'module'=>MODULE_NAME, 'action'=>ACTION_NAME)); ?>',
					dataType : 'html',
					success : function (data) {
						if (data) {
							$('.help_content').html(data);
						}
						flag = false;
						$('.loading').hide();
					}
				});
			}
		}
		function openwin(url,iHeight,iWidth){
			var iTop = (window.screen.availHeight-30-iHeight)/2,iLeft = (window.screen.availWidth-10-iWidth)/2;
			window.open(url, "newwindow", "height="+iHeight+", width="+iWidth+", toolbar=no, menubar=no,top="+iTop+",left="+iLeft+",scrollbars=yes, resizable=no, location=no, status=no");
		}
	</script>
    <script src="<?php echo ($staticPath); ?>/tpl/static/new/js/bootstrap.min.js"></script>
    <script src="<?php echo ($staticPath); ?>/tpl/static/new/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo ($staticPath); ?>/tpl/static/new/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo ($staticPath); ?>/tpl/static/new/js/inspinia.js"></script>
    <script src="<?php echo ($staticPath); ?>/tpl/static/new/js/plugins/pace/pace.min.js"></script>
<?php else: ?>
	<?php if(C('close_help') == 0): ?>
	<link href="<?php echo ($staticPath); ?>/tpl/static/help_xuanfu/css/zuoce.css" type="text/css" rel="stylesheet"/>
	<div class="zuoce zuoce_clear">
		<div id="Layer1">
			<a href="javascript:"><img src="<?php echo ($staticPath); ?>/tpl/static/help_xuanfu/images/ou_03.png"/></a>
		</div>
		<div id="Layer2" style="display:none;height:400px;overflow-y:scroll;">
			<p class="xiangGuan zuoce_clear">相关帮助</p>
			<span class="help_content"></span>
			<span class="loading" >
				<img  style="margin-left:50px;" src="./tpl/static/cutprice/js/artDialog/skins/icons/loading.gif" /> 正在加载帮助教程...
			</span>
			
			<!--p class="anNiuo clear"><a href="#">进入帮助中心27</a></p-->
			<p class="anNiut zuoce_clear"><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($f_qq); ?>&site=qq&menu=yes" target="_blank">在线客服</a></p>
		</div>
	</div>
	<script type="text/javascript">
		window.onload = function(){
			var oDiv1 = document.getElementById('Layer1');
			var oDiv2 = document.getElementById('Layer2');
			var flag = true;
			oDiv1.onclick = function(){
				oDiv2.style.display = oDiv2.style.display == 'block' ? 'none' : 'block';
				if(flag) {
					$.ajax({
						type : 'GET',
						url : '<?php echo U('User/Index/ajax_help', array('group'=>GROUP_NAME,'module'=>MODULE_NAME, 'action'=>ACTION_NAME)); ?>',
						dataType : 'html',
						success : function (data) {
							if (data) {
								$('.help_content').html(data);
							}
							flag = false;
							$('.loading').hide();
						}
					});
				}
			}
		}
		function openwin(url,iHeight,iWidth){
			var iTop = (window.screen.availHeight-30-iHeight)/2,iLeft = (window.screen.availWidth-10-iWidth)/2;
			window.open(url, "newwindow", "height="+iHeight+", width="+iWidth+", toolbar=no, menubar=no,top="+iTop+",left="+iLeft+",scrollbars=yes, resizable=no, location=no, status=no");
		}
	</script>
	<?php endif; endif; ?>
<!-- 帮助悬浮结束 -->
<div style="display:none">
<?php echo ($alert); ?> 
<?php echo base64_decode(C('countsz'));?>
</div><?php endif; ?>
</body>

<?php if(MODULE_NAME == 'Function' && ACTION_NAME == 'welcome'){ ?>
<script src="<?php echo ($staticPath); ?>/tpl/static/myChart/js/echarts-plain.js"></script>

<script type="text/javascript">


    var myChart = echarts.init(document.getElementById('main')); 
   

    var option = {
        title : {
            text: '<?php if($charts["ifnull"] == 1): ?>本月关注和文本请求数据统计示例图(您暂时没有数据)<?php else: ?>本月关注和文本请求数据统计<?php endif; ?>',
            x:'left'
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['文本请求数','关注数'],
            x: 'right'
        },
        toolbox: {
            show : false,
            feature : {
                mark : {show: false},
                dataView : {show: false, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                restore : {show: false} ,
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : [<?php echo ($charts["xAxis"]); ?>]
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'文本请求数',
                type:'line',
                tiled: '总量',
                data: [<?php echo ($charts["text"]); ?>]
            },    
            {
                "name":'关注数',
                "type":'line',
                "tiled": '总量',
                data:[<?php echo ($charts["follow"]); ?>]
            }
       

        ]

    };                   

    myChart.setOption(option); 


    var myChart2 = echarts.init(document.getElementById('pieMain')); 
               
    var option2 = {
        title : {
            text: '<?php if($pie["ifnull"] == 1): ?>7日内粉丝行为分析示例图(您暂时没有数据)<?php else: ?>7日内粉丝行为分析<?php endif; ?>',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        toolbox: {
            show : false,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        series : [
            {
                name:'粉丝行为统计',
                type:'pie',
                radius : ['50%', '70%'],
                itemStyle : {
                    normal : {
                        label : {
                            show : false
                        },
                        labelLine : {
                            show : false
                        }
                    },
                    emphasis : {
                        label : {
                            show : true,
                            position : 'center',
                            textStyle : {
                                fontSize : '18',
                                fontWeight : 'bold'
                            }
                        }
                    }
                },
                data:[ 
                    <?php echo ($pie["series"]); ?>
                
                ]
            }
        ]
    };
     myChart2.setOption(option2,true); 


    var myChart3 = echarts.init(document.getElementById('pieMain2')); 
    var option3 = {
        title : {
            text: '<?php if($sex_series["ifnull"] == 1): ?>会员性别统计示例图(您暂时没有数据)<?php else: ?>会员性别统计<?php endif; ?>',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        toolbox: {
            show : false,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        series : [
            {
                name:'会员性别统计',
                type:'pie',
                radius : ['50%', '70%'],
                itemStyle : {
                    normal : {
                        label : {
                            show : false
                        },
                        labelLine : {
                            show : false
                        }
                    },
                    emphasis : {
                        label : {
                            show : true,
                            position : 'center',
                            textStyle : {
                                fontSize : '18',
                                fontWeight : 'bold'
                            }
                        }
                    }
                },
                data:[
                  <?php echo ($sex_series['series']); ?>
                ]
            }
        ]
    };                     

  myChart3.setOption(option3,true); 



    </script>
<?php } ?>

<?php if(MODULE_NAME == 'RecognitionData' && ACTION_NAME == 'index'){?>
	<script src="<?php echo ($staticPath); ?>/tpl/static/myChart/js/echarts-plain.js"></script>

	<script type="text/javascript">
	<?php if($_GET['rid'] != ''){?>
		var myChart = echarts.init(document.getElementById('main')); 
	   

		var option = {
			title : {
				//text: '<?php if($charts["ifnull"] == 1): ?>【<?php echo ($rname); ?>】本月每日扫描次数和人数统计示例图（没有数据）<?php else: ?>【<?php echo ($rname); ?>】本月每日扫描次数和人数统计<?php endif; ?>',
				x:'left'
			},
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:['每日扫描次数','每日扫描人数'],
				x: 'right'
			},
			toolbox: {
				show : false,
				feature : {
					mark : {show: false},
					dataView : {show: false, readOnly: false},
					magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
					restore : {show: false} ,
					saveAsImage : {show: true}
				}
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : [<?php echo ($charts["xAxis"]); ?>]
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:'每日扫描次数',
					type:'line',
					tiled: '总量',
					data: [<?php echo ($charts["cishu"]); ?>]
				},    
				{
					"name":'每日扫描人数',
					"type":'line',
					"tiled": '总量',
					data:[<?php echo ($charts["renshu"]); ?>]
				}
		   

			]

		};                   

		myChart.setOption(option); 
	<?php }else{?>
		var myChart2 = echarts.init(document.getElementById('pieMain')); 
				   
		var option2 = {
			title : {
				//text: '<?php if($cishu["ifnull"] == 1): ?>本月扫描次数分析示例图（没有数据）<?php else: ?>本月扫描次数分析图<?php endif; ?>',
				x:'center'
			},
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			toolbox: {
				show : false,
				feature : {
					mark : {show: true},
					dataView : {show: true, readOnly: false},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
			calculable : true,
			series : [
				{
					name:'本月扫描次数统计',
					type:'pie',
					radius : ['50%', '70%'],
					itemStyle : {
						normal : {
							label : {
								show : false
							},
							labelLine : {
								show : false
							}
						},
						emphasis : {
							label : {
								show : true,
								position : 'center',
								textStyle : {
									fontSize : '18',
									fontWeight : 'bold'
								}
							}
						}
					},
					data:[ 
						<?php echo ($cishu["series"]); ?>
					
					]
				}
			]
		};
		 myChart2.setOption(option2,true); 
		 
		 
		
		var myChart3 = echarts.init(document.getElementById('pieMain2')); 
		var option3 = {
			title : {
				//text: '<?php if($renshu["ifnull"] == 1): ?>本月扫描人数分析示例图（没有数据）<?php else: ?>本月扫描人数分析图<?php endif; ?>',
				x:'center'
			},
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			toolbox: {
				show : false,
				feature : {
					mark : {show: true},
					dataView : {show: true, readOnly: false},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
			calculable : true,
			series : [
				{
					name:'本月扫描人数统计',
					type:'pie',
					radius : ['50%', '70%'],
					itemStyle : {
						normal : {
							label : {
								show : false
							},
							labelLine : {
								show : false
							}
						},
						emphasis : {
							label : {
								show : true,
								position : 'center',
								textStyle : {
									fontSize : '18',
									fontWeight : 'bold'
								}
							}
						}
					},
					data:[
					  <?php echo ($renshu['series']); ?>
					]
				}
			]
		};                     

	  myChart3.setOption(option3,true); 
	<?php }?>
	</script>
<?php }?>
</html>