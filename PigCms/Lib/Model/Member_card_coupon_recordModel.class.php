<?php
class Member_card_coupon_recordModel extends Model
{
	public function get_coupon($wechat_id, $token, $price)
	{
		$nowtime = time();
		$sql = "SELECT c.least_cost, c.reduce_cost, c.info, r.* FROM " . C('DB_PREFIX') . "member_card_coupon AS c INNER JOIN " . C('DB_PREFIX') . "member_card_coupon_record AS r ON r.coupon_id=c.id INNER JOIN " . C('DB_PREFIX') . "member_card_set AS s ON r.cardid=s.id WHERE r.is_use='0' AND r.coupon_type='2' AND c.is_delete='0' AND c.statdate<'{$nowtime}' AND c.enddate>'{$nowtime}' AND r.token='{$token}' AND r.wecha_id='{$wechat_id}' AND c.least_cost<='{$price}'";
		$result = D('')->query($sql);
		if ($result) {
			foreach ($result as $row) {
				$coupon_attr = unserialize($row['coupon_attr']);
				$row['name'] = "{$coupon_attr['coupon_name']}[满{$coupon_attr['least_cost']}减{$coupon_attr['reduce_cost']}]";
				$list[$row['id']] = $row;
			}
			return $list;
		}
		return false;
	}
	
	public function check_coupon($id, $wechat_id, $token, $price)
	{
		$nowtime = time();
		$record = $this->where(array('token' => $token, 'wecha_id' => $wechat_id, 'id' => $id))->find();
		if (empty($record)) return array('error' => true, 'msg' => '优惠券不存在');
		if (intval($record['is_use']) == 1) return array('error' => true, 'msg' => '优惠券已使用');
		$coupon = D('Member_card_coupon')->where(array('id' => $record['coupon_id']))->find();
		if (empty($coupon)) return array('error' => true, 'msg' => '优惠券不存在');
		if ($coupon['statdate'] > $nowtime) return array('error' => true, 'msg' => '优惠券没有到使用时期');
		if ($coupon['enddate'] < $nowtime) return array('error' => true, 'msg' => '优惠券已过期');
		if ($coupon['least_cost'] > $price) return array('error' => true, 'msg' => '消费金额没有满足使用该券的条件');
		if ($coupon['is_delete']) return array('error' => true, 'msg' => '优惠券已删除');
		$card = D('Member_card_set')->where(array('id' => $record['cardid']))->find();
		if (empty($card)) return array('error' => true, 'msg' => '会员卡已删除');
		$coupon_attr = unserialize($record['coupon_attr']);
		return array('error' => false, 'data' => array('id' => $record['id'], 'least_cost' => $coupon_attr['least_cost'], 'reduce_cost' => $coupon_attr['reduce_cost'], 'cancel_code' => $record['cancel_code'], 'card_id' => $record['card_id'], 'is_weixin' => $coupon['is_weixin'])); 
	}
	
	public function use_coupon($id, $wechat_id, $token, $price)
	{
		$result = $this->check_coupon($id, $wechat_id, $token, $price);
		if ($result['error']) return $result;
		$this->where(array('token' => $token, 'wecha_id' => $wechat_id, 'id' => $id))->save(array('is_use' => '1', 'use_time' => time(), 'staff_id' => -1));
		return array('error' => false);
	}
}
