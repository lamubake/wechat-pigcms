<?php
class HelpingReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Helping')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$reply_pic = explode("http",$this->item['reply_pic']);
		if(count($reply_pic) <= 1){
			$this->item['reply_pic'] = C("site_url").$this->item['reply_pic'];
		}
		$thisItem=$this->item;
		return array(array(array($thisItem['title'],$thisItem['intro'],$thisItem['reply_pic'],$this->siteUrl.U('Wap/Helping/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}

?>

