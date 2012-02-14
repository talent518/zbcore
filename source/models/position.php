<?
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelPosition extends ModelBase{
	var $error,$rules=array(
		'pname'=>array(
			'required'=>true,
			'maxlength'=>20,
		),
		'porder'=>array(
			'integer'=>true
		),
	),$messages=array(
		'pname'=>array(
			'required'=>'推荐位名称不能为空',
			'maxlength'=>'推荐位名称字数不能超过20个字',
			'query'=>'在同级推荐位“{0}”已存在',
		),
		'porder'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function __construct(){
		parent::__construct();
		$this->ModelPosition();
	}
	function ModelPosition(){
	}
	function add(&$data){
		$this->rules['pname']['query']=array('position','pname=\''.$data['pname'].'\'');

		if($this->check($data)){
			DB()->insert('position',$data);
			$this->drop_cache(-1);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if(!$pos=$this->get($id)){
			$this->error='编辑的推荐位不存在！';
			return false;
		}

		$this->rules['pname']['query']=array('position','posid<>'.$id.' AND pname=\''.$data['pname'].'\'');

		if($this->check($data)){
			DB()->update('position',$data,'posid='.$id);
			$this->drop_cache($id);
			$this->drop_cache(-1);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($pos=$this->get($id)){
			DB()->delete('position','posid='.$id);
			$this->drop_cache(-1);
			$this->drop_cache($id);
			return true;
		}else{
			$this->error='推荐位不存在！';
			return false;
		}
	}
	function order($ids){
		foreach($ids as $id=>$order){
			DB()->update('position',array('porder'=>intval($order)),'posid='.intval($id));
			$this->drop_cache($id);
		}
		$this->drop_cache(-1);
	}
	function update_counts(){
		foreach(DB()->select(array('table'=>'picture_position','field'=>'posid,count(spid) as `count`','group'=>'posid'),-1,'posid','count') as $id=>$count){
			DB()->update('position',array('counts'=>intval($count)),'posid='.intval($id));
			$this->drop_cache($id);
		}
		$this->drop_cache(-1);
	}
	function &get($id=0){
		if(!$id)
			return;
		$cache=L('cache');
		$cache->dir='position';
		$cache->name=$id;
		$cache->callback=array(DB(),'select',array(array(
			'table'=>'position',
			'field'=>'*',
			'where'=>'posid='.$id
		),1));
		return $cache->get();
	}
	function &get_list(){
		$cache=L('cache');
		$cache->dir='position';
		$cache->name='list';
		$cache->callback=array(&$this,'_get_list');
		return $cache->get();
	}
	function &_get_list(){
		return DB()->select(array(
			'table'=>'position',
			'field'=>'*',
			'order'=>'porder DESC'
		),-1,'posid');
	}
	function drop_cache($id=0){
		if($id>0){
			$cache=L('cache');
			$cache->dir='position';
			$cache->name=$id;
			$cache->drop();
		}elseif($id==-1){
			$cache=L('cache');
			$cache->dir='position';
			$cache->name='list';
			$cache->drop();
		}else
			return L('io.dir')->drop(DATA_DIR.'position',true);
	}
}
