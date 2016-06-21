<?php

class NumqueueReply
{
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;

	public function __construct($token, $wechat_id, $data, $siteUrl)
	{
		$this->item = M('numqueue_action')->where(array('id' => $data['pid']))->find();
		$this->wechat_id = $wechat_id;
		$this->siteUrl = $siteUrl;
		$this->token = $token;
	}

	public function index()
	{
		$thisItem = $this->item;
		$numqueue_admin = M('company_staff')->where(array('wecha_id' => $this->wechat_id, 'token' => $this->token))->find();

		if (empty($numqueue_admin)) {
			return array(
	array(
		array($thisItem['reply_title'], $thisItem['reply_content'], $thisItem['reply_pic'], $this->siteUrl . U('Wap/Numqueue/index', array('id' => $thisItem['id'], 'token' => $this->token, 'wecha_id' => $this->wechat_id)))
		),
	'news'
	);
		}
		else {
			return array(
	array(
		array($thisItem['reply_title'], $thisItem['reply_content'], $thisItem['reply_pic'], $this->siteUrl . U('Wap/Numqueue/index', array('id' => $thisItem['id'], 'token' => $this->token, 'wecha_id' => $this->wechat_id))),
		array('店员管理号单', '', $thisItem['reply_pic'], $this->siteUrl . '/index.php?g=Wap&m=Numqueue&a=admin_manage_list&id=' . $thisItem['id'] . '&token=' . $this->token . '&wecha_id=' . $this->wechat_id . '')
		),
	'news'
	);
		}
	}
}


?>
