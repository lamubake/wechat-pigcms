<?php
/**
 * ALIPAY API: alipay.app.token.get request
 *
 * @author auto create
 * @since 1.0, 2014-05-15 12:14:54
 */
class AlipayAppTokenGetRequest
{
	/** 
	 * 应用安全码
	 **/
	private $secret;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	
	public function setSecret($secret)
	{
		$this->secret = $secret;
		$this->apiParas["secret"] = $secret;
	}

	public function getSecret()
	{
		return $this->secret;
	}

	public function getApiMethodName()
	{
		return "alipay.app.token.get";
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
