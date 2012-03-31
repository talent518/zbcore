<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlBase extends ZBCore{
	var $MEMBER=array(),$LOGINED=0;
	function __construct($login=0){
		parent::__construct();
		$tpl=L('template');
		$tpl->defdir='';
		$tpl->tpldir=TPL_DIR.'admin'.DIR_SEP;
		$tpl->cachedir=TPL_CACHE_DIR.'admin'.DIR_SEP;
		$tpl->datadir='admin'.DIR_SEP;
		define('SKIN_URL',TPL_URL.'admin/skins/'.M('setup')->get('template','adminskin').'/');
		if($this->LOGINED=M('user')->checklogin(1))
			$this->MEMBER=M('user')->MEMBER;
		elseif(!$login && IN_URL_CM!='user/login'){
			$this->message(array('message'=>IN_AJAX?'用户登录':'您还未登录或登录超时！','function'=>'$.window({title:this.message,url:this.backurl,width:604})'),URL(array('ctrl'=>'index','method'=>'login')),false,0);
		}
		$this->setVar('MEMBER',$this->MEMBER);
	}
}
