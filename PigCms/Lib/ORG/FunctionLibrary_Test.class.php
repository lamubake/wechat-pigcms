<?php
class FunctionLibrary_Test{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'趣味测试',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('test');
			$where	=array('token'=>$this->token);
			
/*			$count  = $db->where($where)->count();
			$Page   = new Page($count,5);
			$show   = $Page->show();
			$list 	= $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();*/
			$items 	= $db->where($where)->select();

			$arr=array(
			'name'=>'趣味测试',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['imicms_id']]=array('name'=>$v['name'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['imicms_id']]=array('name'=>$v['name'],'link'=>'{siteUrl}/index.php?g=Wap&m=Test&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['imicms_id']);
				}
			}
			return $arr;	
		}
	}
}