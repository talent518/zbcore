<?
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlArticle extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('article');
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
			$this->message('提交成功',URL(array('ctrl'=>'article','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('list',$this->mod->get_list(20));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('article/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'name'=>$_POST['name'],
				'seo'=>saddslashes(serialize(sstripslashes($_POST['seo']))),
				'content'=>$_POST['content'],
				'recommended'=>intval($_POST['recommended']),
				'order'=>$_POST['order'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'article','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('pid'=>$this->id,'order'=>0));
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('article/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'name'=>$_POST['name'],
				'seo'=>saddslashes(serialize(sstripslashes($_POST['seo']))),
				'content'=>$_POST['content'],
				'recommended'=>intval($_POST['recommended']),
				'order'=>$_POST['order'],
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'article','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的文章不存在！');
			$edit['seo']=unserialize($edit['seo']);
			$this->setVar('edit',$edit);
			$this->setVar('edithash',$this->formhash('edit'));
			$this->display('article/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'article','method'=>'list','id'=>$_POST['pid'])),true);
			else
				$this->message($this->mod->error);
		}elseif($article=$this->mod->get($this->id)){
			$edit['seo']=unserialize($article['seo']);
			$this->setVar('article',$article);
			$this->setVar('drophash',$this->formhash('drop'));
			$this->display('article/drop');
		}else
			$this->message('文章不存在！');
	}
}
