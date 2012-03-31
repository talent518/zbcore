<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class LibCookie{
	function get($key){
		if(empty($key))
			return;
		return decodestr($_COOKIE[md5(CFG()->cookiePre.$key)]);
	}
	function set($key,$value,$life=30){
		if(empty($key))
			return;
		$cfg=CFG();
		setcookie(md5($cfg->cookiePre.$key),
			empty($value)?null:encodestr($value),
			$life?(TIMESTAMP+$life*60):0,
			$cfg->cookiePath,
			$cfg->cookieDomain,
			$_SERVER['SERVER_PORT']==443?1:0
		);
	}
	function drop($key){
		if(empty($key))
			return;
		$this->set($key,'',-60);
	}
}
