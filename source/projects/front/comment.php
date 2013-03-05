<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlComment extends CtrlBase{
	var $isCachePage=true;
	function __construct(){
		parent::__construct();
		$this->setVar('head',array('title'=>'用户评论'));
	}
	function onIndex(){
		$this->setVar('catid','comment');
		if($this->MEMBER['ismanage']){
			$this->display('comment/list',null);
		}else{
			$this->formhash('comment');
			$this->display('comment/form',null,true);
		}
	}
	function onSubmit(){
		if($this->is_submit('comment')){
			$data=array(
				'nickname'=>$_POST['nickname'],
				'email'=>$_POST['email'],
				'phone'=>$_POST['phone'],
				'avatar'=>$_POST['avatar'],
				'subject'=>$_POST['subject'],
				'content'=>$_POST['content'],
				'dateline'=>time(),
			);
			if(M('comment')->add($data)){
				$this->message('提交成功！',URL(),true);
			}elseif(M('comment')->error){
				$this->message(M('comment')->error);
			}else{
				$this->message('未知提交错误！');
			}
		}
	}
	function onDrop(){
		$id=GET('id')+0;
		if(M('comment')->drop($id)){
			$this->message('删除成功！',URL(),true);
		}else{
			$this->message('删除失败！');
		}
	}
}
