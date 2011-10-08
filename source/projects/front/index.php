<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlIndex extends CtrlBase{
	var $isCachePage=true;
	function __construct(){
		parent::__construct();
	}
	function onIndex(){
		$this->display('index',null,true);
	}
}
