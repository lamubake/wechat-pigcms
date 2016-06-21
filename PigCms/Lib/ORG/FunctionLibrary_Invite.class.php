<?php
class FunctionLibrary_Invite{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if(!$this->sub){
			return array(
			'name'=>'邀请函',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else{
			$where	=array('token'=>$this->token);
			$items 	= M('Invite')->where($where)->order('id DESC')->select();
			$arr=array(
				'name'=>'邀请函',
				'subkeywords'=>array(
				),
				'sublinks'=>array(
				),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['title'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['title'],'link'=>'{siteUrl}/index.php?g=Wap&m=Invite&a=index&token='.$this->token.'&wecha_id={wechat_id}&yid='.$v['id']);
				}
			}
			return $arr;
		}		
	}
}