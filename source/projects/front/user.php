<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlUser extends CtrlBase{
	function __construct(){
		parent::__construct();
		$this->mod=M('user');
	}
	function onIndex(){
		if($this->LOGINED){
			$this->setVar('head',array(
				'title'=>'用户中心',
				'keywords'=>'用户中心，提交与申请网站信息',
				'description'=>'用户中心是提交与申请网站信息以及其它操作'
			));
			$this->display('user/index',null,true);
		}else{
			$this->onLogin();
		}
	}
	function onLogin(){
		if($this->LOGINED)
			$this->message('你已登录！',URL(array('ctrl'=>'user')));
		if($this->is_submit('login')){
			if(M('setup')->get('verify','frontlogin') && L('cookie')->get('verify')!=$_POST['verify'])
				$this->message('验证码不正确',URL(array('ctrl'=>'user','method'=>'login')));
			$status=$this->mod->login($_POST['username'],$_POST['password']);
			switch($status){
				case 1:
					$this->message('登录成功！',URL(array('ctrl'=>'user','method'=>'index')),true);
				case 0:
					$this->message('用户不存在！',URL(array('ctrl'=>'user','method'=>'login')));
				case -1:
					$this->message('密码不正确！',URL(array('ctrl'=>'user','method'=>'login')));
			}
		}else{
			$this->setVar('head',array(
				'title'=>'用户登录',
				'keywords'=>'用户登录，用户入口，登录',
				'description'=>'用户登录是用户中心的入口'
			));
			$this->formhash('login');
			$this->display('user/login');
		}
	}
	function onLogout(){
		$this->mod->logout();
		$this->message('退出成功！',URL(array('ctrl'=>'user','method'=>'login')),true);
	}
	function onRegister(){
		if($this->LOGINED)
			$this->message('你已登录！',URL(array('ctrl'=>'user')));
		if($this->is_submit('register')){
			if(empty($_POST['agree']))
				$this->message('您未同意注册协议');
			if($_POST['passsword']!=$_POST['confirm_passsword'])
				$this->message('您输入的俩次密码不一样！');
			if(M('setup')->get('verify','frontregister') && L('cookie')->get('verify')!=$_POST['verify'])
				$this->message('验证码不正确',URL(array('ctrl'=>'user','method'=>'login')));
			$data=array(
				'username'=>$_POST['username'],
				'password'=>$_POST['password'],
				'email'=>$_POST['email'],
			);
			if($this->mod->register($data))
				$this->message('注册成功！',URL(array('ctrl'=>'user')),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('head',array(
				'title'=>'用户注册',
				'keywords'=>'用户注册，加入我们',
				'description'=>'用户注册是用户加入我们，提交网站信息即申请加入我们！'
			));
			$this->formhash('register');
			$this->display('user/register');
		}
	}
	function onEdit(){
		$this->checkLogin();
		if($this->is_submit('edit')){
			if($_POST['oldpassword']){
				if(md5(md5($_POST['oldpassword']).$this->MEMBER['salt'])!=$this->MEMBER['password'])
					$this->message('旧密码不正确');
				if(!$_POST['password'])
					$this->message('新密码不能为空');
				if($_POST['password']!=$_POST['confirmpassword'])
					$this->message('俩次密码输入不同，请重新输入');
			}else
				$_POST['password']='';
			$data=array(
				'gid'=>$this->MEMBER['gid'],
				'username'=>$_POST['username'],
				'password'=>$_POST['password'],
				'email'=>$_POST['email'],
			);
			if($this->mod->edit($this->MEMBER['uid'],$data))
				$this->message('账户修改成功',URL(array('ctrl'=>'user','method'=>'welcome')),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->formhash('edit');
			$this->display('user/edit');
		}
	}
	function onVerify(){
		L('image.verify')->verify();
	}
	function onVmail(){
		if(M('user')->vmail(GET('code')))
			$this->message('已通过验证','',true);
		else
			$this->message(M('user')->error);
	}
	function onWelcome(){
		$this->checkLogin();
		if($this->MEMBER['hasdatum'])
			$datum=M('user.datum')->get($this->MEMBER['uid']);
		else
			$datum=array();
		foreach(array('corpname','linkman','address','qq','msn','mobile','phone') as $key)
			if(!$datum[$key])
				$datum[$key]='-';
		$this->setVar('datum',$datum);
		$this->setVar('count',M('count')->user($this->MEMBER['uid']));
		$this->display('user/welcome');
	}
	function onDatum(){
		$this->checkLogin();
		if($this->is_submit('datum')){
			$data=array(
				'corpname'=>$_POST['corpname'],
				'linkman'=>$_POST['linkman'],
				'address'=>$_POST['address'],
				'qq'=>$_POST['qq'],
				'msn'=>$_POST['msn'],
				'mobile'=>$_POST['mobile'],
				'phone'=>$_POST['phone'],
			);
			if(M('user.datum')->edit($this->MEMBER['uid'],$data))
				$this->message('编辑资料成功！',URL(array('ctrl'=>'user','method'=>'welcome')),true);
			else
				$this->message(M('user.datum')->error);
		}else{
			$this->setVar('datum',M('user.datum')->get($this->MEMBER['uid']));
			$this->formhash('datum');
			$this->display('user/datum');
		}
	}
}
