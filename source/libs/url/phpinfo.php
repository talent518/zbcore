<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class LibUrlPhpinfo extends LibUrlBase{
	function LibUrlPhpinfo(){
		$this->get=&$_GET;
		$this->decode($_SERVER['PATH_INFO']);
	}
	function link($args){
		if(is_array($args)){
			return ROOT_URL.'index.php'.$this->encode($args);
		}else{
			return ROOT_URL.'index.php'.$args;
		}
	}
}
