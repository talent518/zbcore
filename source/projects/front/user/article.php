<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlUserArticle extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=GET('id')+0;
		$this->setVar('id',$this->id);
		$this->mod=M('user.article');
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
			$this->message('提交成功',URL(array('ctrl'=>'user.article','method'=>'list')),true);
		}else{
			$this->setVar('list',$this->mod->get_list_by_where($this->MEMBER['uid'],20,true));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('user/article/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'uid'=>$this->MEMBER['uid'],
				'title'=>$_POST['title'],
				'seo'=>serialize(sstripslashes($_POST['seo'])),
				'content'=>$_POST['content'],
				'recommended'=>intval($_POST['recommended']),
				'order'=>$_POST['order'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'user.article','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('cat_id'=>$this->id,'order'=>0));
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('user/article/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'title'=>$_POST['title'],
				'seo'=>serialize(sstripslashes($_POST['seo'])),
				'content'=>$_POST['content'],
				'recommended'=>intval($_POST['recommended']),
				'order'=>$_POST['order'],
			);
			if(($article=$this->mod->get($this->id)) && $article['uid']==$this->MEMBER['uid'] && $this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'user.article','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的文章不存在！');
			$edit['seo']=unserialize($edit['seo']);
			$this->setVar('edit',$edit);
			$this->setVar('edithash',$this->formhash('edit'));
			$this->display('user/article/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if(($article=$this->mod->get(intval($_POST['id']))) && $article['uid']==$this->MEMBER['uid'] && $this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'user.article','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}elseif($article=$this->mod->get($this->id)){
			$edit['seo']=unserialize($article['seo']);
			$this->setVar('article',$article);
			$this->setVar('drophash',$this->formhash('drop'));
			$this->display('user/article/drop');
		}else
			$this->message('文章不存在！');
	}
}
