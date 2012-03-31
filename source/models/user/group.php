<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUserGroup extends ModelBase{
	protected $table='user_group';
	protected $priKey='gid';
	protected $order='`gid` DESC';
	protected $rules=array(
		'gname'=>array(
			'required'=>true,
			'rangelength'=>'3,25',
			'chinese'=>true,
			'query'=>'user_group'
		),
		'order'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'gname'=>array(
			'required'=>'用户组不能为空',
			'rangelength'=>'用户组的长度只能在{0}和{1}之间',
			'chinese'=>'用户组只能包括中文和英文、数字和非特殊符号',
			'query'=>'用户组“{0}”已经存在'
		),
		'order'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	private $cookie='auth',$timeout=1440;
	var $MEMBER;
	function add(&$data){
		if(parent::add($data)){
			$id=DB()->insert_id();
			$this->drop_cache($id);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if($id==1){
			$this->error='受保护用户组不可删除！';
			return false;
		}elseif(!$group=$this->get($id)){
			$this->error='编辑的用户组不存在！';
			return false;
		}

		$this->rules['gname']['query']=array('user_group','gid<>'.$id.' AND `gname`=\''.$data['gname'].'\'');
		if($id==1){
			$data['protected']=1;
		}
		if(parent::edit($id,$data)){
			$this->drop_cache($id);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($id==1){
			$this->error='受保护用户组不可删除！';
			return false;
		}elseif($group=$this->get($id)){
			if($group['protected']){
				$this->error='受保护用户组不可删除！';
				return false;
			}
			DB()->delete('user_group','gid='.$id);
			$this->drop_cache($id);
			return true;
		}else{
			$this->error='用户组不存在！';
			return false;
		}
	}
	function order($ids){
		foreach($ids as $id=>$order){
			DB()->update('user_group',array('order'=>intval($order)),'gid='.intval($id));
			$this->drop_cache($id);
		}
	}

	function _get($id=0){
		return parent::get($id);
	}

	function &get($id=0){
		if(!$id)
			return;
		$cache=L('cache');
		$cache->dir='user_group';
		$cache->name=$id;
		$cache->callback=array($this,'_get',$id);
		return $cache->get();
	}
	function &get_list(){
		$cache=L('cache');
		$cache->dir='user_group';
		$cache->name='list';
		$cache->callback=array($this,'get_list_by_where',);
		return $cache->get();
	}
	function drop_cache($id=0){
		if($id>0){
			$cache=L('cache');
			$cache->dir='user_group';
			if($id>0){
				$cache->name=$id;
				$cache->drop();
			}
			$cache->name='list';
			$cache->drop();
			return true;
		}
		return L('io.dir')->drop(DATA_DIR.'user_group',true);
	}
}
