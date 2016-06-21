<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>跳转提示</title>
        <link href="{pigcms:$staticPath}/tpl/static/redirect/css/public.css" rel="stylesheet" type="text/css" />
        <link href="{pigcms:$staticPath}/tpl/static/redirect/css/index.css" rel="stylesheet" type="text/css" />
        <link href="{pigcms:$staticPath}/tpl/static/redirect/css/404.css" rel="stylesheet" type="text/css" />
        <script src="{pigcms:$staticPath}/tpl/static/jquery-1.4.2.min.js"></script>
        <script type="text/javascript">

            $(function(){
                var h=$(window).height();
                $('body').height(h);
                $('.mianBox').height(h);
                centerWindow(".tipInfo");
            });

            //2.将盒子方法放入这个方，方便法统一调用
            function centerWindow(a) {
                center(a);
                //自适应窗口
                $(window).bind('scroll resize',
                        function() {
                            center(a);
                        });
            }

            //1.居中方法，传入需要剧中的标签
            function center(a) {
                var wWidth = $(window).width();
                var wHeight = $(window).height();
                var boxWidth = $(a).width();
                var boxHeight = $(a).height();
                var scrollTop = $(window).scrollTop();
                var scrollLeft = $(window).scrollLeft();
                var top = scrollTop + (wHeight - boxHeight) / 2;
                var left = scrollLeft + (wWidth - boxWidth) / 2;
                $(a).css({
                    "top": top,
                    "left": left
                });
            }
        </script>
    </head>
    <body>
        <div class="mianBox">
        <present name="message">
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/yun0.png" alt="" class="yun yun0"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/yun1.png" alt="" class="yun yun1" />
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/yun2.png" alt="" class="yun yun2"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/disk.png" alt="" class="disk"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/light.png" alt="" class="light"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/man.png" alt="" class="man"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/picv.png" alt="" class="picv"/>
            <div class="tipInfo">
                <div class="in">
                    <div class="textThis">
                        <h2>
                            <?php echo($message); ?>
                        </h2>
        <else/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/yun0.png" alt="" class="yun yun0"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/yun1.png" alt="" class="yun yun1" />
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/yun2.png" alt="" class="yun yun2"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/bird.png" alt="" class="bird"/>
            <img src="{pigcms:$staticPath}/tpl/static/redirect/images/404/san.png" alt="" class="san"/>
            <div class="tipInfo">
                <div class="in">
                    <div class="textThis">
                        <h2>
                            <?php echo($error); ?>
                        </h2>
        </present>
                        <p><span>页面自动<a id="href" href="<?php echo($jumpUrl); ?>">跳转</a></span><span>等待<b id="wait"><?php echo($waitSecond); ?></b>秒</span></p>
                        <script type="text/javascript">
                            (function(){
                                var wait = document.getElementById('wait'),href = document.getElementById('href').href;
                                var interval = setInterval(function(){
                                    var time = --wait.innerHTML;
                                    if(time <= 0) {
                                        location.href = href;
                                        clearInterval(interval);
                                    };
                                }, 1000);
                            })();
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


















