<?php
class Diymen_classModel extends Model{
	protected $_validate = array(
		array('title','require','菜单名称必须填写',1),
		array('keyword', 'checkKeyword', '关键字必须填写', 1, 'callback'),
		array('url', 'checkUrl', 'URL链接必须填写', 1, 'callback'),
		array('wxsys', 'checkWxsys', '微信扩展必须选择', 1, 'callback'),
		array('tel', 'checkTel', '手机号码必须填写', 1, 'callback'),
		array('longitude,latitude', 'checkNav', '导航必须填写不能为空', 1, 'callback'),

	 );
	protected $_auto = array (		
		array('token','getToken',Model:: MODEL_BOTH,'callback'),
		array('nav','setNav',Model:: MODEL_BOTH,'callback'),
	);
	
	protected function getToken(){	
		return $_SESSION['token'];
	}
	
	/**
	 * 设置导航坐标
	 * @return string
	 */
	protected function setNav() {
		if($_POST['longitude'] && $_POST['latitude']) {
			return $_POST['longitude'].','.$_POST['latitude'];
		}
	}
	
	/**
	 * 验证关键字 2015-05-19
	 * @param String $data
	 * @return boolean
	 */
	protected function checkKeyword($data) {
		if (1 == $_POST['menu_type']) {
			if (!$this->check($data, 'require')) {
				return false;
			}
		}
	}

	/**
	 * 验证URL 2015-05-19
	 * @param String $data
	 * @return boolean
	 */
	protected function checkUrl($data) {
		if (2 == $_POST['menu_type']) {
			if (!$this->check($data, 'require')) {
				return false;
			}
		}
	}

	/**
	 * 验证微信扩展 2015-05-19
	 * @param String $data
	 * @return boolean
	 */
	protected function checkWxsys($data) {
		if (3 == $_POST['menu_type']) {
			if (!$this->check($data, 'require')) {
				return false;
			}
		}
	}

	/**
	 * 验证手机号码 2015-05-19
	 * @param String $data
	 * @return boolean
	 */
	protected function checkTel($data) {
		if (4 == $_POST['menu_type']) {
			if (!$this->check($data, 'require')) {
				return false;
			}
		}
	}

	/**
	 * 验证导航 2015-05-19
	 * @param String $data
	 * @return boolean
	 */
	protected function checkNav($data) {
		if (5 == $_POST['menu_type']) {
			if (!$this->check($data['longitude'], 'require') || !$this->check($data['latitude'], 'require')) {
				return false;
			}
		}
	}
}

?>
