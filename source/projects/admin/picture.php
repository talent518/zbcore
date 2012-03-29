<?
if(!defined('IN_SITE'))
exit('Access Denied');

class CtrlPicture extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct(IN_METHOD=='onUpload');
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
				'url'=>$_POST['url'],
				'posids'=>$_POST['posids'],
				'remark'=>$_POST['remark'],
				'order'=>$_POST['order'],
			);
			if($this->mod->add($data)){
				$idata=array();
				$idata['pic_id']=$this->mod->id;
				foreach($_POST['remarkes'] as $k=>$v){
					$idata['remark']=$v;
					$idata['order']=$_POST['orderes'][$k];
					M('picture.image')->edit($k,$idata,false);
				}
				$this->message('提交成功',URL(array('ctrl'=>'picture','method'=>'list','id'=>$data['cat_id'])),true);
			}else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('cat_id'=>$this->id,'order'=>0));
			$this->setVar('auth',encodestr($this->MEMBER['uid']."|".$this->MEMBER['password']));
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
				'url'=>$_POST['url'],
				'posids'=>$_POST['posids'],
				'remark'=>$_POST['remark'],
				'order'=>$_POST['order'],
			);
			if($this->mod->edit($this->id,$data)){
				$idata=array();
				$idata['pic_id']=$this->id;
				foreach($_POST['remarkes'] as $k=>$v){
					$idata['remark']=$v;
					$idata['order']=$_POST['orderes'][$k];
					M('picture.image')->edit($k,$idata,false);
				}
				$this->message('提交成功',URL(array('ctrl'=>'picture','method'=>'list','id'=>$data['cat_id'])),true);
			}else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id))
				$this->message('你要编辑的图片不存在！');
			$this->setVar('edit',$edit);
			$this->setVar('auth',encodestr($this->MEMBER['uid']."|".$this->MEMBER['password']));
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
	function onUpload(){
		@list($uid,$password)=explode("|",decodestr(str_replace(' ','+',GET('auth'))));
		$uid=intval($uid);
		$password=addslashes($password);
		$logined=($uid && $password && DB()->count('user',"`uid`=$uid AND `password`='$password'"));
		if(!$logined)
			$this->echoJson($uid.' '.$password.' 管理员没有登录，不允许上传！');

		if(L('upload')->saveImage($_FILES['Filedata'],'picture')){
			$data=array(
				'url'=>L('upload')->url,
				'size'=>$_FILES['Filedata']['size'],
				//'type'=>$_FILES['Filedata']['type'],
				'remark'=>$_FILES['Filedata']['name']
			);
			M('picture.image')->add($data);
			$data['src']=RES_UPLOAD_URL.$data['url'];
			$data['img_id']=DB()->insert_id();
			$this->echoJson($data);
		}else{
			$this->echoJson(L('upload')->error?L('upload')->error:'没有文件被上传');
		}
	}
	function onDropUpload(){
		$return=false;
		$id=GET('id')+0;
		if($get=M('picture.image')->get($id)){
			@unlink(RES_UPLOAD_DIR.$get['url']);
			M('picture.image')->delete($id);
			$return=true;
		}
		$this->echoJson($return);
	}
}
