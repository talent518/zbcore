<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlRouter extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('router');
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
			$this->message('提交成功',URL(array('ctrl'=>'router','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('list',$this->mod->get_list_by_where(null,20,true));
			$this->formhash('list');
			$this->display('router/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'src'=>$_POST['src'],
				'dest'=>$_POST['dest'],
				'order'=>$_POST['order'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'router','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('order'=>0));
			$this->formhash('add');
			$this->display('router/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'src'=>$_POST['src'],
				'dest'=>$_POST['dest'],
				'order'=>$_POST['order'],
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'router','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的URL路由不存在！');
			$this->setVar('edit',$edit);
			$this->formhash('edit');
			$this->display('router/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'router','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}elseif($router=$this->mod->get($this->id)){
			$this->setVar('router',$router);
			$this->formhash('drop');
			$this->display('router/drop');
		}else
			$this->message('URL路由不存在！');
	}
}
