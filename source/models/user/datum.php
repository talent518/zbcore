<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUserDatum extends ModelBase{
	var $error,$rules=array(
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
	);
	function edit($id,&$data){
		if($this->check($data)){
			if($this->get($id))
				DB()->update('user_datum',$data,'uid='.$id);
			else{
				$data['uid']=$id;
				DB()->insert('user_datum',$data);
				DB()->update('user',array('hasdatum'=>1),'uid='.$id);
			}
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($this->get($id)){
			DB()->delete('user_datum','uid='.$id);
			return true;
		}else{
			$this->error='用户资料不存在！';
			return false;
		}
	}
	function &get($id=0){
		return DB()->select(array(
			'table'=>'user_datum',
			'field'=>'*',
			'where'=>'uid='.$id
		),1);
	}
}
