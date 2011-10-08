<?php
if(!defined('IN_SITE'))
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
		if($this->check($data)){
			DB()->insert('user_group',$data);
			$id=DB()->insert_id();
			$this->drop_cache($id);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if(!$group=$this->get($id)){
			$this->error='编辑的用户组不存在！';
			return false;
		}

		$this->rules['gname']['query']=array('user_group','gid<>'.$id.' AND `gname`=\''.$data['gname'].'\'');

		if($this->check($data)){
			DB()->update('user_group',$data,'gid='.$id);
			$this->drop_cache($id);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($group=$this->get($id)){
			DB()->delete('user_group','gid='.$id);
			$this->drop_cache($group['gid'],$id);
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

	function &get($id=0){
		if(!$id)
			return;
		$cache=L('cache');
		$cache->dir='user_group';
		$cache->name=$id;
		$cache->callback=array(parent,'get',array($id));
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
