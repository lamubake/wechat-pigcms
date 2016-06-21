<?php
/**
 * ALIPAY API: alipay.ebpp.bill.batch.payurl.get request
 *
 * @author auto create
 * @since 1.0, 2014-06-12 17:16:57
 */
class AlipayEbppBillBatchPayurlGetRequest
{
	/** 
	 * 回调系统url
	 **/
	private $callbackUrl;
	
	/** 
	 * 订单类型
	 **/
	private $orderType;
	
	/** 
	 * alipayOrderNo-merchantOrderNo即业务流水号和业务订单号
	 **/
	private $payBillList;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	
	public function setCallbackUrl($callbackUrl)
	{
		$this->callbackUrl = $callbackUrl;
		$this->apiParas["callback_url"] = $callbackUrl;
	}

	public function getCallbackUrl()
	{
		return $this->callbackUrl;
	}

	public function setOrderType($orderType)
	{
		$this->orderType = $orderType;
		$this->apiParas["order_type"] = $orderType;
	}

	public function getOrderType()
	{
		return $this->orderType;
	}

	public function setPayBillList($payBillList)
	{
		$this->payBillList = $payBillList;
		$this->apiParas["pay_bill_list"] = $payBillList;
	}

	public function getPayBillList()
	{
		return $this->payBillList;
	}

	public function getApiMethodName()
	{
		return "alipay.ebpp.bill.batch.payurl.get";
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
