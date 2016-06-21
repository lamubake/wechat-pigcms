<?php

class CheckStaff
{
	public $token;
	public $wecha_id;
	public $username;
	public $password;
	public $modelname;
	public $jumpUrl;

	public function __construct($parameters)
	{
		$this->token = $parameters['token'];
		$this->wecha_id = $parameters['wecha_id'];
		$this->username = $parameters['username'];
		$this->password = $parameters['password'];
		$this->modelName = $parameters['modelName'];
	}

	public function check_login()
	{
		if (empty($this->token)) {
			return json_encode(array('error_code' => 1000, 'error_msg' => 'token不能为空'));
		}

		if (empty($this->modelName)) {
			return json_encode(array('error_code' => 1001, 'error_msg' => '所属模块不能为空'));
		}

		$is_staff = M('company_staff')->where(array(
	'token'    => $this->token,
	'pcorwap'  => array('neq', 1),
	'wecha_id' => $this->wecha_id
	))->find();

		if (!empty($is_staff)) {
			return json_encode(array('error_code' => 0, 'error_msg' => 'success'));
		}
		else {
			if (($this->username != '') && ($this->password != '')) {
				$is_staff = M('company_staff')->where(array('token' => $this->token, 'username' => $this->username, 'password' => $this->password))->find();

				if (empty($is_staff)) {
					return json_encode(array('error_code' => 1005, 'error_msg' => '登陆失败,用户名密码错误'));
				}
				else {
					if ($is_staff['pcorwap'] == 1) {
						return json_encode(array('error_code' => 1005, 'error_msg' => '您的权限类型只适用于电脑端'));
					}

					$func = explode(',', $is_staff['func']);
					if (!in_array($this->modelName, $func) || empty($func)) {
						return json_encode(array('error_code' => 1006, 'error_msg' => '你没有该模块的访问权限,请联系你的管理员'));
					}
					else {
						return json_encode(array('error_code' => 0, 'error_msg' => 'success'));
					}
				}
			}
			else {
				return json_encode(array('error_code' => 1007, 'error_msg' => '登陆失败,没有设置手机端的店员'));
			}
		}
	}
}


?>
