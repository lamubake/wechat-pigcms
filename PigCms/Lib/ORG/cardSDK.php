<?php

class Sku
{
	public function __construct($quantity)
	{
		$this->quantity = $quantity;
	}
}

class DateInfo
{
	public function __construct($type, $arg0, $arg1 = NULL)
	{
		if (!is_int($type)) {
			exit('DateInfo.type must be integer');
		}

		$this->type = $type;

		if ($type == 1) {
			if (!is_int($arg0) || !is_int($arg1)) {
				exit('begin_timestamp and  end_timestamp must be integer');
			}

			$this->begin_timestamp = $arg0;
			$this->end_timestamp = $arg1;
		}
		else if ($type == 2) {
			if (!is_int($arg0)) {
				exit('fixed_term must be integer');
			}

			$this->fixed_term = $arg0;
		}
		else {
			exit('DateInfo.tpye Error');
		}
	}
}

class BaseInfo
{
	public function __construct($logo_url, $brand_name, $code_type, $title, $color, $notice, $service_phone, $description, $date_info, $sku)
	{
		if (!$date_info instanceof DateInfo) {
			exit('date_info Error');
		}

		if (!$sku instanceof Sku) {
			exit('sku Error');
		}

		if (!is_int($code_type)) {
			exit('code_type must be integer');
		}

		$this->logo_url = $logo_url;
		$this->brand_name = $brand_name;
		$this->code_type = $code_type;
		$this->title = $title;
		$this->color = $color;
		$this->notice = $notice;
		$this->service_phone = $service_phone;
		$this->description = $description;
		$this->date_info = $date_info;
		$this->sku = $sku;
	}

	public function set_sub_title($sub_title)
	{
		$this->sub_title = $sub_title;
	}

	public function set_use_limit($use_limit)
	{
		if (!is_int($use_limit)) {
			exit('use_limit must be integer');
		}

		$this->use_limit = $use_limit;
	}

	public function set_get_limit($get_limit)
	{
		if (!is_int($get_limit)) {
			exit('get_limit must be integer');
		}

		$this->get_limit = $get_limit;
	}

	public function set_use_custom_code($use_custom_code)
	{
		$this->use_custom_code = $use_custom_code;
	}

	public function set_bind_openid($bind_openid)
	{
		$this->bind_openid = $bind_openid;
	}

	public function set_can_share($can_share)
	{
		$this->can_share = $can_share;
	}

	public function set_location_id_list($location_id_list)
	{
		$this->location_id_list = $location_id_list;
	}

	public function set_url_name_type($url_name_type)
	{
		if (!is_int($url_name_type)) {
			exit('url_name_type must be int');
		}

		$this->url_name_type = $url_name_type;
	}

	public function set_custom_url_name($custom_url_name)
	{
		$this->custom_url_name = $custom_url_name;
	}

	public function set_custom_url($custom_url)
	{
		$this->custom_url = $custom_url;
	}

	public function set_can_give_friend($can_give_friend)
	{
		$this->can_give_friend = $can_give_friend;
	}
}

