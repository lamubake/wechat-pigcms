<?php
header("Content-type:text/html;charset=utf-8"); 
//http://mp.weixin.qq.com/s?__biz=MzA4MDgyOTgwNA==&mid=201723415&idx=1&sn=3479200ff9bb35aaa122843ce117e852#rd
$gzh=$_GET['gzh'];
/*//$gzhb=$_GET['gzhurl'];
if (empty($gzhb)) {
			echo "<h1>请先到首页配置内填好关注链接</h1>";
			die;
}
$mid=$_GET['mid'];
$idx=$_GET['idx'];
$sn=$_GET['sn'];
$gzhurl=$gzhb.'&mid='.$mid.'&idx='.$idx.'&sn='.$sn;*/
$arr=array("fzprogrammer","zz");
if (in_array($gzh,$arr)){
	
}  else
{
	
}


if (stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false &&  
    stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
	//  stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
		
	
		//$gzhurl = M("Home")->where(array("token" => $this->token))->getField("gzhurl");
	//	$gzhurl = 'http://mp.weixin.qq.com/s?__biz=MzA4MDgyOTgwNA==&mid=201723415&idx=1&sn=3479200ff9bb35aaa122843ce117e852#rd';

	//	if ($gzhurl == NULL) {
	//		echo "<h1>未设置关注链接</h1>";
	//		die;
	//	}
			echo "<script>window.location.href='" . $gzhurl . "'</script>";
			die;
    // exit("apple is valid");

}

if (stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false &&  
    stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
        header("location:weixin://profile/$gzh");

}



if (stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
  
    // header("location:weixin://profile/fzprogrammer");
    header("location:weixin://profile/$gzh");
   
} else {
 exit("请到微信客户端打开");
//echo "<script>window.location.href='" . $gzhurl . "'</script>";
}

    
   