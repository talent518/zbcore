<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelPicture extends ModelBase{
	protected $table='picture';
	protected $priKey='pic_id';
	protected $forKey='cat_id';
	protected $order='`order` desc,pic_id desc';
	protected $rules=array(
		'cat_id'=>array(
			'required'=>true,
			'integer'=>true
		),
		'title'=>array(
			'required'=>true,
			'maxlength'=>50,
		),
		'url'=>array(
			'required'=>true
		),
		'order'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'cat_id'=>array(
			'required'=>'所属栏目不能为空',
			'integer'=>'所属栏目不是一个整数'
		),
		'title'=>array(
			'required'=>'图片标题不能为空',
			'maxlength'=>'图片标题字数不能超过20个字',
			'query'=>'在同级栏目“{0}”已存在',
		),
		'url'=>array(
			'required'=>'请上传图片'
		),
		'order'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function ModelPicture(){
		$this->mod=M('category');
	}
	function add(&$data){
		$this->rules['name']['query']=array('picture','cat_id='.$data['cat_id'].' AND `title`=\''.$data['title'].'\'');
		$data['addtime']=TIMESTAMP;
		if($this->check($data)){
			$posids=$data['posids'];
			unset($data['posids']);
			DB()->insert('picture',$data);
			$this->id=DB()->insert_id();
			if($posids){
				DB()->update('position',array('counts'=>'counts+1'),'posid IN ('.iimplode($posids).')',false);
				foreach($posids as $key=>$posid)
					$posids[$key]=array($posid,$this->id);
				DB()->inserts('picture_position',array('posid','id'),$posids,true);
			}
			$pos=$this->mod->get($data['cat_id']);
			$ids=explode(',',$pos['pids']);
			$ids[]=$data['cat_id'];
			M('category')->update(array('counts'=>'counts+1'),'cat_id in ('.iimplode($ids).')',false);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if(!$picture=$this->get($id)){
			$this->error='编辑的图片不存在！';
			return false;
		}

		$this->rules['name']['query']=array('picture','pic_id<>'.$id.' AND cat_id='.$data['cat_id'].' AND `title`=\''.$data['title'].'\'');
		$data['edittime']=TIMESTAMP;

		if($this->check($data)){
			$olds=explode(',',$picture['posids']);
			$news=$data['posids'];
			unset($data['posids']);
			$this->update($data,'pic_id='.$id);
			if($picture['cat_id']!=$data['cat_id']){
				$pos=$this->mod->get($picture['cat_id']);
				$ids=explode(',',$pos['pids']);
				$ids[]=$data['cat_id'];
				M('category')->update(array('counts'=>'counts-1'),'counts>0 AND cat_id in ('.iimplode($ids).')',false);
				$pos=$this->mod->get($data['cat_id']);
				$ids=explode(',',$pos['pids']);
				$ids[]=$data['cat_id'];
				M('category')->update(array('counts'=>'counts+1'),'cat_id in ('.iimplode($ids).')',false);
			}
			if($news){
				if($outs=array_diff($olds,$news)){//删除
					DB()->update('position',array('counts'=>'counts-1'),'counts>0 AND posid IN ('.iimplode($outs).')',false);
					DB()->delete('picture_position','id='.$id.' AND posid IN ('.iimplode($outs).')');
				}
				if($ins=array_diff($news,$olds)){//不存在，插入
					DB()->update('position',array('counts'=>'counts+1'),'posid IN ('.iimplode($ins).')',false);
					$posids=array();
					foreach($ins as $posid)
						$posids[]=array($posid,$id);
					DB()->inserts('picture_position',array('posid','id'),$posids);
				}
			}elseif($olds){
				DB()->delete('picture_position','id='.$id);
				DB()->update('position',array('counts'=>'counts-1'),'counts>0 AND posid in ('.iimplode($olds).')',false);
			}
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($picture=$this->get($id)){
			$pos=$this->mod->get($picture['cat_id']);
			DB()->update('category',array('counts'=>'counts-1'),'counts>0 AND cat_id in ('.($pos['pids']?$pos['pids'].',':'').$picture['cat_id'].')',false);
			$this->delete('pic_id='.$id);
			DB()->delete('picture_position','id='.$id);
			foreach(M('picture.image')->get_list_by_where('pic_id='.$id) as $r){
				@unlink(RES_UPLOAD_DIR.$r['url']);
			}
			M('picture.image')->delete('pic_id='.$id);
			if($picture['posids'])
				DB()->update('position',array('counts'=>'counts-1'),'counts>0 AND posid in ('.$picture['posids'].')',false);
			return true;
		}else{
			$this->error='图片不存在！';
			return false;
		}
	}
	function order($cat_id,$ids){
		foreach($ids as $id=>$order){
			$this->update(array('order'=>intval($order)),'pic_id='.intval($id));
		}
	}

	function &get($id=0,$isPos=false){
		return ($isPos?DB()->select(array(
			'table'=>'picture s',
			'field'=>'s.*,group_concat(p.posid) as `posids`',
			'join'=>array('picture_position p'=>'s.pic_id=p.id'),
			'group'=>'s.pic_id',
			'where'=>'s.pic_id='.$id
		),1):parent::get($id));
	}
	function &get_prev($id,$len=1){
		$get=$this->get($id);
		$list=DB()->select(array(
			'table'=>'picture',
			'field'=>'pic_id,title,url',
			'where'=>'`order`>='.$get['order'].' AND pic_id>'.$id.' AND cat_id='.$get['cat_id'],
			'order'=>'`order`,pic_id'
		),$len>1?SQL_SELECT_LIST:SQL_SELECT_ONLY);
		return $len>1?array_reverse($list):$list;
	}
	function &get_next($id,$len=1){
		$get=$this->get($id);
		$list=DB()->select(array(
			'table'=>'picture',
			'field'=>'pic_id,title,url',
			'where'=>'`order`<='.$get['order'].' AND pic_id<'.$id.' AND cat_id='.$get['cat_id'],
			'order'=>'`order` DESC,pic_id DESC'
		),$len>1?SQL_SELECT_LIST:SQL_SELECT_ONLY);
		return $len>1?$list:$list;
	}
	function &get_list($cat_id,$limit=0){
		return DB()->select(array(
			'table'=>'picture',
			'field'=>'*',
			'where'=>(is_numeric($cat_id)?'cat_id='.$cat_id:$cat_id),
			'order'=>'`order` DESC,pic_id DESC',
			'limit'=>$limit,
			'spages'=>true
		),SQL_SELECT_LIST,'pic_id');
	}
	function &get_list_by_position($posid=0,$limit=0){
		return DB()->select(array(
			'table'=>'picture_position p',
			'field'=>'s.*',
			'join'=>array('picture s'=>'s.pic_id=p.id'),
			'where'=>(is_string($posid)?$posid:'posid='.$posid),
			'order'=>'`order` DESC,pic_id DESC',
			'limit'=>$limit
		),SQL_SELECT_LIST,'pic_id');
	}
}
