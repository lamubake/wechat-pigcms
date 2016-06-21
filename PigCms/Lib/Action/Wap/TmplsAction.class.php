<?php

class TmplsAction extends WapAction
{
	public $live_info;

	public function _initialize()
	{
		parent::_initialize();
	}

	public function show()
	{
		if ($this->isGet()) {
			$id = $this->_get("id", "intval");
			$content = F("Wap/tmpls_" . $id);

			if (!$content) {
				$meihuaUrl = "http://www.meihua.com";
				$content = file_get_contents($meihuaUrl . "/index.php?m=Wap&c=tmpls&a=index&id=" . $id);
				F("Wap/tmpls_" . $id, $content);
			}

			echo $content . $this->shareScript . "\n</html>";
		}
	}
}


?>
