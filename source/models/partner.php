<?php
if(!defined('IN_ZBC'))
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
	);
	protected $messages=array(
		'name'=>array(
			'required'=>'网站名称不能为空',
			'maxlength'=>'网站名称字数不能超过20个字',
			'query'=>'在同级网站“{0}”已存在',
		),
		'order'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function add($data){
		$data['haslogo']=empty($data['logo'])===false;
		return parent::add($data);
	}
	function edit($id,&$data){
		if(!$partner=$this->get($id)){
			$this->error='编辑的栏目不存在！';
			return false;
		}
		$data['haslogo']=empty($data['logo'])===false;
		$this->rules['name']['query']=array('partner','id<>'.$id.' AND name=\''.$data['name'].'\'');

		return parent::edit($id,$data);
	}
	function order($ids){
		foreach($ids as $id=>$order)
			$this->update(array('order'=>intval($order)),'id='.intval($id));
	}
}
