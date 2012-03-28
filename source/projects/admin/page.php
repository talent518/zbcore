<?
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlPage extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=GET('id')+0;
		$this->setVar('id',$this->id);
		$this->mod=M('page');
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
			$this->message('提交成功',URL(array('ctrl'=>'page','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('catpos',M('category')->catpos($this->id));
			$this->setVar('catlist',M('category')->get_list_by_where('ctype=\'page\' AND pid='.$this->id));
			$this->setVar('list',$this->mod->get_list_by_where($this->id?'cat_id='.$this->id:null,20,true));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('page/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'cat_id'=>$_POST['cat_id'],
				'title'=>$_POST['title'],
				'page_title'=>$_POST['page_title'],
				'page_name'=>$_POST['page_name'],
				'page_content'=>$_POST['page_content'],
				'order'=>$_POST['order'],
			);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'page','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('cat_id'=>$this->id,'order'=>0));
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('page/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'cat_id'=>$_POST['cat_id'],
				'title'=>$_POST['title'],
				'page_title'=>$_POST['page_title'],
				'page_name'=>$_POST['page_name'],
				'page_content'=>$_POST['page_content'],
				'order'=>$_POST['order'],
			);
			if($this->mod->edit($this->id,$data))
				$this->message('提交成功',URL(array('ctrl'=>'page','method'=>'list','id'=>$data['pid'])),true);
			else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的文章不存在！');
			$edit['seo']=unserialize($edit['seo']);
			$this->setVar('edit',$edit);
			$this->setVar('edithash',$this->formhash('edit'));
			$this->display('page/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'page','method'=>'list','id'=>$_POST['pid'])),true);
			else
				$this->message($this->mod->error);
		}elseif($page=$this->mod->get($this->id)){
			$this->setVar('page',$page);
			$this->setVar('drophash',$this->formhash('drop'));
			$this->display('page/drop');
		}else
			$this->message('文章不存在！');
	}
}
