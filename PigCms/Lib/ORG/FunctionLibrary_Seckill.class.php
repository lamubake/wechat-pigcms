<?php
class FunctionLibrary_Seckill{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'微秒杀',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('Seckill_action');
			$where	=array('action_token'=>$this->token);
			$items 	= $db->where($where)->order('action_id DESC')->select();

			$arr=array(
			'name'=>'微秒杀',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['action_id']]=array('name'=>$v['reply_title'],'keyword'=>$v['reply_keyword']);
					$arr['sublinks'][$v['action_id']]=array('name'=>$v['reply_title'],'link'=>'{siteUrl}/index.php?g=Wap&m=Seckill&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['action_id']);
				}
			}
			return $arr;	
		}
	}
}