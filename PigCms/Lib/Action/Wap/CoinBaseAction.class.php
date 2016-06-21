<?php

class CoinBaseAction extends WapAction
{
	public function __construct()
	{
		parent::_initialize();
		
		$this->action_id = $this->_get("id", "intval");
		D("Userinfo")->convertFake(M("cointree_users"), array("token" => $this->token, "wecha_id" => $this->wecha_id, "fakeopenid" => $this->fakeopenid));
	}

	public function shakePrize($shakePrize)
	{
		$prizetype = "";

		if (empty($shakePrize)) {
			return false;
		}

		$actual_join_number = (int) $shakePrize["actual_join_number"];
		$join_number = (int) $shakePrize["join_number"];
		$totaltimes = (int) $shakePrize["totaltimes"];
		$firstNum = intval($shakePrize["first_nums"]) - intval($shakePrize["fistlucknums"]);
		$secondNum = intval($shakePrize["second_nums"]) - intval($shakePrize["secondlucknums"]);
		$thirdNum = intval($shakePrize["third_nums"]) - intval($shakePrize["thirdlucknums"]);
		$fourthNum = intval($shakePrize["fourth_nums"]) - intval($shakePrize["fourlucknums"]);
		$fifthNum = intval($shakePrize["fifth_nums"]) - intval($shakePrize["fivelucknums"]);
		$sixthNum = intval($shakePrize["sixth_nums"]) - intval($shakePrize["sixlucknums"]);
		$prize_arr = array(
			1 => array("id" => 1, "prize" => "一等奖", "v" => $firstNum, "start" => 0, "end" => $firstNum),
			2 => array("id" => 2, "prize" => "二等奖", "v" => $secondNum, "start" => $firstNum, "end" => $firstNum + $secondNum),
			3 => array("id" => 3, "prize" => "三等奖", "v" => $thirdNum, "start" => $firstNum + $secondNum, "end" => $firstNum + $secondNum + $thirdNum),
			4 => array("id" => 4, "prize" => "四等奖", "v" => $fourthNum, "start" => $firstNum + $secondNum + $thirdNum, "end" => $firstNum + $secondNum + $thirdNum + $fourthNum),
			5 => array("id" => 5, "prize" => "五等奖", "v" => $fifthNum, "start" => $firstNum + $secondNum + $thirdNum + $fourthNum, "end" => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum),
			6 => array("id" => 6, "prize" => "六等奖", "v" => $sixthNum, "start" => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum, "end" => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum + $sixthNum),
			7 => array("id" => 7, "prize" => "谢谢参与", "v" => ($join_number * $totaltimes) - ($firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum + $sixthNum), "start" => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum + $sixthNum, "end" => $join_number * $totaltimes)
			);

		if ($join_number == 1) {
			if ($shakePrize["fistlucknums"] <= $shakePrize["first_nums"]) {
				$prizetype = 1;
			}
			else {
				$prizetype = 7;
			}
		}
		else {
			$prizetype = $this->get_rand($prize_arr, ($join_number * $totaltimes) - $actual_join_number);
		}

		switch ($prizetype) {
		case 1:
			if ($shakePrize["first_nums"] <= $shakePrize["fistlucknums"]) {
				$prizetype = 7;
			}

			break;

		case 2:
			if ($shakePrize["second_nums"] <= $shakePrize["secondlucknums"]) {
				$prizetype = 7;
			}

			break;

		case 3:
			if ($shakePrize["third_nums"] <= $shakePrize["thirdlucknums"]) {
				$prizetype = 7;
			}

			break;

		case 4:
			if ($shakePrize["fourth_nums"] <= $shakePrize["fourlucknums"]) {
				$prizetype = 7;
			}

			break;

		case 5:
			if ($shakePrize["fifth_nums"] <= $shakePrize["fivelucknums"]) {
				$prizetype = 7;
			}

			break;

		case 6:
			if ($shakePrize["sixth_nums"] <= $shakePrize["sixlucknums"]) {
				$prizetype = 7;
			}

			break;

		default:
			$prizetype = 7;
			break;
		}

		return $prizetype;
	}

	protected function get_rand($proArr, $total)
	{
		$result = 7;
		$randNum = mt_rand(1, $total);

		foreach ($proArr as $k => $v ) {
			if (0 < $v["v"]) {
				if (($v["start"] < $randNum) && ($randNum <= $v["end"])) {
					$result = $k;
					break;
				}
			}
		}

		return $result;
	}

	protected function getPrizeName($shakePrize, $prizetype)
	{
		$prizeinfo = array();

		switch ($prizetype) {
		case 1:
			$prizeinfo["prizename"] = $shakePrize["first_prize"];
			$prizeinfo["prizepic"] = $shakePrize["first_img"];
			break;

		case 2:
			$prizeinfo["prizename"] = $shakePrize["second_prize"];
			$prizeinfo["prizepic"] = $shakePrize["second_img"];
			break;

		case 3:
			$prizeinfo["prizename"] = $shakePrize["third_prize"];
			$prizeinfo["prizepic"] = $shakePrize["third_img"];
			break;

		case 4:
			$prizeinfo["prizename"] = $shakePrize["fourth_prize"];
			$prizeinfo["prizepic"] = $shakePrize["fourth_img"];
			break;

		case 5:
			$prizeinfo["prizename"] = $shakePrize["fifth_prize"];
			$prizeinfo["prizepic"] = $shakePrize["fifth_img"];
			break;

		case 6:
			$prizeinfo["prizename"] = $shakePrize["sixth_prize"];
			$prizeinfo["prizepic"] = $shakePrize["sixth_img"];
			break;

		default:
			$prizeinfo["prizename"] = "谢谢参与";
			break;
		}

		return $prizeinfo;
	}
}


?>
