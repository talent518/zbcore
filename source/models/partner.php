<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelPartner extends ModelBase{
	protected $table='partner';
	protected $priKey='id';
	protected $order='`order` DESC';
	protected $rules=array(
		'name'=>array(
			'required'=>true,
			'maxlength'=>20,
			'query'=>'parnter'
		),
		'url'=>array(
			'required'=>true,
			'url'=>true
		),
		'order'=>array(
			'integer'=>true
		),
	),$messages=array(
		'name'=>array(
			'required'=>'网站名称不能为空',
			'maxlength'=>'网站名称字数不能超过20个字',
			'query'=>'在同级网站“{0}”已存在',
		),
		'order'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function edit($id,&$data){
		if(!$partner=$this->get($id)){
			$this->error='编辑的栏目不存在！';
			return false;
		}

		$this->rules['name']['query']=array('partner','id<>'.$id.' AND name=\''.$data['name'].'\'');

		if($this->check($data)){
			DB()->update('partner',$data,'id='.$id);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($partner=$this->get($id)){
			DB()->delete('partner','id='.$id);
			return true;
		}else{
			$this->error='栏目不存在！';
			return false;
		}
	}
	function order($ids){
		foreach($ids as $id=>$order)
			DB()->update('partner',array('order'=>intval($order)),'id='.intval($id));
	}
}
