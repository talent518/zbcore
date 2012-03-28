<?
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlPicture extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		$this->CtrlPicture();
	}
	function CtrlPicture(){
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->cmod=M('category');
		$this->mod=M('picture');
		$this->pmod=M('position');
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
			$this->message('提交成功',URL(array('ctrl'=>'picture','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('id',$this->id);
			$this->setVar('catpos',$this->cmod->catpos($this->id));
			$this->setVar('catlist',$this->cmod->get_list_by_where('ctype=\'picture\' AND pid='.$this->id));
			$this->setVar('list',$this->mod->get_list($this->id?$this->id:null,20));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('picture/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'cat_id'=>$_POST['cat_id'],
				'title'=>$_POST['title'],
				'posids'=>$_POST['posids'],
				'remark'=>$_POST['remark'],
				'order'=>$_POST['order'],
			);
			if(L('upload')->saveImage($_FILES['url'],'picture'))
				$data['url']=L('upload')->url;
			elseif(L('upload')->error)
				$this->message(L('upload')->error);
			if($this->mod->add($data))
				$this->message('提交成功',URL(array('ctrl'=>'picture','method'=>'list','id'=>$data['cat_id'])),true);
			else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('cat_id'=>$this->id,'order'=>0));
			$this->setVar('posList',$this->pmod->get_list());
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('picture/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'cat_id'=>$_POST['cat_id'],
				'title'=>$_POST['title'],
				'url'=>$_POST['_url'],
				'posids'=>$_POST['posids'],
				'remark'=>$_POST['remark'],
				'order'=>$_POST['order'],
			);
			if(L('upload')->saveImage($_FILES['url'],'picture'))
				$data['url']=L('upload')->url;
			elseif(L('upload')->error)
				$this->message(L('upload')->error);
			if($this->mod->edit($this->id,$data)){
				if($_POST['_url'] && L('upload')->file)
					@unlink(RES_UPLOAD_DIR.$_POST['_url']);
				$this->message('提交成功',URL(array('ctrl'=>'picture','method'=>'list','id'=>$data['cat_id'])),true);
			}else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的图片不存在！');
			$this->setVar('edit',$edit);
			$this->setVar('posList',$this->pmod->get_list());
			$this->setVar('edithash',$this->formhash('edit'));
			$this->display('picture/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id'])))
				$this->message('提交成功',URL(array('ctrl'=>'picture','method'=>'list','id'=>$_POST['cat_id'])),true);
			else
				$this->message($this->mod->error);
		}elseif($picture=$this->mod->get($this->id)){
			$this->setVar('catpos',$this->cmod->catpos($cat['cat_id']));
			$this->setVar('picture',$picture);
			$this->setVar('drophash',$this->formhash('drop'));
			$this->display('picture/drop');
		}else
			$this->message('图片不存在！');
	}
}
