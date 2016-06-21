<?php

class checkZend
{
	public function __construct()
	{
		$url = C("site_url") . "/index.php?g=Home&m=Index&a=checkZend";
		$rt = file_get_contents($url);

		if ($rt == "1") {
		}
		else {
			echo "对不起，您的系统环境无法运行此程序";
			exit();
		}
	}
}


?>
