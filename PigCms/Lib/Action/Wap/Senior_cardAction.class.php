<?php
class Senior_cardAction extends WapAction
{
	public function _initialize()
	{
	}

	public function viewData()
	{
		$greeting_card = D('Cards')->where(array('id' => intval($_GET['card_id'])))->setInc('viewcount');
	}
}

?>
