<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

class CtrlArea extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('area');
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
			$this->message('提交成功',URL(array('ctrl'=>'area','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('id',$this->id);
			$this->setVar('areapos',$this->mod->areapos($this->id));
			$this->setVar('list',$this->mod->get_list_by_where('pid='.$this->id));
			$this->formhash('list');
			$this->display('area/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'id'=>$_POST['id'],
				'pid'=>$_POST['pid'],
				'pids'=>iimplode(explode(',',$_POST['pids'])),
				'name'=>$_POST['name'],
				'order'=>intval($_POST['order']),
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'area','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('pid'=>$this->id,'order'=>0));
			$this->formhash('add');
			$this->display('area/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'id'=>intval($_POST['id']),
				'pid'=>intval($_POST['pid']),
				'pids'=>iimplode(explode(',',$_POST['pids'])),
				'name'=>$_POST['name'],
				'order'=>intval($_POST['order']),
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'area','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的地区不存在！');
			$this->setVar('edit',$edit);
			$this->formhash('edit');
			$this->display('area/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'area','method'=>'list','id'=>$_POST['pid'])),true);
			else
				$this->message($this->mod->error);
		}elseif($area=$this->mod->get($this->id)){
			$this->setVar('areapos',$this->mod->areapos($area['pid']));
			$this->setVar('area',$area);
			$this->formhash('drop');
			$this->display('area/drop');
		}else
			$this->message('地区不存在！');
	}
	function onJson(){
		$this->echoKJson($this->mod->get_tree());
	}
}
