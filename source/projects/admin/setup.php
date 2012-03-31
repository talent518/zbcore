<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlSetup extends CtrlBase{
	function __construct(){
		parent::__construct();
		$this->mod=M('setup');
	}
	function onIndex(){
		$this->onConfig();
	}
	function onConfig(){
		if($this->is_submit('config')){
			$this->mod->set('config',$_POST['config']);
			$this->message('提交成功',URL(array('ctrl'=>'setup','method'=>'config')),true);
		}else{
			$this->setVar('config',M('setup')->get());
			$this->formhash('config');
			$this->display('setup/config');
		}
	}
	function onSiteKey(){
		$config=array();
		$config['sitekey']=md5(MICROTIME.L('string')->rand(12,STRING_RAND_BOTH));
		$this->mod->set('config',$config);
		$this->message('提交成功',URL(array('ctrl'=>'setup','method'=>'config')),true);
	}
	function onEmail(){
		if($this->is_submit('email')){
			$this->mod->set('email',$_POST['email']);
			$this->message('提交成功',URL(array('ctrl'=>'setup','method'=>'email')),true);
		}else{
			$this->setVar('email',$this->mod->get('email'));
			$this->formhash('email');
			$this->formhash('testEmail');
			$this->display('setup/email');
		}
	}
	function onVerify(){
		if($this->is_submit('verify')){
			$this->mod->set('verify',$_POST['verify']);
			$this->message('提交成功',URL(array('ctrl'=>'setup','method'=>'verify')),true);
		}else{
			$this->setVar('verify',$this->mod->get('verify'));
			$this->formhash('verify');
			$this->display('setup/verify');
		}
	}
	function onTestEmail(){
		if($this->is_submit('testEmail')){
			$cfg=$this->mod->get();
			$mail=M('mail');
			if($mail->send($_POST['email'],'测试Email发送功能！',"<h1>{$cfg[sitetitle]}</h1><p>{$cfg[sitedescription]}</p><h5>".sgmdate('Y年m月d日　H时i分s秒')."</h5>"))
				$this->message('发送成功','',true);
			else
				$this->message($mail->error);
		}else
			exit('Access Denied!');
	}
	function onUser(){
		if($this->is_submit('user')){
			$this->mod->set('user',$_POST['user']);
			$this->message('提交成功',URL(array('ctrl'=>'setup','method'=>'user')),true);
		}else{
			$this->setVar('user',$this->mod->get('user'));
			$this->formhash('user');
			$this->display('setup/user');
		}
	}
	function onTemplate(){
		if($this->is_submit('template')){
			$this->mod->set('template',$_POST['template']);
			$this->message('提交成功',URL(array('ctrl'=>'setup','method'=>'template')),true);
		}else{
			$adminskins=$this->_getdir(TPL_DIR.'admin'.DIR_SEP.'skins');
			$fronttpls=$this->_getdir(TPL_DIR.'front');
			$frontskins=array();
			foreach($fronttpls as $tpl){
				$frontskins[$tpl]=$this->_getdir(TPL_DIR.'front'.DIR_SEP.$tpl.DIR_SEP.'skins');
			}
			$this->setVar('adminskins',$adminskins);
			$this->setVar('fronttpls',$fronttpls);
			$this->setVar('frontskins',$frontskins);
			$this->setVar('template',$this->mod->get('template'));
			$this->formhash('template');
			$this->display('setup/template');
		}
	}
	function _getdir($pdir){
		$dirs=array();
		if($dh=@opendir($pdir)){
			while ($dir=readdir($dh)){
				if(!@in_array($dir,array('.','..')) && is_dir($pdir.DIR_SEP.$dir))
					$dirs[]=$dir;
			}
			closedir($dh);
		}
		return $dirs;
	}
}
