<?php

class BackAction extends BaseAction
{
	protected $pid;

	protected function _initialize()
	{
		$sql = 'SHOW COLUMNS FROM `' . C('DB_PREFIX') . 'user`';
		$COLUMNS = M()->query($sql);

		foreach ($COLUMNS as $vo) {
			$COLUMNS_array[] = $vo['Field'];
		}

		if (!in_array('is_admin', $COLUMNS_array)) {
			$sql = 'ALTER TABLE `' . C('DB_PREFIX') . 'user` ADD `is_admin` INT NOT NULL DEFAULT \'0\'';
			M()->query($sql);
		}
        if (C('safeadmin')<>'' && $_SESSION['safeadmin'] <> C('safeadmin')){
				$this->error('非法操作!', U('System/Admin/index'));
		}
		
		if (!isset($_SESSION['username'])) {
			$this->error('非法操作', U('System/Admin/index'));
		}

		parent::_initialize();
		C('NOT_AUTH_ACTION', '');
		C('NOT_AUTH_MODULE', 'Admin');
		if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
			if (!RBAC::AccessDecision()) {
				if (!$_SESSION[C('USER_AUTH_KEY')]) {
					redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
				}

				if (C('RBAC_ERROR_PAGE')) {
					redirect(C('RBAC_ERROR_PAGE'));
				}
				else {
					if (C('GUEST_AUTH_ON')) {
						$this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
					}

					$this->error(L('_VALID_ACCESS_'));
				}
			}
		}

		$this->show_menu();
	}

	private function show_menu()
	{
		$thisPid = M('Node')->where(array('name' => MODULE_NAME, 'level' => 2, 'status' => 1, 'display' => 2))->getField('id');

		if ($this->_get('pid', 'intval')) {
			$this->pid = $this->_get('pid', 'intval');
		}
		else if ($thisPid) {
			$this->pid = $thisPid;
		}
		else {
			$this->pid = 2;
		}

		$where['level'] = $this->_get('level', 'intval') ? $this->_get('level', 'intval') : 3;
		$where['pid'] = $this->pid;
		$title = rawurldecode($this->_get('title'));
		$where['status'] = 1;
		$where['display'] = array('gt', 0);
		$order['sort'] = 'asc';
		$nav = M('Node')->where($where)->order($order)->select();
		$this->assign('title', $title);
		$this->assign('nav', $nav);
	}
}

?>
