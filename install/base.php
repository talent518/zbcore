<?
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlBase extends ZBCore{
	function __construct(){
		parent::__construct();
		$this->CtrlBase();
	}
	function CtrlBase(){
		$tpl=L('template');
		$tpl->tpldir=CTRL_DIR.'tpls'.DIR_SEP;
		$tpl->cachedir=CTRL_DIR.'cache'.DIR_SEP;
		$tpl->datadir='install'.DIR_SEP;
		define('SKIN_URL',ROOT_URL.'install/tpls/');
	}
}
