<?php
	class Member_card_giftsModel extends Model{
		protected $_validate = array(
				array('name','require','优惠名称不能为空',1),
				array('start','require','持续时间不能为空',1),
				array('end','require','持续时间不能为空',1),
				array('item_value','require','赠送选项不能为空',1),
		);
}

?>