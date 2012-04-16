<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUserDatum extends ModelBase{
	protected $table='user_datum';
	protected $priKey='uid';
	protected $rules=array(
		'corpname'=>array(
			'chinese'=>true,
		),
		'linkman'=>array(
			'required'=>true,
			'chinese'=>true,
		),
		'address'=>array(
			'required'=>true,
			'chinese'=>true,
		),
		'qq'=>array(
			'required'=>true,
			'integer'=>true
		),
		'msn'=>array(
			'email'=>true,
		),
		'mobile'=>array(
			'mobile'=>true,
		),
		'phone'=>array(
			'phone'=>true,
		),
		'fax'=>array(
			'phone'=>true,
		),
	),$messages=array(
		'cropname'=>array(
			'chinese'=>'公司名只能包括中文和英文、数字和非特殊符号',
		),
		'linkman'=>array(
			'required'=>'联系人不能为空',
			'chinese'=>'联系人只能包括中文和英文、数字和非特殊符号',
		),
		'address'=>array(
			'required'=>'通信地址不能为空',
			'chinese'=>'只能包括中文和英文、数字和非特殊符号',
		),
		'qq'=>array(
			'required'=>'QQ号不能为空',
			'uinteger'=>'QQ号只能包括数字'
		),
		'fax'=>array(
			'phone'=>'传真格式不正确',
		),
	);
	function edit($id,&$data){
		if($this->check($data)){
			if($this->get($id))
				parent::edit($id,$data);
			else{
				$data['uid']=$id;
				parent::add($data);
			}
			return true;
		}
		return false;
	}
}
