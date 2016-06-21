<?php
class WallAction extends WapAction{
	public $wall_model;
	public $wecha_id;
	public $token;
	public function __construct(){
		parent::_initialize();
		$this->token		= $this->_get('token');
		$this->assign('token',$this->token);
		$this->wecha_id	= $this->wecha_id;
		if (!$this->wecha_id){
			$this->wecha_id='null';
		}
		$this->assign('wecha_id',$this->wecha_id);
		$this->wall_model=M('Wall');
	}
	public function index(){
		if (!$this->wecha_id){
			$this->error('您无权参与微信墙','');
		}
		if (IS_POST){
			$wallRow=array();
			$wallRow['wecha_id']=$this->wecha_id;//need update
			$wallRow['token']=$this->token;
			//
			$thisWall=M('Wall')->where(array('token'=>$wallRow['token']))->find();
			if ($thisWall){
				$wallRow['wallid']=$thisWall['id'];
				$wallRow['portrait']=$this->_post('portrait');//need update
				$wallRow['nickname']=$this->_post('nickname');//need update
				$wallRow['mp']=$this->_post('mp');//need update
				$wallRow['time']=time();

				$wallRowExist=M('Wall_member')->where(array('wallid'=>intval($thisWall['id']),'wecha_id'=>$wallRow['wecha_id']))->find();
				if ($wallRowExist){
					M('Wall_member')->where(array('wallid'=>intval($thisWall['id']),'wecha_id'=>$wallRow['wecha_id']))->save($wallRow);
				}else {
					M('Wall_member')->add($wallRow);
				}
				echo 1;
			}
		}else {
			$thisWall=M('Wall')->where(array('token'=>$this->token))->find();
			$wallRowExist=M('Wall_member')->where(array('wallid'=>intval($thisWall['id']),'wecha_id'=>$this->wecha_id))->find();
			$this->assign('info',$wallRowExist);
			$this->display();
		}
	}
	public function person(){
		$wallRow=array();
		$wallRow['wecha_id']=$this->wecha_id;//need update
		$wallRow['token']=$this->token;
		$wallRow['wallid']=intval($_GET['id']);
		$wallRow['portrait']=$this->fans['portrait'];//need update
		$wallRow['nickname']=$this->fans['truename'];//need update
		$wallRow['mp']=$this->fans['tel'];//need update
		$wallRow['time']=time();
		$wallRowExist=M('Wall_member')->where(array('wallid'=>$wallRow['wallid'],'wecha_id'=>$wallRow['wecha_id']))->find();
		if ($wallRowExist){
			M('Wall_member')->where(array('wallid'=>intval($wallRow['wallid']),'wecha_id'=>$wallRow['wecha_id']))->save($wallRow);
		}else {
			M('Wall_member')->add($wallRow);
		}
				
		$this->success('设置成功，关掉该页面，进入微信对话框留言就行了','');
	}
	
	/*
<?php
class WallAction extends WapAction{
	public $wall_model;
	public $wecha_id;
	public $token;
	public $act_type;
	public function __construct(){
		parent::_initialize();


		//普通活动或者现场活动
		$this->act_type 	= $this->_get('act_type','intval');
		if(!in_array($this->act_type,array('1','3')) || !$this->wecha_id){
			echo '参数错误';
			exit();
		}


		$this->wall_model=M('Wall');
	}
	public function index(){

		$where 		= array('wecha_id'=>$this->wecha_id,'token'=>$this->token,'act_id'=>$this->_get('id','
			intval'),'act_type'=>$this->act_type);

		$member 	= M('wall_member')->where($where)->find();
		dump($member);
		if (!$member){
			$this->error('请先填写个人信息',U('Scene_member/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'act_id'=>$where['act_id'],'act_type'=>$where['act_type'])));
			exit();
		}


		$this->display();
	}
	public function person(){
		$wallRow=array();
		$wallRow['wecha_id']=$this->wecha_id;//need update
		$wallRow['token']=$this->token;
		$wallRow['wallid']=intval($_GET['id']);
		$wallRow['portrait']=$this->fans['portrait'];//need update
		$wallRow['nickname']=$this->fans['truename'];//need update
		$wallRow['mp']=$this->fans['tel'];//need update
		$wallRow['time']=time();
		$wallRowExist=M('Wall_member')->where(array('wallid'=>$wallRow['wallid'],'wecha_id'=>$wallRow['wecha_id']))->find();
		if ($wallRowExist){
			M('Wall_member')->where(array('wallid'=>intval($wallRow['wallid']),'wecha_id'=>$wallRow['wecha_id']))->save($wallRow);
		}else {
			M('Wall_member')->add($wallRow);
		}
				
		$this->success('设置成功，关掉该页面，进入微信对话框留言就行了','');
	}
	
}
?>
	*/
}
?>