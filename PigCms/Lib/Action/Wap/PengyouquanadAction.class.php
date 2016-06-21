<?php
class PengyouquanadAction extends WapAction{

    public function __construct(){
        parent::_initialize();
    }

	public function index(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		$token		= $this->_get('token');
		$wecha_id	= $this->wecha_id;
		
		//活动项目信息
		$items_db	    = M('Pengyouquanad');
		$where 		= array('token'=>$token,'status'=>'1');
		$items 	    = $items_db->where($where)->find();
		
		if(empty($items)){
            exit('系统错误，未找到项目，请稍后再试');
        }
		
		//微信用户信息
		$user_db	    = M('Userinfo');
		$where 		= array('token'=>$token,'wecha_id'=>$wecha_id);
		$user 	    = $user_db->where($where)->find();

        if(empty($user)){
            exit('系统错误，未能识别用户信息，请稍后再试');
        }


        $this->assign('item',$items);
        $this->assign('user',$user);
		$this->display();
	}

}?>