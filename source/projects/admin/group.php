<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlGroup extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('user.group');
	}
	function onIndex(){
		$this->onList();
	}
	function onList(){
		if($this->is_submit('list')){
			$ids=array();
			foreach($_POST['ids'] as $id=>$order)
				if($_POST['_ids'][$id]!=$order)
					$ids[$id]=$order;
			$this->mod->order($this->id,$ids);
			$this->message('提交成功',URL(array('ctrl'=>'group','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('id',$this->id);
			$this->setVar('list',$this->mod->get_list($this->id));
			$this->formhash('list');
			$this->display('group/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'gname'=>$_POST['gname'],
				'remark'=>$_POST['remark'],
				'protected'=>$_POST['protected']+0,
				'order'=>$_POST['order'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'group','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('order'=>0));
			$this->formhash('add');
			$this->display('group/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'gname'=>$_POST['gname'],
				'remark'=>$_POST['remark'],
				'protected'=>$_POST['protected']+0,
				'order'=>$_POST['order'],
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'group','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的用户组不存在！');
			$this->setVar('edit',$edit);
			$this->formhash('edit');
			$this->display('group/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'group','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}elseif($group=$this->mod->get($this->id)){
			$this->setVar('group',$group);
			$this->formhash('drop');
			$this->display('group/drop');
		}else
			$this->message('用户组不存在！');
	}
}
