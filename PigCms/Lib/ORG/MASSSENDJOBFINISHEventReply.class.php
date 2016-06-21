<?php

class MASSSENDJOBFINISHEventReply{
	public $token;
	public $FromUserName;
	public $data;
	public $siteUrl;

	function __constract($token,$FromUserName,$data,$siteUrl){
		$this->token 	= $token;
		$this->FromUserName = $FromUserName;
		$this->data 	= $data;
		$this->siteUrl 	= $siteUrl;
	}

	function index(){
		if($this->data['Status'] == 'send success'){
			return M('Send_message')->where(array('msg_id'=>$this->data['MsgID']))->save(array('reachcount'=>$this->data['SentCount'],'status'=>2));
		}else{
			return M('Send_message')->where(array('msg_id'=>$this->data['MsgID']))->save(array('status'=>3));
		}
		
	}
}


?>