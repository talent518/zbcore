<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlPartner extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('partner');
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
			$this->message('提交成功',URL(array('ctrl'=>'partner','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('list',$this->mod->get_list_by_where(null,10,true));
			$this->formhash('list');
			$this->display('partner/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'name'=>$_POST['name'],
				'url'=>$_POST['url'],
				'logo'=>$_POST['logo'],
				'description'=>$_POST['description'],
				'order'=>$_POST['order'],
			);
			if(L('upload')->saveImage($_FILES['logo'],'partner'))
				$data['logo']=L('upload')->url;
			elseif(L('upload')->error)
				$this->message(L('upload')->error);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'partner','method'=>'list')),true);
			else{
				@unlink(L('upload')->file);
				$this->message($this->mod->error);
			}
		}else{
			$this->setVar('add',array('order'=>0));
			$this->formhash('add');
			$this->display('partner/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'name'=>$_POST['name'],
				'url'=>$_POST['url'],
				'logo'=>$_POST['_logo'],
				'description'=>$_POST['description'],
				'order'=>$_POST['order'],
			);
			if(L('upload')->saveImage($_FILES['logo'],'partner'))
				$data['logo']=L('upload')->url;
			elseif(L('upload')->error)
				$this->message(L('upload')->error);
			if($this->mod->edit($this->id,$data)){
				if($_POST['_logo'] && $data['logo']!=$_POST['_logo']){
					@unlink(RES_UPLOAD_DIR.$_POST['_logo']);
				}
				$this->message('提交成功',URL(array('ctrl'=>'partner','method'=>'list')),true);
			}else{
				@unlink(L('upload')->file);
				$this->message($this->mod->error);
			}
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的栏目不存在！');
			$this->setVar('edit',$edit);
			$this->formhash('edit');
			$this->display('partner/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'partner','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}elseif($partner=$this->mod->get($this->id)){
			$this->setVar('partner',$partner);
			$this->formhash('drop');
			$this->display('partner/drop');
		}else
			$this->message('栏目不存在！');
	}
	function onJson(){
		$this->echoKJson($this->mod->get_tree('name'));
	}
}
