<?php
class FunctionLibrary_Cutprice{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'降价拍',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('cutprice');
			$where	=array('token'=>$this->token);
			$items 	= $db->where($where)->select();

			$arr=array(
			'name'=>'降价拍',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['imicms_id']]=array('name'=>$v['name'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['imicms_id']]=array('name'=>$v['name'],'link'=>'{siteUrl}/index.php?g=Wap&m=Cutprice&a=goods&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['imicms_id']);
				}
			}
			return $arr;	
		}
	}
}