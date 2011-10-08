<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlAdp extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('adp');
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
			$this->mod->order($ids);
			$this->message('提交成功',URL(array('ctrl'=>'adp','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('id',$this->id);
			$this->setVar('list',$this->mod->get_list($this->id));
			$this->formhash('list');
			$this->display('adp/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'pname'=>$_POST['pname'],
				'type'=>$_POST['type'],
				'size'=>$_POST['size'],
				'pcount'=>$_POST['pcount'],
				'porder'=>$_POST['porder'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'adp','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('type'=>'image','size'=>array('width'=>0,'height'=>0),'pcount'=>0,'porder'=>0));
			$this->formhash('add');
			$this->display('adp/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'pname'=>$_POST['pname'],
				'type'=>$_POST['type'],
				'size'=>$_POST['size'],
				'pcount'=>$_POST['pcount'],
				'porder'=>$_POST['porder'],
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'adp','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的广告位不存在！');
			$this->setVar('edit',$edit);
			$this->formhash('edit');
			$this->display('adp/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'adp','method'=>'list','id'=>$_POST['pid'])),true);
			else
				$this->message($this->mod->error);
		}elseif($pos=$this->mod->get($this->id)){
			$this->setVar('pos',$pos);
			$this->formhash('drop');
			$this->display('adp/drop');
		}else
			$this->message('广告位不存在！');
	}
	function onShow(){
		$this->echoPage(array('Content-type'=>'text/html; charset='.CFG()->charset),$this->mod->show($this->id));
	}
	function onJson(){
		$this->echoKJson($this->mod->get_tree('name'));
	}
}
