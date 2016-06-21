<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限管理</title>
<link href="<?php echo RES;?>/images/main.css" type="text/css" rel="stylesheet">
<script src="<?php echo STATICS;?>/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo STATICS;?>/function.js" type="text/javascript"></script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
</head>
<body class="warp">
<div id="artlist">
	<div class="mod kjnav">
		<?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U($action.'/'.$vo['name'],array('pid'=>$_GET['pid'],'level'=>3,'title'=>urlencode ($vo['title'])));?>"><?php echo ($vo['title']); ?></a>
		<?php if(($action == 'Article') or ($action == 'Img') or ($action == 'Text') or ($action == 'Voiceresponse')): break; endif; endforeach; endif; else: echo "" ;endif; ?>
	</div>   	
</div>
<div class="cr"></div>
<div id="artlist">

	 <div class="ksearch">
    	<div class="fl">
			<form action="<?php echo U('Users/search');?>" method="post">
	
			<b class="kserico">快速搜索：</b>搜索类型：
			<select name="type">
				<option value="1">用户名</option>
				<option value="2">用户ID</option>
				<option value="3">用户邮箱</option>
			</select> 
			<input name="name" class="ipt" type="text" value=""> 
			<input type="submit" class="ksub" value="">
			</form>
        </div>
        <div class="fl">
			<b>排序方式：</b>
			<select>
				<option value="">注册时间（降序）</option>
				<option value="">登陆时间（降序）</option>
			</select>
		</div>
        <div class="fl">
			<b>查看方式：</b>
			<select>
				<option value="">按分类查看</option>
			</select>
		</div>
    </div>
 	
</div>
<form name="form1" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="alist">
		<tr>
			<td align='center'><input type="checkbox" id="checkAll" value="1" style="vertical-align:middle;"/>&nbsp;全选</td>
			<td align='center'><input type="submit" value="设为系统用户" onclick="send();"/></td>
			<td align='center'><input type="submit" value="取消系统用户" onclick="save();"/>&nbsp;&nbsp;<nobr>系统用户可以看到视频帮助</nobr></td>
			<td align='center'><input type="hidden" name="pid" value="<?php echo ($pid); ?>"/></td>
		</tr>
		  <tr>
		  	<td width="40">选中</td>
			<td width="20">ID</td>
			<td width="150">用户名称</td>
			<td width="50">系统用户</td>
			<td width="150">角色组</td>
			<td width="100">最后登录IP</td>
			<td width="150">最后登录时间</td>
			<td width="70">状态</td>
			<td width="100">管理操作</td>
		  </tr>
		    <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td align='center'><input type="checkbox" name='test[]' value="<?php echo ($vo["id"]); ?>"/></td>
					<td align='center'><?php echo ($vo["id"]); ?></td>
					<td ><?php echo ($vo["username"]); ?></td>
					<td ><?php if(($vo["sysuser"]) == "1"): ?>是<?php else: ?>否<?php endif; ?></td>
					<td ><?php echo ($group[$vo['gid']]); ?></td>
					<td align='center'><?php echo ($vo["lastip"]); ?></td>
					<td align='center'><?php echo date('Y-m-d H:i:s', $vo['lasttime']) ?></td>
					<td align='center'><?php if(($vo["status"]) == "1"): ?><font color="red">√</font><?php else: ?><font color="blue">×</font><?php endif; ?> 
					</td>
					<td align='center'>
						<a href="<?php echo U('Users/edit/',array('id'=>$vo['id']));?>">修改</a>
						| <?php if(($vo["username"]) == "admin"): ?><font color="#cccccc">删除</font><?php else: ?><a href="javascript:void(0)" onclick="if(confirm('确定删除吗')){location.href='<?php echo U('Users/del/',array('id'=>$vo['id']));?>'}">删除</a><?php endif; ?>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	    <tr bgcolor="#FFFFFF"> 
	      	<td colspan="9"><div class="listpage"><?php echo ($page); ?></div></td>
	    </tr>
	   
	</table>
</form>
<script type="text/javascript">
	$(function(){
		$('#checkAll').click(function(){
			if($(this).attr('checked')){
				$(':checkbox').attr('checked','true');
			}else{
				$(':checkbox').removeAttr('checked');
			}
		});
	});
</script>
<script type="text/javascript">
	function send(){ 
		document.form1.action="<?php echo U('Users/syname');?>";
		document.form1.submit();
	}
	function save(){
		document.form1.action="<?php echo U('Users/sysname');?>";
		document.form1.submit();
	}
</script>
</body>
</html>