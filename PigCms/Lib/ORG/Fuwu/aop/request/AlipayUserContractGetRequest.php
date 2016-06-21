<?php
/**
 * ALIPAY API: alipay.user.contract.get request
 *
 * @author auto create
 * @since 1.0, 2014-06-12 17:15:38
 */
class AlipayUserContractGetRequest
{
	/** 
	 * 订购者支付宝ID。session与subscriber_user_id二选一即可。
	 **/
	private $subscriberUserId;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	
	public function setSubscriberUserId($subscriberUserId)
	{
		$this->subscriberUserId = $subscriberUserId;
		$this->apiParas["subscriber_user_id"] = $subscriberUserId;
	}

	public function getSubscriberUserId()
	{
		return $this->subscriberUserId;
	}

	public function getApiMethodName()
	{
		return "alipay.user.contract.get";
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function getTerminalType()
	{
		return $this->terminalType;
	}

	public function setTerminalType($terminalType)
	{
		$this->terminalType = $terminalType;
	}

	public function getTerminalInfo()
	{
		return $this->terminalInfo;
	}

	public function setTerminalInfo($terminalInfo)
	{
		$this->terminalInfo = $terminalInfo;
	}

	public function getProdCode()
	{
		return $this->prodCode;
	}

	public function setProdCode($prodCode)
	{
		$this->prodCode = $prodCode;
	}
}
