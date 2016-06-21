<?php
class ClearAction extends BackAction{
	public function index(){
    $this->display();
    }
 public function delall(){
		$dir = './conf/logs/Temp';
		$r = $this->deldirs($dir);
		if($r){
			echo "清除临时文件成功...<br>";
		}else{
			$this->error('临时文件清除失败，请检查目录权限',U('index'));
		}	


		$dir = './conf/logs/Cache';
		$r = $this->deldirc($dir);
		if($r){
			echo '清除缓存文件成功...<br>';
		}else{
			$this->error('缓存文件清除失败，请检查目录权限',U('index'));
		}


		$dir = './conf/logs/Data/_fields';
		$r = $this->deldir($dir);
		if($r){
			echo '清除系统缓存成功...<br>';
		}else{
			$this->error('系统缓存清除失败，请检查目录权限',U('index'));
		}
 }

 public function delt(){
		
		$dir = './conf/logs/Temp';
		$r = $this->deldirs($dir);
		if($r){
			$this->success('清除成功',U('index'));
		}else{
			$this->error('清除失败，请检查目录权限',U('index'));
		}
	}
 public function delc(){
		
		$dir = './conf/logs/Cache';
		$r = $this->deldirc($dir);
		if($r){
			$this->success('清除成功',U('index'));
		}else{
			$this->error('清除失败，请检查目录权限',U('index'));
		}
	}
 public function del(){
		
		$dir = './conf/logs/Data';
		$r = $this->deldir($dir);
		if($r){
			$this->success('清除成功',U('index'));
		}else{
			$this->error('清除失败，请检查目录权限',U('index'));
		}
	}

protected function deldirs($dir){
		$result = true;
		$dh = opendir($dir);
		while(false !==($file=readdir($dh))){
			if($file!="." && $file!=".."){
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)){
					$result = unlink($fullpath);					
				}else{
					$this->deldirs($fullpath);
				}
			}
			//rmdir($fullpath);
		}
		closedir($dh);
		return $result;
	}
protected function deldirc($dir){
		$result = true;
		$dh = opendir($dir);
		while($file=readdir($dh)){
			if($file!="." && $file!=".."){
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)){
					$result = unlink($fullpath);					
				}else{
					$this->deldirc($fullpath);
				}
			}
			rmdir($fullpath);
		}
		closedir($dh);
		return $result;
	}
protected function deldir($dir){
		$result = true;
		$dh = opendir($dir);
		while($file=readdir($dh)){
			if($file!="." && $file!=".."){
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)){
					$result = unlink($fullpath);					
				}else{
					$this->deldir($fullpath);
				}
			}
			rmdir($fullpath);
		}
		closedir($dh);
		return $result;
	}
	public function clear(){
		$this->display();
	}

}
?>