class CardBase
{
	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class GeneralCoupon extends CardBase
{
	public function set_default_detail($default_detail)
	{
		$this->default_detail = $default_detail;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class Groupon extends CardBase
{
	public function set_deal_detail($deal_detail)
	{
		$this->deal_detail = $deal_detail;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class Discount extends CardBase
{
	public function set_discount($discount)
	{
		$this->discount = $discount;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class Gift extends CardBase
{
	public function set_gift($gift)
	{
		$this->gift = $gift;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class Cash extends CardBase
{
	public function set_least_cost($least_cost)
	{
		$this->least_cost = $least_cost;
	}

	public function set_reduce_cost($reduce_cost)
	{
		$this->reduce_cost = $reduce_cost;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class MemberCard extends CardBase
{
	public function set_supply_bonus($supply_bonus)
	{
		$this->supply_bonus = $supply_bonus;
	}

	public function set_supply_balance($supply_balance)
	{
		$this->supply_balance = $supply_balance;
	}

	public function set_bonus_cleared($bonus_cleared)
	{
		$this->bonus_cleared = $bonus_cleared;
	}

	public function set_bonus_rules($bonus_rules)
	{
		$this->bonus_rules = $bonus_rules;
	}

	public function set_balance_rules($balance_rules)
	{
		$this->balance_rules = $balance_rules;
	}

	public function set_prerogative($prerogative)
	{
		$this->prerogative = $prerogative;
	}

	public function set_bind_old_card_url($bind_old_card_url)
	{
		$this->bind_old_card_url = $bind_old_card_url;
	}

	public function set_activate_url($activate_url)
	{
		$this->activate_url = $activate_url;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class ScenicTicket extends CardBase
{
	public function set_ticket_class($ticket_class)
	{
		$this->ticket_class = $ticket_class;
	}

	public function set_guide_url($guide_url)
	{
		$this->guide_url = $guide_url;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class MovieTicket extends CardBase
{
	public function set_detail($detail)
	{
		$this->detail = $detail;
	}

	public function __construct($base_info)
	{
		$this->base_info = $base_info;
	}
}

class Card
{
	private $CARD_TYPE = array('GENERAL_COUPON', 'GROUPON', 'DISCOUNT', 'GIFT', 'CASH', 'MEMBER_CARD', 'SCENIC_TICKET', 'MOVIE_TICKET');

	public function __construct($card_type, $base_info)
	{
		if (!in_array($card_type, $this->CARD_TYPE)) {
			exit('CardType Error');
		}

		if (!$base_info instanceof BaseInfo) {
			exit('base_info Error');
		}

		$this->card_type = $card_type;

		switch ($card_type) {
		case $this->CARD_TYPE[0]:
			$this->general_coupon = new GeneralCoupon($base_info);
			break;

		case $this->CARD_TYPE[1]:
			$this->groupon = new Groupon($base_info);
			break;

		case $this->CARD_TYPE[2]:
			$this->discount = new Discount($base_info);
			break;

		case $this->CARD_TYPE[3]:
			$this->gift = new Gift($base_info);
			break;

		case $this->CARD_TYPE[4]:
			$this->cash = new Cash($base_info);
			break;

		case $this->CARD_TYPE[5]:
			$this->member_card = new MemberCard($base_info);
			break;

		case $this->CARD_TYPE[6]:
			$this->scenic_ticket = new ScenicTicket($base_info);
			break;

		case $this->CARD_TYPE[8]:
			$this->movie_ticket = new MovieTicket($base_info);
			break;

		default:
			exit('CardType Error');
		}

		return true;
	}

	public function get_card()
	{
		switch ($this->card_type) {
		case $this->CARD_TYPE[0]:
			return $this->general_coupon;
		case $this->CARD_TYPE[1]:
			return $this->groupon;
		case $this->CARD_TYPE[2]:
			return $this->discount;
		case $this->CARD_TYPE[3]:
			return $this->gift;
		case $this->CARD_TYPE[4]:
			return $this->cash;
		case $this->CARD_TYPE[5]:
			return $this->member_card;
		case $this->CARD_TYPE[6]:
			return $this->scenic_ticket;
		case $this->CARD_TYPE[8]:
			return $this->movie_ticket;
		default:
			exit('GetCard Error');
		}
	}

	public function toJson()
	{
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
			$str = json_encode($this);
			$str = preg_replace_callback('#\\\\u([0-9a-f]{4})#i', function($matchs) {
				return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
			}, $str);
			$str = $str;
		}
		else {
			$str = json_encode($this, JSON_UNESCAPED_UNICODE);
		}

		return '{ "card":' . $str . '}';
	}
}

class Signature
{
	public function __construct()
	{
		$this->data = array();
	}

	public function add_data($str)
	{
		array_push($this->data, (string) $str);
	}

	public function get_signature()
	{
		sort($this->data, SORT_STRING);
		return sha1(implode($this->data));
	}
}


?>
