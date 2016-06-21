<?php
class getUserInfoModule {

	public function __construct(){
		
	}
	public function index(){
		return array(
		//module_name=>array('action_name'),
		'Card'=>array('card'),
		'Crowdfunding'=>array('index'),
		'Forum'=>array('index'),
		//'Store'=>array('index'),
		);
	}
}
