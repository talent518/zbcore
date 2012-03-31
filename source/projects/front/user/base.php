<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlUserBase extends CtrlBase{
	function __construct(){
		parent::__construct();
		if(!$this->LOGINED)
			$this->message('你还未登录！',URL(array('ctrl'=>'user','method'=>'login')));
		if(M('setup')->get('user','verifyemail') && !$this->MEMBER['verifyemail'])
			$this->message('您的EMail还未通过验证！请查询我们给您发的验证邮件，你的EMail地址是'.$this->MEMBER['email'].'<br/><a href="'.URL(array('ctrl'=>'user','method'=>'svmail')).'"><font color="red">重新发送</font></a>验证邮件！');
		if(!$this->MEMBER['hasdatum'])
			$this->message('请先把您的个人资料补充完整！');
	}
}
