<?
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlPosition extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->CtrlPosition();
	}
	function CtrlPosition(){
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('position');
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
			$this->message('提交成功',URL(array('ctrl'=>'position','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('list',$this->mod->get_list());
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('position/list');
		}
	}
	function onUpdate(){
		$this->mod->update_counts();
		$this->message('更新缓存成功',URL(array('ctrl'=>'position','method'=>'list')),true);
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'pname'=>$_POST['pname'],
				'porder'=>$_POST['porder'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'position','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('pid'=>$this->id,'porder'=>0));
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('position/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'pname'=>$_POST['pname'],
				'porder'=>intval($_POST['porder']),
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'position','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的栏目不存在！');
			$this->setVar('edit',$edit);
			$this->setVar('edithash',$this->formhash('edit'));
			$this->display('position/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'position','method'=>'list','id'=>$_POST['pid'])),true);
			else
				$this->message($this->mod->error);
		}elseif($pos=$this->mod->get($this->id)){
			$this->setVar('pos',$pos);
			$this->setVar('drophash',$this->formhash('drop'));
			$this->display('position/drop');
		}else
			$this->message('栏目不存在！');
	}
	function onJson(){
		$this->echoKJson($this->mod->get_tree('name'));
	}
}
