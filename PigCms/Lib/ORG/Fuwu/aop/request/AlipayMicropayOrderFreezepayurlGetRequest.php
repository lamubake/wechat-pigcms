<?php
/**
 * ALIPAY API: alipay.micropay.order.freezepayurl.get request
 *
 * @author auto create
 * @since 1.0, 2014-06-12 17:16:25
 */
class AlipayMicropayOrderFreezepayurlGetRequest
{
	/** 
	 * 冻结订单号,创建冻结订单时支付宝返回的
	 **/
	private $alipayOrderNo;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	
	public function setAlipayOrderNo($alipayOrderNo)
	{
		$this->alipayOrderNo = $alipayOrderNo;
		$this->apiParas["alipay_order_no"] = $alipayOrderNo;
	}

	public function getAlipayOrderNo()
	{
		return $this->alipayOrderNo;
	}

	public function getApiMethodName()
	{
		return "alipay.micropay.order.freezepayurl.get";
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
