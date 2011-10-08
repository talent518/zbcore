<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlUser extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->gmod=M('user.group');
		$this->mod=M('user');
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
			$this->message('提交成功',URL(array('ctrl'=>'user','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('id',$this->id);
			$this->setVar('list',$this->mod->get_list_by_where($this->id>0?'gid='.$this->id:'',20,true));
			$this->formhash('list');
			$this->display('user/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'gid'=>$_POST['gid'],
				'username'=>$_POST['username'],
				'password'=>$_POST['password'],
				'email'=>$_POST['email'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'user','method'=>'list','id'=>$data['cid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('gid'=>0));
			$this->formhash('add');
			$this->display('user/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'gid'=>$_POST['gid'],
				'username'=>$_POST['username'],
				'password'=>$_POST['password'],
				'email'=>$_POST['email'],
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'user','method'=>'list','id'=>$data['cid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get_by_uid($this->id))
				$this->message('你要编辑的用户不存在！');
			$this->setVar('edit',$edit);
			$this->formhash('edit');
			$this->display('user/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'user','method'=>'list','id'=>$_POST['cid'])),true);
			else
				$this->message($this->mod->error);
		}elseif($user=$this->mod->get_by_uid($this->id)){
			$this->setVar('user',$user);
			$this->formhash('drop');
			$this->display('user/drop');
		}else
			$this->message('用户不存在！');
	}
	function onDatum(){
		$this->setVar('user',M('user')->get_by_uid($this->id));
		$this->setVar('datum',M('user.datum')->get($this->id));
		$this->display('user/datum');
	}
	function onJson(){
		$list=array();
		foreach($this->gmod->get_list() as $group)
			$list[$group['gid']]=$group['gname'];
		$this->echoKJson($list);
	}
}
