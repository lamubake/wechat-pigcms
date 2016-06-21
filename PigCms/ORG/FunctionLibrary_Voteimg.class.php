<?php
class FunctionLibrary_Voteimg{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'图文投票',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('voteimg');
			$where	=array('token'=>$this->token);
			$items 	= $db->where($where)->order('id DESC')->select();

			$arr=array(
			'name'=>'图文投票',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['action_name'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['action_name'],'link'=>'{siteUrl}/index.php?g=Wap&m=Voteimg&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}