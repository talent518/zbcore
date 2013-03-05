<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlArticle extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->id=GET('id')+0;
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
			$this->setVar('catpos',M('category')->catpos($this->id));
			$this->setVar('catlist',M('category')->get_list_by_where('ctype=\'article\' AND pid='.$this->id));
			$this->setVar('list',$this->mod->get_list_by_where($this->id?$this->id:null,20,true));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('article/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'cat_id'=>$_POST['cat_id'],
				'title'=>$_POST['title'],
				'thumb'=>$_POST['thumb'],
				'seo'=>serialize(sstripslashes($_POST['seo'])),
				'content'=>$_POST['content'],
				'source'=>$_POST['source'],
				'recommended'=>intval($_POST['recommended']),
				'order'=>$_POST['order'],
			);
			if(L('upload')->saveImage($_FILES['thumb'],'partner'))
				$data['thumb']=L('upload')->url;
			elseif(L('upload')->error)
				$this->message(L('upload')->error);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'article','method'=>'list','id'=>$data['pid'])),true);
			else{
				@unlink(L('upload')->file);
				$this->message($this->mod->error);
			}
		}else{
			$this->setVar('add',array('cat_id'=>$this->id,'order'=>0));
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('article/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'cat_id'=>$_POST['cat_id'],
				'title'=>$_POST['title'],
				'thumb'=>$_POST['_thumb'],
				'seo'=>serialize(sstripslashes($_POST['seo'])),
				'content'=>$_POST['content'],
				'source'=>$_POST['source'],
				'recommended'=>intval($_POST['recommended']),
				'order'=>$_POST['order'],
			);
			if(L('upload')->saveImage($_FILES['thumb'],'article'))
				$data['thumb']=L('upload')->url;
			elseif(L('upload')->error)
				$this->message(L('upload')->error);
			if($this->mod->edit($this->id,$data)){
				if($_POST['_thumb'] && $data['thumb']!=$_POST['_thumb']){
					@unlink(RES_UPLOAD_DIR.$_POST['_thumb']);
				}
				$this->message('提交成功',URL(array('ctrl'=>'article','method'=>'list','id'=>$data['pid'])),true);
			}else{
				@unlink(L('upload')->file);
				$this->message($this->mod->error);
			}
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
