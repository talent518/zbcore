<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

class CtrlIndex extends CtrlBase{
	function __construct(){
		parent::__construct(false);
		$this->mod=M('user');
	}
	function onIndex(){
		if($this->LOGINED){
			$this->setVar('head',array(
				'title'=>'用户中心',
				'keywords'=>'用户中心，提交与申请网站信息',
				'description'=>'用户中心是提交与申请网站信息以及其它操作'
			));
			$this->display('index',null,true);
		}else{
			$this->onLogin();
		}
	}
	function onLogin(){
		if($this->LOGINED)
			$this->message(array('message'=>'你已登录！','callback'=>'return;'),URL(array('method'=>'index')));
		if($this->is_submit('login')){
			if(M('setup')->get('verify','frontlogin') && L('cookie')->get('verify')!=$_POST['verify'])
				$this->message(array('message'=>'验证码不正确','callback'=>'return;'),URL(array('method'=>'login')));
			$status=M('user')->login($_POST['username'],$_POST['password'],0);
			switch($status){
				case 1:
					$messageData=array('message'=>'登录成功！');
					if(empty($_POST['isRefer'])){
						$messageData['function']='location.href=this.backurl';
					}else{
						$messageData['callback']='$("#loginForm").getWindow().close();return;';
					}
					$this->message($messageData,URL(array('method'=>'index')),true);
					break;
				default:
					$this->message(array('message'=>'用户或密码不正确！','callback'=>'return;'),URL(array('method'=>'login')));
					break;
			}
		}else{
			$this->setVar('head',array(
				'title'=>'用户登录',
				'keywords'=>'用户登录，用户入口，登录',
				'description'=>'用户登录是用户中心的入口'
			));
			$this->formhash('login');
			$this->display('login');
		}
	}
	function onLogout(){
		$this->mod->logout();
		$this->message('退出成功！',URL(array('method'=>'login')),true);
	}
	function onRegister(){
		if($this->LOGINED)
			$this->message(array('message'=>'你已登录！','callback'=>'return;'),URL(array('method'=>'index')));
		if($this->is_submit('register')){
			if(empty($_POST['agree']))
				$this->message(array('message'=>'您未同意注册协议','callback'=>'return;'));
			if($_POST['passsword']!=$_POST['confirm_passsword'])
				$this->message(array('message'=>'您输入的俩次密码不一样！','callback'=>'return;'));
			if(M('setup')->get('verify','frontregister') && L('cookie')->get('verify')!=$_POST['verify'])
				$this->message(array('message'=>'验证码不正确','callback'=>'return;'),URL(array('method'=>'login')));
			$data=array(
				'username'=>$_POST['username'],
				'password'=>$_POST['password'],
				'email'=>$_POST['email'],
				'iscorp'=>(int)$_POST['iscorp']?1:0,
			);
			if(($status=$this->mod->register($data))!==false)
				$this->message(array('message'=>'注册成功！','function'=>'location.href=this.backurl'),URL(array('method'=>'index')),true);
			else
				$this->message(array('message'=>$this->mod->error,'callback'=>'return;'));
		}else{
			$this->setVar('head',array(
				'title'=>'用户注册',
				'keywords'=>'用户注册，加入我们',
				'description'=>'用户注册是用户加入我们，提交网站信息即申请加入我们！'
			));
			$this->formhash('register');
			$this->display('register');
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
				$this->message('账户修改成功',URL(array('method'=>'welcome')),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->formhash('edit');
			$this->display('edit');
		}
	}
	function onVerify(){
		L('image.verify')->verify();
	}
	function onSvmail(){
		$this->checkLogin();
		if(M('user')->svmail())
			$this->message('已成功发送！',URL(array('method'=>'index')),true);
		else
			$this->message(M('user')->error);
	}
	function onVmail(){
		if(M('user')->vmail(GET('code')))
			$this->message('已通过验证','',true);
		else
			$this->message(M('user')->error);
	}
	function onWelcome(){
		if(!$this->LOGINED)
			return;
		//$this->checkLogin();
		if($this->MEMBER['hasdatum'])
			$datum=M('user.datum')->get($this->MEMBER['uid']);
		else
			$datum=array();
		foreach(array('corpname','linkman','address','qq','msn','mobile','phone','fax') as $key)
			if(!$datum[$key])
				$datum[$key]='-';
		$this->setVar('datum',$datum);
		$this->setVar('count',M('count')->user($this->MEMBER['uid']));
		$this->display('welcome');
	}
	function onDatum(){
		$this->checkLogin();
		if($this->is_submit('datum')){
			$data=array(
				'corpname'=>$_POST['corpname'],
				'introduce'=>$_POST['introduce'],
				'linkman'=>$_POST['linkman'],
				'sex'=>(int)$_POST['sex'],
				'address'=>$_POST['address'],
				'qq'=>$_POST['qq'],
				'msn'=>$_POST['msn'],
				'mobile'=>$_POST['mobile'],
				'phone'=>$_POST['phone'],
				'fax'=>$_POST['fax'],
			);
			if($this->MEMBER['iscorp']){
				unset($data['sex']);
			}else{
				unset($data['corpname'],$data['introduce'],$data['fax']);
			}
			if(M('user.datum')->edit($this->MEMBER['uid'],$data,$this->MEMBER['iscorp']))
				$this->message('编辑资料成功！',URL(array('method'=>'welcome')),true);
			else
				$this->message(M('user.datum')->error);
		}else{
			$this->setVar('datum',M('user.datum')->get($this->MEMBER['uid']));
			$this->formhash('datum');
			$this->display('datum');
		}
	}
}
