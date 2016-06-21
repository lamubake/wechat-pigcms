<?php
class SiteMessageModel extends Model {
	
	protected function _before_insert(&$data, $options) {
		parent::_before_insert($data, $options);
		$data['create_time'] = time();
		return true;
	}
}