<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelBase{
	var $key,$error;

	protected $table;
	protected $priKey;
	protected $order;
	protected $rules;
	protected $messages;

	function check($data){
		$valid=L('validate');
		if($valid->check($data,$this->rules,$this->messages)){
			return true;
		}else{
			$this->key=$valid->key;
			$this->error=$valid->error;
			return false;
		}
	}
	function &exists($id){
		return $id!==0?DB()->select(array(
				'table'=>$this->table,
				'field'=>$this->priKey,
				'where'=>$this->priKey.'='.($id+0)
			),SQL_SELECT_ONLY,$this->priKey):0;
	}
	function &get($id){
		return $id!==0?$this->get_by_where($this->priKey.'='.($id+0)):false;
	}
	function &get_by_where($where='',$order=''){
		return DB()->select(array(
				'table'=>$this->table,
				'field'=>'*',
				'where'=>$where,
				'order'=>$order
			),SQL_SELECT_ONLY);
	}
	function get_list_by_where($where='',$limit=0,$spages=false){
		return DB()->select(array(
				'table'=>$this->table,
				'field'=>'*',
				'where'=>$where,
				'order'=>$this->order,
				'limit'=>$limit,
				'spages'=>$spages,
			),SQL_SELECT_LIST,$this->priKey);
	}
	function add($data,$isCheck=true,$isReplace=false){
		if(!$isCheck || $this->check($data)){
			DB()->insert($this->table,saddslashes($data),$isReplace);
			return true;
		}
		return false;
	}
	function edit($id,$data,$isCheck=true,$isString=true){
		if($id<=0 || $this->exists($id)!=$id){
			$this->error='信息不存在';
			return false;
		}
		if(!$isCheck || $this->check($data)){
			DB()->update($this->table,saddslashes($data),($id+0>0)?'`'.$this->priKey.'`='.($id+0):'1>0',$isString);
			return true;
		}
		return false;
	}
	function drop($id){
		if($id<=0)
			return false;
		if($this->exists($id)==$id){
			$this->delete($id+0);
			return true;
		}else{
			$this->error='信息不存在';
		}
		return false;
	}
	function update($data,$where,$isString=true){
		DB()->update($this->table,$data,$where,$isString);
	}
	function delete($where){
		DB()->delete($this->table,is_int($where)?$this->priKey.'='.$where:$where);
	}
}
