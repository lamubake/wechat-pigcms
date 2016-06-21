 <?php

class OpenApiAction extends BaseAction{

	public function _initialize() {
		parent::_initialize();
	}	


	public function get_access_token(){

		$post 	= array(
			'appid' 		=> $this->_post('appid','trim'),
		);

		$info 	= M('Wxuser')->where("appid='{$post['appid']}'")->getField('id');

		if(empty($info)) {
			
			$return 	= array(
				'errCode' 	=> 10001,
				'errMsg' 	=> '无效的appid',
			);

		} else {

			$apiOauth 		= new apiOauth();

			$access_token  	= $apiOauth->update_authorizer_access_token($post['appid']);

			$return 	= array(
				'errCode' 	=> 0,
				'errMsg' 	=> 'success',
				'access_token' 	=> $access_token,
			);

		}

		echo json_encode($return);
	}


	public function get_js_ticket(){

		$post 	= array(
			'appid' 		=> $this->_post('appid','trim'),
			'access_token' 	=> $this->_post('access_token','trim'),
		);

		$info 	= M('Wxuser')->where("appid='{$post['appid']}'")->getField('id');

		if(empty($info)) {

			$return 	= array(
				'errCode' 	=> 10002,
				'errMsg' 	=> '无效的appid'
			);

		} else {

			$apiOauth 		= new apiOauth();

			$apiTicket  	= $apiOauth->getAuthorizerTicket($post['appid'],$post['access_token']);

			if(empty($apiTicket)) {

				$return 	= array(
					'errCode' 	=> 10003,
					'errMsg' 	=> '获取ticket失败'
				);

			} else {

				$return 	= array(
					'errCode' 	=> 0,
					'errMsg' 	=> 'success',
					'ticket' 	=> $apiTicket
				);

			}
		}

		echo json_encode($return);

	}
}
?>