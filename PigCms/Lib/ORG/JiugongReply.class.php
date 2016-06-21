<?php
class JiugongReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Lottery')->where(array('id'=>$data['pid'],'type'=>10))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		$id = M('Lottery')->where(array('zjpic'=>$thisItem['id']))->getField('id');
		return array(array(array($thisItem['title'],$thisItem['sttxt'],$thisItem['starpicurl'],$this->siteUrl.U('Wap/Jiugong/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}
?>

