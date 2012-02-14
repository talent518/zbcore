<?
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlCategory extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->CtrlCategory();
	}
	function CtrlCategory(){
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('category');
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
			$this->message('提交成功',URL(array('ctrl'=>'category','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('id',$this->id);
			$this->setVar('catpos',$this->mod->catpos($this->id));
			$this->setVar('list',$this->mod->get_list($this->id));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('category/list');
		}
	}
	function onUpdate(){
		$this->mod->updatepids();
		$this->mod->updatecounts();
		$this->message('更新缓存成功',URL(array('ctrl'=>'category','method'=>'list','id'=>$this->id)),true);
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'pid'=>$_POST['pid'],
				'pids'=>iimplode(explode(',',$_POST['pids'])),
				'cname'=>$_POST['cname'],
				'cseo'=>saddslashes(serialize(sstripslashes($_POST['cseo']))),
				'corder'=>intval($_POST['corder']),
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'category','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('pid'=>$this->id,'corder'=>0));
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('category/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'pid'=>intval($_POST['pid']),
				'pids'=>iimplode(explode(',',$_POST['pids'])),
				'cname'=>$_POST['cname'],
				'cseo'=>saddslashes(serialize(sstripslashes($_POST['cseo']))),
				'corder'=>intval($_POST['corder']),
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'category','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的栏目不存在！');
			$edit['cseo']=unserialize($edit['cseo']);
			$this->setVar('edit',$edit);
			$this->setVar('edithash',$this->formhash('edit'));
			$this->display('category/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'category','method'=>'list','id'=>$_POST['pid'])),true);
			else
				$this->message($this->mod->error);
		}elseif($cat=$this->mod->get($this->id)){
			$this->setVar('catpos',$this->mod->catpos($cat['pid']));
			$this->setVar('cat',$cat);
			$this->setVar('drophash',$this->formhash('drop'));
			$this->display('category/drop');
		}else
			$this->message('栏目不存在！');
	}
	function onJson(){
		$this->echoKJson($this->mod->get_tree('name'));
	}
}
