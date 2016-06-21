<?php
class vCardReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Cards')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		$heka_url = "http://www.meihua.com/";
		$unique = base64_encode($_SERVER['SERVER_NAME'].'_pigcms_'.$this->token);
		return array(array(array($thisItem['title'],'',$thisItem['picurl'],$heka_url.'index.php?m=Card&c=index&a=index&unique='.$unique.'&crid='.$thisItem['cardid'].'&usercardid='.$thisItem['id'].'&token='.$this->token.'&wecha_id='.$this->wechat_id)),'news');
	}
	
}
?>