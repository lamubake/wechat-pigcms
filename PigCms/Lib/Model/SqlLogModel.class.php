<?php
class SqlLogModel extends Model {
	private $_id;
	private $_status;
	private $_result;
	private $_exception;
	private $_errno = 0;
	private $_allowErrorCode = array(1050, 1054, 1060, 1061, 1062, 1091, 1146);
	
	public function run($result) {
		$this->_result = $result;
		$this->_start();
		$this->_commit();
		$this->_end();
		return $this->_status;
	}
	
	private function _start() {
		$data = $this->where(array('time'=>$this->_result['time']))->find();
		if ($data) {
			$this->_id = $data['id'];
		} else {
			if ($this->_result['time']) {
				$this->_id = $this->add(array('time'=>$this->_result['time'], 'create_time'=>time(), 'hash'=>sha1($this->_result['sql']), 'status'=>0));
			}
		}
		$this->_error('start');
		return true;
	}
	
	private function _commit() {
		//error_reporting(0);
		$link = $this->db->connect();
		$sql = str_replace('{tableprefix}',C('DB_PREFIX'),$this->_result['sql']);
		try {
			$this->_status = @mysql_query($sql, $link);
			if (empty($this->_status)) {
				$this->_status = $this->query($sql);
			}
		} catch (Exception $e) {
			$this->_status = 0;
			$this->_errno = mysql_errno();
			if (in_array($this->_errno, $this->_allowErrorCode)) {
				$this->_status = 1;
			}
			$this->_exception = '[CODE] : '.$this->_errno.' # '.$e->getMessage();
		}
		
	}
	
	private function _end() {
		$result = $this->where(array('id'=>$this->_id))->save(array('status'=>(Int) $this->_status, 'code' => $this->_errno, 'update_time'=>time(), 'exception'=>$this->_exception ));
		if (empty($result)) {
			$this->_id = null;
		}		
		$this->_error('end');
		return true;
	}
	
	private function _error($message) {
		if (empty($this->_id)) {
			exit('id '.$this->_id.'升级日志写入失败 '.$message."\n Status: ".$this->_result['success']."\n Message: ".$this->_result['msg']);
		}
	}
}