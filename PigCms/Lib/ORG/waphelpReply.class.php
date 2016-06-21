<?php
class waphelpReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		//$this->item=M('Problem_game')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		$wxuser=M('Wxuser')->where(array('token'=>$this->token))->find();
		$thisUser=M('Users')->where(array('id'=>$wxuser['uid']))->find();
		$isagent=$thisUser['agent']?1:0;
		return array(array(array('wap帮助','','','http://www.meihua.com/waphelp/index.php?weixintype='.$wxuser['winxintype'].'&isAgent='.$isagent.'&username='.$thisUser['username'].'&siteUrl='.$this->siteUrl)),'news');
	}
}
?>

