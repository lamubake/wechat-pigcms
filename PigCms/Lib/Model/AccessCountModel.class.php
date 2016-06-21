<?php 
class AccessCountModel extends Model {
	
	private $_isRequest = false;
	private $_week = array(518400, 0, 86400, 172800, 259200, 345600, 432000);
	private $_deleteBeforeDay = 70;
	
	protected function _before_insert(&$data, $options) {
		parent::_before_insert($data, $options);
		$data['module'] = strtolower($data['module']);
		$data['controller'] = strtolower($data['controller']);
		$data['action'] = strtolower($data['action']);
		$data['update_time'] = $this->_getTime();
		$data['create_time'] = $this->_getTime();
		return true;
	}

	protected function _before_update(&$data, $options) {
		parent::_before_update($data, $options);
		$data['update_time'] = $this->_getTime();
		return true;
	}
	
	// 不带token 不记录  只记录GET方法请求
	public function setCount($params) {
		if ($this->_isRequest && !IS_GET) {
			return true;
		}
		$params['module'] = strtolower(GROUP_NAME);
		$params['controller'] = strtolower(MODULE_NAME);
		$params['action'] = strtolower(ACTION_NAME);
		if ('wap' == $params['module']) {
			$params['token'] = empty($_GET['token']) ? session('wap_token') : $_GET['token'];
		} else {
			$params['token'] = empty($_GET['token']) ? session('token') : $_GET['token'];
		}
		$params['token'] = empty($params['token']) ? 'alltoken' : $params['token'];;
		$params['create_time'] = array('egt', $this->_getWeekTime());
		$data = $this->where($params)->find();
		if ( 'wap' == $params['module'] && 'alltoken' != $params['token']) {
			session('last_access_url_'.$params['token'], getSelfUrl());
		}
		if ($data && 'alltoken' !== $params['token']) {
			$this->where(array('id'=>$data['id']))->setInc('count');
		} else {
			if ('alltoken' !== $params['token']) {	// 不记录没有TOKEN的页面
				$params['create_time'] = array('lt', $this->_getTime() - ($this->_deleteBeforeDay*86400));
				$this->where($params)->delete();
				$this->add($params);
			}
		}		
		if ('alltoken' !== $params['token']) {
			$params['token'] = 'alltoken';
			$params['create_time'] = array('egt', strtotime(date('Y-m-d')));
			$data = $this->where($params)->find();
			if ($data) {
				$this->where(array('id'=>$data['id']))->setInc('count');
			} else {
				$params['create_time'] = array('lt', $this->_getTime() - ($this->_deleteBeforeDay*86400));
				$this->where($params)->delete();
				$this->add($params);
			}
		}
		$this->_isRequest = true;
	}
	
	private function _getTime() {
		return time();
	}
	
	private function _getWeekTime() {
		return strtotime(date('Y-m-d',$this->_getTime()-$this->_week[date('w')]));
	}
	
	public function getTokenCount($tokens) {
		$data = $this->getData(array(
				'where' => array(
						'token' => array('IN', $tokens),
						'module' => 'wap',
						'create_time' => array('egt', $this->_getWeekTime()),
				)
		));
		return $data['0']['count'];
	}
	
	public function getData($option) {
		$option['field'] = empty($option['field']) ? 'SUM(count) AS count, token,module,controller,update_time,create_time' : $option['field'];
		$option['order'] = empty($option['order']) ? 'count DESC' : $option['limit'];
		$option['limit'] = empty($option['limit']) ? 50 : $option['limit'];
		$percent = $this->_percent($option);
		$result = $this->select($option);
		$tokens = $this->_tokenUser($result);
		foreach ($result as $key => $value) {
			$result[$key]['username'] = $tokens[$value['token']]['username'];
			$result[$key]['wxname'] = $tokens[$value['token']]['wxname'];
			$result[$key]['percent'] = round($value['count'] / $percent * 100, 1);
		}
		return $result;
	}
	
	private function _tokenUser($data) {
		foreach ($data as $key => $value) {
			$tokens[$value['token']] = $value['token'];
		}
		if (empty($tokens)) {
			return array();
		}
		if (1 == count($tokens) && 'alltoken' == $tokens[0]['token']) {
			return array('token'=>'全部用户');
		}
		$data = M('Wxuser')->alias('wx')->join(
				'LEFT JOIN '.C('DB_PREFIX').'users AS user ON wx.uid = user.id'
		)->where(
				array('token'=>array('IN', $tokens))
		)->field(
				'user.username,wx.token,wx.wxname'
		)->select();
		foreach ($data as $key => $value) {
			$result[$value['token']]['username'] = $value['username'];
			$result[$value['token']]['wxname'] = $value['wxname'];
		}
		return $result;
	}
	
	private function _percent($option) {
		$option['field'] = 'SUM(count) as count';
		unset($option['group']);
		$result = $this->find($option);
		return $result['count'];		
	}
}