<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/bootstrap_min.css?2013-9-13-2" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/bootstrap_responsive_min.css?2013-9-13-2" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/sstyle.css?2013-9-13-2" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/todc_bootstrap.css?2013-9-13-2" media="all" />


        <!--[if IE 7]>
            <link href="<?php echo RES;?>/css/font_awesome_ie7.css" rel="stylesheet" />
        <![endif]-->
        <!--[if lte IE 8]>
            <script src="<?php echo RES;?>/js/excanvas_min.js"></script>
        <![endif]-->
        <!--[if lte IE 9]>
            <script src="<?php echo RES;?>/js/watermark.js"></script>
        <![endif]-->
    <div id="main">
        <div class="container-fluid">

            <div class="row-fluid">
                <div class="span12">

                    <div class="box">
                        <div class="box-title">
                            <div class="span12">
                                <h3><i class="icon-table"></i><small>系统管理 >> 清除缓存</small></h3>
                            </div>
                        </div>
<div class="row-fluid dataTables_wrapper">
                                <div class="alert">
                                    <strong>提示</strong>：</br>临时文件目录为 Data/logs/Temp</br>缓存文件目录为 Data/logs/Cache</br>系统缓存文件目录为 Data/logs/Data/_fields
                                </div>
<div class="msgWrap bgfc">
<tr><td><a href="<?php echo U('delt');?>"  class="btn btn-primary">清除临时文件</a></td></tr>
<tr><td><a href="<?php echo U('delc');?>"  class="btn btn-primary">清除缓存文件</a></td></tr>
<tr><td><a href="<?php echo U('del');?>"  class="btn btn-primary">清除系统缓存</a></td></tr>
<tr><td><a href="<?php echo U('delall');?>"  class="btn btn-primary">清除全部缓存</a></td></tr>
</table>
          </div> <!-- /widget-content -->
        </div> <!-- /widget -->
      </div> <!-- /span9 -->
    </div> <!-- /row -->