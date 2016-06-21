<?php
class DaofuAction extends BaseAction
{
	public $token;
	public $wecha_id;
	public $payConfig;

	public function __construct()
	{
		$this->token = $this->_get('token');
		$this->wecha_id = $this->wecha_id;

		if (!$this->token) {
		}

		$payConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		$payConfigInfo = unserialize($payConfig['info']);
		$this->payConfig = $payConfigInfo['daofu'];
	}

	public function pay()
	{
		$orderName = $_GET['orderName'];

		if (!$orderName) {
			$orderName = microtime();
		}

		$orderid = $_GET['orderid'];

		if (!$orderid) {
			$orderid = $_GET['single_orderid'];
		}

		$payHandel = new payHandle($this->token, $_GET['from'], 'daofu');
		$orderInfo = $payHandel->beforePay($orderid);

		if (!$orderInfo['price']) {
			exit('必须有价格才能支付');
		}

		$orderInfo = $payHandel->afterPay($orderid, '');
		$from = $payHandel->getFrom();
		$this->redirect('/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&nohandle=1&orderid=' . $orderid);
	}
}

?>
