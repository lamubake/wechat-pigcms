<?php
class AccessCountBehavior extends Behavior {
	
	private $_allowList = array('user', 'wap'); // 统一小写
	private $_denyList = array(
		'user'=> array('index'=>array('del')),
	);
	
	public function run(&$params) {
		if ($this->_accessList()) {
			D('AccessCount')->setCount($params);
		}
	}	
	
	private function _accessList() {
		$module = strtolower(GROUP_NAME);
		if (in_array($module, $this->_allowList)) {
			$controller = strtolower(MODULE_NAME);
			$action = strtolower(ACTION_NAME);
			$denyModule = isset($this->_denyList[$module]) ? $this->_denyList[$module] : array();
			$denyController = isset($denyModule[$controller]) ? $denyModule[$controller] : array();
			if (is_array($denyController)) {
				return in_array($action, $denyController) ? false : true;
			} else if ('*' == $denyController) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
}