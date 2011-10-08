<?php
if(!defined('IN_SITE'))
	exit('Access Denied');

class CtrlIndex extends CtrlBase{
	//必须有此空方法
	function __construct(){}
	function onIndex(){
		parent::__construct(1);
		if($this->LOGINED){
			$this->display('index');
		}else{
			$this->onLogin();
		}
	}
	function onLogin(){
		parent::__construct(1);
		if($this->is_submit('login')){
			if(M('setup')->get('verify','adminlogin') && L('cookie')->get('verify')!=$_POST['verify'])
				$this->message('验证码不正确',URL(array('ctrl'=>'user','method'=>'login')));
			$status=M('user')->login($_POST['username'],$_POST['password'],1);
			switch($status){
				case 1:
					$this->message('登录成功！',URL(array('method'=>'index')),true);
					break;
				case 0:
					$this->message('用户不存在！',URL(array('method'=>'login')));
					break;
				case -1:
					$this->message('密码不正确！',URL(array('method'=>'login')));
					break;
			}
		}else{
			if($this->LOGINED=M('user')->checklogin()){
				$this->MEMBER=M('user')->MEMBER;
				$this->setVar('MEMBER',$this->MEMBER);
				if(!$this->MEMBER['ismanage'])
					$this->message('您没有权限登录管理中心',URL(array('method'=>'index')));
			}else{
				$this->message('前台尚未登录！',URL(array('proj'=>'index','ctrl'=>'user')));
			}
			$this->formhash('login');
			$this->display('login');
		}
	}
	function onLogout(){
		parent::__construct(1);
		M('user')->logout(1);
		$this->message('退出成功！',URL(array('method'=>'login')),true);
	}
	function onVerify(){
		L('image.verify')->verify();
	}
	function onWelcome(){
		parent::__construct();
		$this->setVar('runtime',M('count')->runtime());
		$this->setVar('count',M('count')->get());
		$this->setVar('week',M('count')->week());
		$this->display('welcome');
	}
	function onAboutus(){
		parent::__construct();
		$this->display('aboutus');
	}
	function onCache(){
		parent::__construct();
		if($this->is_submit('cache')){
			if($_POST['data'])
				L('io.dir')->drop(DATA_DIR,true);
			if($_POST['tpls'])
				L('io.dir')->drop(TPL_CACHE_DIR,true);
			$this->message('更新成功',URL(array('method'=>'cache')),true);
		}
		$this->formhash('cache');
		$this->display('cache');
	}
}
