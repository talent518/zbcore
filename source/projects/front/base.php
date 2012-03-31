<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlBase extends ZBCore{
	var $MEMBER=array(),$LOGINED=0;
	function __construct(){
		parent::__construct();
		$ftpl=M('setup')->get('template','fronttpl');
		$tpl=L('template');
		$tpl->defdir=TPL_DIR.'front'.DIR_SEP.'default'.DIR_SEP;
		$tpl->tpldir=TPL_DIR.'front'.DIR_SEP.$ftpl.DIR_SEP;
		$tpl->cachedir=TPL_CACHE_DIR.'front'.DIR_SEP.$ftpl.DIR_SEP;
		$tpl->datadir='front'.DIR_SEP.$ftpl.DIR_SEP;
		define('SKIN_URL',TPL_URL.'front/'.$ftpl.'/skins/'.M('setup')->get('template','frontskin').'/');
		if($this->LOGINED=M('user')->checklogin())
			$this->MEMBER=M('user')->MEMBER;
		$this->setVar('MEMBER',$this->MEMBER);
		$this->setVar('LOGINED',$this->LOGINED);
		$this->setVar('config',M('setup')->get());
	}
	function checkLogin(){
		if(!$this->LOGINED)
			$this->message('你还未登录！',URL(array('ctrl'=>'user','method'=>'login')));
	}
}
