<?php

class StaffIndexAction extends UserAction
{
	public $first_func;
	public $token;

	protected function _initialize()
	{
		parent::_initialize();
		$first_func = $this->_get('first_func', 'trim');
		$this->first_func = $first_func;
		$this->token = $this->token;
		$this->canUseFunction($first_func);
	}

	public function index()
	{
		$trans = include './PigCms/Lib/ORG/FuncToModel.php';
		$first_func = (isset($trans[$this->first_func]) ? ucfirst($trans[$this->first_func]) : ucfirst($this->first_func));

		if ($first_func == 'Home') {
			$function = 'set';
		}

		header('Location:/index.php?g=User&m=' . $first_func . '&a=' . $function . '&token=' . $this->token);
	}
}

?>
