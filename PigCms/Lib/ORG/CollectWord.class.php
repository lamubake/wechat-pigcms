<?php

class CollectWord {
	
	private $_word;		// 中奖的词
	private $_length;	// 中奖的词长度
	private $_prize;	// 获取的词
	private $_selfNum;	// 当前的词编号
	private $_wordNum;	// 词编号
	private $_count;	// 奖品数量
	private $_time;		// 时间
	private $_sCount;	// 剩余的奖品数量
	private $_expect;	// 中奖机率
	
	public function __construct($params) {
		$this->_word = $this->stringToArray($params['word']);
		$this->_length = count($this->_word);
		$this->_selfNum = $params['self'];
		$this->_level = $params['level'];
		$this->_wordNum = implode('', range(0, $this->_length-1));
		$this->_count = $params['count'];
		$this->_sCount = $params['sCount'];
		$this->_time = abs($params['time']);
		$this->_expect = abs($params['expect']);
	}
	
	public function setSelfNum($selfNum) {
		$this->_selfNum = $selfNum;
	}
	
	public function getLength() {
		return $this->_length;
	}
	
	public function getWord() {
		return $this->_word;
	}
	
	public function getCount() {
		return $this->_count;
	}
	
	public function stringToArray($string) {
		preg_match_all('/[\x{4e00}-\x{9fa5}]/u', $string, $string);
		return $string[0];
	}
	
	public function prize() {
		$self = $this->prizeRand();
		if ($self === $this->_wordNum) {
			if (0 < $this->_sCount) {		
				if ($this->_chances()) { // 判断是否允许中奖
					$result['status'] = 1;
					$result['message'] = $this->_prize;
				} else {
					return $this->prize();
				}
			} else {
				return $this->prize();
			}
		} else {
			$result['status'] = 0;
			$result['message'] = $this->_prize;
		}		
		return $result;
	}
	
	public function prizeRand() {
		$self = $this->_selfNum;
		$self[] = $this->_prize = array_rand($this->_word);
		sort($self);
		$self = array_unique($self);
		return implode('', $self);
	}
	
	public function _chances() {
		$this->_time = round($this->_time / 84600, 2);
		$min =  ($this->_count - ($this->_count / $this->_time)) / $this->_time;
		$ss = ($this->_sCount / $this->_time);
		$max = $this->_count / $this->_time;
		if ($min < $ss && $ss <= $max) {
			$expect = ($ss / $this->_expect) * 100;
			$rand = mt_rand(1, 1000000) / 10000;
			if ($rand <= $expect) {
				return  true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	

}