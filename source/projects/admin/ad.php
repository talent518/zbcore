<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlAd extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->pmod=M('adp');
		$this->mod=M('ad');
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
			$this->message('提交成功',URL(array('ctrl'=>'ad','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('id',$this->id);
			$this->setVar('poslist',$this->pmod->get_list());
			$this->setVar('list',$this->mod->get_list_by_where($this->id>0?'pid='.$this->id:'',20,true));
			$this->formhash('list');
			$this->display('ad/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'pid'=>$_POST['pid'],
				'aid'=>$_POST['aid'],
				'name'=>$_POST['name'],
				'code'=>$_POST['code'][$_POST['type']],
				'enable'=>$_POST['enable'],
				'expired'=>$_POST['expired'],
				'order'=>$_POST['order'],
			);
			$this->mod->setCodeRule($_POST['type']);
			switch($_POST['type']){
				case 'flash':
					if(L('upload')->saveFlash($_FILES['flash'],'ad'))
						$data['code']=L('upload')->url;
					elseif(L('upload')->error)
						$this->message(L('upload')->error);
					break;
				case 'image':
					if(L('upload')->saveImage($_FILES['image'],'ad'))
						$data['code']['src']=L('upload')->url;
					elseif(L('upload')->error)
						$this->message(L('upload')->error);
					break;
			}
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'ad','method'=>'list','id'=>$data['pid'])),true);
			else{
				@unlink(L('upload')->file);
				$this->message($this->mod->error);
			}
		}else{
			$this->setVar('add',array('pid'=>$this->id,'aid'=>0,'enable'=>0,'expired'=>strtotime('+1 month'),'enable'=>1,'order'=>0));
			$this->formhash('add');
			$this->display('ad/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'pid'=>$_POST['pid'],
				'aid'=>$_POST['aid'],
				'name'=>$_POST['name'],
				'code'=>$_POST['code'][$_POST['type']],
				'enable'=>$_POST['enable'],
				'expired'=>$_POST['expired'],
				'order'=>$_POST['order'],
			);
			$this->mod->setCodeRule($_POST['type']);
			switch($_POST['type']){
				case 'flash':
					if(L('upload')->saveFlash($_FILES['flash'],'ad'))
						$data['code']=L('upload')->url;
					elseif(L('upload')->error)
						$this->message(L('upload')->error);
					break;
				case 'image':
					if(L('upload')->saveImage($_FILES['image'],'ad'))
						$data['code']['src']=L('upload')->url;
					elseif(L('upload')->error)
						$this->message(L('upload')->error);
					break;
			}
			if($this->mod->edit($this->id,$data)){
				switch($_POST['type']){
					case 'flash':
						if($_POST['code']['flash'] && L('upload')->file){
							@unlink(RES_UPLOAD_DIR.$_POST['code']['flash']);
						}
						if($_POST['code']['image']['src'])
							@unlink(RES_UPLOAD_DIR.$_POST['code']['image']['src']);
						break;
					case 'image':
						if($_POST['code']['image']['src'] && L('upload')->file){
							@unlink(RES_UPLOAD_DIR.$_POST['code']['image']['src']);
						}
						if($_POST['code']['flash'])
							@unlink(RES_UPLOAD_DIR.$_POST['code']['flash']);
						break;
				}
				$this->message('提交成功',URL(array('ctrl'=>'ad','method'=>'list','id'=>$data['pid'])),true);
			}else{
				@unlink(L('upload')->file);
				$this->message($this->mod->error);
			}
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的广告不存在！');
			$pos=$this->pmod->get($edit['pid']);
			$edit['code']=array($pos['type']=>$edit['code']);
			$this->setVar('edit',$edit);
			$this->formhash('edit');
			$this->display('ad/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			$adp=$this->pmod->get($ad['pid']);
			if($this->mod->drop(intval($_POST['id']),$adp['type'])){
				$this->message('提交成功',URL(array('ctrl'=>'ad','method'=>'list','id'=>$_POST['pid'])),true);
			}else
				$this->message($this->mod->error);
		}elseif($ad=$this->mod->get($this->id)){
			$this->setVar('adp',$this->pmod->get($ad['pid']));
			$this->setVar('ad',$ad);
			$this->formhash('drop');
			$this->display('ad/drop');
		}else
			$this->message('广告不存在！');
	}
	function onShow(){
		$ad=$this->mod->get($this->id);
		$adp=$this->pmod->get($ad['pid']);
		if($ad && $adp)
			$this->echoPage(array('Content-type'=>'text/html; charset='.CFG()->charset),$this->mod->show($adp,$ad));
		else
			$this->message('广告不存在');
	}
	function onJson(){
		$this->echoKJson($this->pmod->get_list(),1);
	}
}
