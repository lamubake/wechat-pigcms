<?php
class SeniorSceneReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Senior_scene')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		$thisItem['url'] = str_replace('{changjingUrl}','http://www.weihubao.com',html_entity_decode($thisItem['url']));
		return array(array(array($thisItem['name'],$thisItem['intro'],$thisItem['pic'],$thisItem['url'])),'news');
	}
}
?>

