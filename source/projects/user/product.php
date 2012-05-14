<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

class CtrlProduct extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct(IN_METHOD!=='upload');
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('user.product');
		if(IN_METHOD!='upload' && !$this->MEMBER['iscorp']){
			$this->message('无权使用此功能');
		}
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
			$this->message('提交成功',URL(array('ctrl'=>'product','method'=>'list','id'=>$this->id)),true);
		}else{
			$this->setVar('list',$this->mod->get_list_by_where($this->MEMBER['uid'],20));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('product/list');
		}
	}
	function onAdd(){
		if($this->is_submit('add')){
			$data=array(
				'uid'=>$this->MEMBER['uid'],
				'title'=>$_POST['title'],
				'url'=>$_POST['url'],
				'price'=>$_POST['price'],
				'remark'=>$_POST['remark'],
				'order'=>$_POST['order'],
			);
			if($this->mod->add($data)){
				$idata=array();
				$idata['prod_id']=DB()->insert_id();
				foreach($_POST['remarkes'] as $k=>$v){
					$idata['remark']=$v;
					$idata['order']=$_POST['orderes'][$k];
					M('user.product.image')->edit($k,$idata,false);
				}
				$this->message('提交成功',URL(array('ctrl'=>'product','method'=>'list')),true);
			}else
				$this->message($this->mod->error);
		}else{
			$this->setVar('add',array('order'=>0));
			$this->setVar('auth',encodestr($this->MEMBER['uid']."|".$this->MEMBER['password']));
			$this->setVar('addhash',$this->formhash('add'));
			$this->display('product/add');
		}
	}
	function onEdit(){
		if($this->is_submit('edit')){
			$data=array(
				'uid'=>$this->MEMBER['uid'],
				'title'=>$_POST['title'],
				'url'=>$_POST['url'],
				'price'=>$_POST['price'],
				'remark'=>$_POST['remark'],
				'order'=>$_POST['order'],
			);
			if($this->mod->edit($this->id,$data,$this->MEMBER['uid'])){
				$idata=array();
				$idata['prod_id']=$this->id;
				foreach($_POST['remarkes'] as $k=>$v){
					$idata['remark']=$v;
					$idata['order']=$_POST['orderes'][$k];
					M('user.product.image')->edit($k,$idata,false);
				}
				$this->message('提交成功',URL(array('ctrl'=>'product','method'=>'list')),true);
			}else
				$this->message($this->mod->error);
		}else{
			if(!$edit=$this->mod->get($this->id,true))
				$this->message('你要编辑的产品不存在！');
			$this->setVar('edit',$edit);
			$this->setVar('auth',encodestr($this->MEMBER['uid']."|".$this->MEMBER['password']));
			$this->setVar('edithash',$this->formhash('edit'));
			$this->display('product/edit');
		}
	}
	function onDrop(){
		if($this->is_submit('drop')){
			if($this->mod->drop(intval($_POST['id']),$this->MEMBER['uid']))
				$this->message('提交成功',URL(array('ctrl'=>'product','method'=>'list')),true);
			else
				$this->message($this->mod->error);
		}elseif($product=$this->mod->get($this->id)){
			$this->setVar('product',$product);
			$this->setVar('drophash',$this->formhash('drop'));
			$this->display('product/drop');
		}else
			$this->message('产品不存在！');
	}
	function onUpload(){
		@list($uid,$password)=explode("|",decodestr(str_replace(' ','+',GET('auth'))));
		$uid=intval($uid);
		$password=addslashes($password);
		$logined=($uid && $password && DB()->count('user',"`uid`=$uid AND `password`='$password' AND iscorp=1"));
		if(!$logined){
			$this->echoJson('不允许上传！');
		}

		if(L('upload')->saveImage($_FILES['Filedata'],'user_product/'.$uid)){
			$data=array(
				'uid'=>$uid,
				'url'=>L('upload')->url,
				'size'=>$_FILES['Filedata']['size'],
				//'type'=>$_FILES['Filedata']['type'],
				'remark'=>$_FILES['Filedata']['name']
			);
			M('user.product.image')->add($data);
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
		if(($get=M('user.product.image')->get($id)) && $get['uid']==$this->MEMBER['uid']){
			@unlink(RES_UPLOAD_DIR.$get['url']);
			M('user.product.image')->delete($id);
			$return=true;
		}
		$this->echoJson($return);
	}
}
