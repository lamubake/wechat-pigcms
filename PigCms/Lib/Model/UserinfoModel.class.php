<?php
class UserinfoModel extends Model {
		
	public function isSub($token, $openid) {
		$where['token'] = $token;
		$where['wecha_id'] = $openid;
		$issub = $this->where("token='$token' AND (wecha_id='$openid' OR fakeopenid='$openid')")->limit(1)->getField('issub');
		return $issub == '1' ? true : false;
	}
	
	/**
	 * 重置附属表wecha_id
	 * @param Object $model
	 * @param Array $params
	 */
	public function convertFake($model, $params) {
		if (count($params) > 3) {
			exit('param error');
		}
		foreach ($params as $key => $value) {
			if ('token' == $key || 'fakeopenid' == $key) continue;
			$field = $key;
			$wecha_id = $value;			
		}
		$token = $params['token'];
		$fakeopenid = $params['fakeopenid'];
		if ($this->isSub($token, $wecha_id) && $wecha_id != $fakeopenid) {
			if (!empty($fakeopenid)) {
				$model->where(array('token'=>$token, $field=>$fakeopenid))->setField($field, $wecha_id);
			}
		}
	}
}