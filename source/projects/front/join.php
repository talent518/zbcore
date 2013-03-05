<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlJoin extends CtrlBase{
	var $isCachePage=true;
	function __construct(){
		parent::__construct();
		$this->setVar('head',array('title'=>'加入我们'));
	}
	function onIndex(){
		$this->setVar('catid','join');
		if($this->MEMBER['ismanage']){
			$this->display('join/list',null);
		}else{
			$this->formhash('join');
			$this->display('join/form',null,true);
		}
	}
	function onSubmit(){
		if($this->is_submit('join')){
			$data=array(
				'realname'=>$_POST['realname'],
				'mobile'=>$_POST['mobile'],
				'qq'=>$_POST['qq'],
				'dateline'=>time(),
			);
			if(M('join')->add($data)){
				$this->message('提交成功！',SITE_URL,true);
			}elseif(M('join')->error){
				$this->message(M('join')->error);
			}else{
				$this->message('未知提交错误！');
			}
		}
	}
	function onDrop(){
		$id=GET('id')+0;
		if(M('join')->drop($id)){
			$this->message('删除成功！',URL(),true);
		}else{
			$this->message('删除失败！');
		}
	}
}
