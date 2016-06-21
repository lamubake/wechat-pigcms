<?php
class MicrstoreReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		
		$this->item=M('Micrstore_reply')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		
		$thisItem=$this->item;
		$link = $this->siteUrl.'/index.php?g=Wap&m=Micrstore&a=api&token='.$this->token.'&wecha_id='.$this->wechat_id.'&store_id='.$thisItem['sid'];
		return array(array(array($thisItem['title'],$thisItem['description'],$thisItem['img'],$link)),'news');
	}
}
?>

