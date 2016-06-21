<?php
class FunctionLibrary_Autumns{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if(!$this->sub){
			return array(
			'name'=>'拆礼盒',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else{
			$where	=array('token'=>$this->token);
			$items 	= M('Activity')->where($where)->order('id DESC')->select();
			$arr=array(
				'name'=>'拆礼盒',
				'subkeywords'=>array(
				),
				'sublinks'=>array(
				),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['title'],'keyword'=>$v['keyword']);
					$v['id']=M('Lottery')->where(array('zjpic'=>$v['id']))->getField('id');
					$arr['sublinks'][$v['id']]=array('name'=>$v['title'],'link'=>'{siteUrl}/index.php?g=Wap&m=Autumns&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['id']);
				}
			}
			return $arr;
		}		
	}
}