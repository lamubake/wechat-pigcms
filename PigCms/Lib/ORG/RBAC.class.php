<?php

class RBAC
{
	static public function authenticate($map, $model = '')
	{
		if (empty($model)) {
			$model = C('USER_AUTH_MODEL');
		}

		return M($model)->where($map)->find();
	}

	static public function saveAccessList($authId = NULL)
	{
		if (NULL === $authId) {
			$authId = $_SESSION[C('USER_AUTH_KEY')];
		}

		if ((C('USER_AUTH_TYPE') != 2) && !$_SESSION[C('ADMIN_AUTH_KEY')]) {
			$_SESSION['_ACCESS_LIST'] = RBAC::getAccessList($authId);
		}

		return NULL;
	}

	static public function getRecordAccessList($authId = NULL, $module = '')
	{
		if (NULL === $authId) {
			$authId = $_SESSION[C('USER_AUTH_KEY')];
		}

		if (empty($module)) {
			$module = MODULE_NAME;
		}

		$accessList = RBAC::getModuleAccessList($authId, $module);
		return $accessList;
	}

	static public function checkAccess()
	{
		if (C('USER_AUTH_ON')) {
			$_module = array();
			$_action = array();

			if ('' != C('REQUIRE_AUTH_MODULE')) {
				$_module['yes'] = explode(',', strtoupper(C('REQUIRE_AUTH_MODULE')));
			}
			else {
				$_module['no'] = explode(',', strtoupper(C('NOT_AUTH_MODULE')));
			}

			if ((!empty($_module['no']) && !in_array(strtoupper(MODULE_NAME), $_module['no'])) || (!empty($_module['yes']) && in_array(strtoupper(MODULE_NAME), $_module['yes']))) {
				if ('' != C('REQUIRE_AUTH_ACTION')) {
					$_action['yes'] = explode(',', strtoupper(C('REQUIRE_AUTH_ACTION')));
				}
				else {
					$_action['no'] = explode(',', strtoupper(C('NOT_AUTH_ACTION')));
				}

				if ((!empty($_action['no']) && !in_array(strtoupper(ACTION_NAME), $_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper(ACTION_NAME), $_action['yes']))) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}

		return false;
	}

	static public function checkLogin()
	{
		if (RBAC::checkAccess()) {
			if (!$_SESSION[C('USER_AUTH_KEY')]) {
				if (C('GUEST_AUTH_ON')) {
					if (!isset($_SESSION['_ACCESS_LIST'])) {
						RBAC::saveAccessList(C('GUEST_AUTH_ID'));
					}
				}
				else {
					redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
				}
			}
		}

		return true;
	}

	static public function AccessDecision($appName = APP_NAME)
	{
		if (RBAC::checkAccess()) {
			$accessGuid = md5($appName . MODULE_NAME . ACTION_NAME);

			if (empty($_SESSION[C('ADMIN_AUTH_KEY')])) {
				if (C('USER_AUTH_TYPE') == 2) {
					$accessList = RBAC::getAccessList($_SESSION[C('USER_AUTH_KEY')]);
				}
				else {
					if ($_SESSION[$accessGuid]) {
						return true;
					}

					$accessList = $_SESSION['_ACCESS_LIST'];
				}

				$module = (defined('P_MODULE_NAME') ? P_MODULE_NAME : MODULE_NAME);

				if (!isset($accessList[strtoupper($appName)][strtoupper($module)][strtoupper(ACTION_NAME)])) {
					$_SESSION[$accessGuid] = false;
					return false;
				}
				else {
					$_SESSION[$accessGuid] = true;
				}
			}
			else {
				return true;
			}
		}

		return true;
	}

	static public function getAccessList($authId)
	{
		$db = Db::getInstance(C('RBAC_DB_DSN'));
		$table = array('role' => C('DB_PREFIX') . 'role', 'user' => C('DB_PREFIX') . 'role_user', 'access' => C('DB_PREFIX') . 'access', 'node' => C('DB_PREFIX') . 'node');
		$sql = 'select node.id,node.name from ' . $table['role'] . ' as role,' . $table['user'] . ' as user,' . $table['access'] . ' as access ,' . $table['node'] . ' as node ' . 'where user.user_id=\'' . $authId . '\' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=1 and node.status=1';
		$apps = $db->query($sql);
		$access = array();

		foreach ($apps as $key => $app) {
			$appId = $app['id'];
			$appName = $app['name'];
			$access[strtoupper($appName)] = array();
			$sql = 'select node.id,node.name from ' . $table['role'] . ' as role,' . $table['user'] . ' as user,' . $table['access'] . ' as access ,' . $table['node'] . ' as node ' . 'where user.user_id=\'' . $authId . '\' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=2 and node.status=1';
			$modules = $db->query($sql);
			$publicAction = array();

			foreach ($modules as $key => $module) {
				$moduleId = $module['id'];
				$moduleName = $module['name'];

				if ('PUBLIC' == strtoupper($moduleName)) {
					$sql = 'select node.id,node.name from ' . $table['role'] . ' as role,' . $table['user'] . ' as user,' . $table['access'] . ' as access ,' . $table['node'] . ' as node ' . 'where user.user_id=\'' . $authId . '\' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=3 and node.pid=' . $moduleId . ' and node.status=1';
					$rs = $db->query($sql);

					foreach ($rs as $a) {
						$publicAction[$a['name']] = $a['id'];
					}

					unset($modules[$key]);
					break;
				}
			}

			foreach ($modules as $key => $module) {
				$moduleId = $module['id'];
				$moduleName = $module['name'];
				$sql = 'select node.id,node.name from ' . $table['role'] . ' as role,' . $table['user'] . ' as user,' . $table['access'] . ' as access ,' . $table['node'] . ' as node ' . 'where user.user_id=\'' . $authId . '\' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and access.node_id=node.id and node.level=3 and node.pid=' . $moduleId . ' and node.status=1';
				$rs = $db->query($sql);
				$action = array();

				foreach ($rs as $a) {
					$action[$a['name']] = $a['id'];
				}

				$action += $publicAction;
				$access[strtoupper($appName)][strtoupper($moduleName)] = array_change_key_case($action, CASE_UPPER);
			}
		}

		return $access;
	}

	static public function getModuleAccessList($authId, $module)
	{
		$db = Db::getInstance(C('RBAC_DB_DSN'));
		$table = array('role' => C('DB_PREFIX') . 'role', 'user' => C('DB_PREFIX') . 'role_user', 'access' => C('DB_PREFIX') . 'access');
		$sql = 'select access.node_id from ' . $table['role'] . ' as role,' . $table['user'] . ' as user,' . $table['access'] . ' as access ' . 'where user.user_id=\'' . $authId . '\' and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=0 ) ) and role.status=1 and  access.module=\'' . $module . '\' and access.status=1';
		$rs = $db->query($sql);
		$access = array();

		foreach ($rs as $node) {
			$access[] = $node['node_id'];
		}

		return $access;
	}
}


?>
