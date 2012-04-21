<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

abstract class CtrlBase extends ZBCore{
	function __construct(){
		parent::__construct();
		$tpl=L('template');
		$tpl->defdir='';
		$tpl->tpldir=TPL_DIR.'wap'.DIR_SEP;
		$tpl->cachedir=TPL_CACHE_DIR.'wap'.DIR_SEP;
		$tpl->datadir='wap'.DIR_SEP;
		define('SKIN_URL',TPL_URL.'wap/skin/');

		$this->setVar('config',M('setup')->get());
	}
}
