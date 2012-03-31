<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelArea extends ModelBase{
	protected $table='area';
	protected $priKey='id';
	protected $order='`order`,`name`';
	protected $rules=array(
		'id'=>array(
			'required'=>true,
			'integer'=>true
		),
		'pid'=>array(
			'integer'=>true
		),
		'name'=>array(
			'required'=>true,
			'maxlength'=>20,
		),
	);
	protected $messages=array(
		'id'=>array(
			'required'=>'地区编号不能为空',
			'integer'=>'地区编号不是一个整数'
		),
		'pid'=>array(
			'integer'=>'上级地区不是一个整数'
		),
		'name'=>array(
			'required'=>'地区名称不能为空',
			'maxlength'=>'地区名称字数不能超过20个字',
			'query'=>'在同级地区“{0}”已存在',
		),
	);
	function add(&$data){
		$this->rules['name']['query']=array('area','pid='.$data['pid'].' AND name=\''.$data['name'].'\'');

		if($this->check($data)){
			DB()->insert('area',$data);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if(!$area=$this->get($id)){
			$this->error='编辑的地区不存在！';
			return false;
		}

		$this->rules['name']['query']=array('area','id<>'.$id.' AND pid='.$data['pid'].' AND name=\''.$data['name'].'\'');

		if($this->check($data)){
			DB()->update('area',$data,'id='.$id);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($area=$this->get($id)){
			$ids=$this->get_child($id);
			DB()->delete('area','id in('.iimplode($ids).')');
			return true;
		}else{
			$this->error='地区不存在！';
			return false;
		}
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order)
			DB()->update('area',array('order'=>intval($order)),'id='.intval($id));
	}
	function areapos($id){
		if(!$area=$this->get($id))
			return false;
		$pos=array();
		foreach(explode(',',$area['pids']) as $id)
			if($_pos=$this->get(intval($id)))
				$pos[]=$_pos;
		$pos[]=$area;
		return $pos;
	}
	function &get_child($id,$list=false){
		$tree=$this->get_tree('id');
		if(!is_array($list))
			$list=array($id);
		if($tree[$id]){
			foreach($tree[$id] as $_id){
				$list[]=$_id;
				$this->get_child($_id,&$list);
			}
		}
		return $list;
	}
	function &get_tree(){
		$mapTree=array();
		$q=DB()->select(array(
			'table'=>'area',
			'field'=>'*',
			'order'=>'`order`,`name`'
		));
		while($value=DB()->row($q)){
			$mapTree[$value['pid']][$value['id']]=$value['name'];
		}
		return $mapTree;
	}
}
