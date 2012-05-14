<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

abstract class CtrlBase extends ZBCore{
	var $MEMBER=array(),$LOGINED=0;
	function __construct($isCheck=true){
		parent::__construct();
		$tpl=L('template');
		$tpl->defdir=$tpl->tpldir=TPL_DIR.'user'.DIR_SEP;
		$tpl->cachedir=TPL_CACHE_DIR.'user'.DIR_SEP;
		$tpl->datadir='user'.DIR_SEP;
		define('SKIN_URL',TPL_URL.'user/skin/');
		if($this->LOGINED=M('user')->checklogin())
			$this->MEMBER=M('user')->MEMBER;
		$this->setVar('MEMBER',$this->MEMBER);
		$this->setVar('LOGINED',$this->LOGINED);
		$this->setVar('config',M('setup')->get());
		if($isCheck){
			$this->checkLogin(true);
		}
	}
	function checkLogin($checkDatum=false){
		if(!$this->LOGINED)
			$this->message('你还未登录！',URL(array('method'=>'login')));
		if(M('setup')->get('user','verifyemail') && !$this->MEMBER['verifyemail'])
			$this->message('您的EMail还未通过验证！请查询我们给您发的验证邮件，你的EMail地址是'.$this->MEMBER['email'].'<br/><a href="'.URL(array('method'=>'svmail')).'"><font color="red">重新发送</font></a>验证邮件！');
		if($checkDatum && !$this->MEMBER['hasdatum'])
			$this->message('请先把您的个人资料补充完整！');
	}
}
