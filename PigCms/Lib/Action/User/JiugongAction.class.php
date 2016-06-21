<?php
class JiugongAction extends LotteryBaseAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Jiugong');
	}
	public function cheat(){
		parent::cheat();
		$this->display();
	}
	public function index(){
		parent::index(10);
		$this->display();
	
	}
	public function sn(){
		$type=isset($_GET['type'])?intval($_GET['type']):10;
		parent::sn($type);
		$this->display();
	}
	public function add(){
		parent::add(10);
	}
	
	public function edit(){
		parent::edit(10);
	}
}


?>