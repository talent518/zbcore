<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class LibUrlRewrite extends LibUrlBase{
	function LibUrlRewrite(){
		$this->get=&$_GET;
		$len=strpos($_SERVER['REQUEST_URI'],'?');
		$this->decode($len===false?$_SERVER['REQUEST_URI']:substr($_SERVER['REQUEST_URI'],0,$len));
	}
	function link($args=array()){
		return substr(ROOT_URL,0,-1).$this->encode($args);
	}
}
