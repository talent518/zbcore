<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelSetup extends ModelBase{
	function &_get($var){
		return DB()->select(array(
			'table'=>'setup',
			'field'=>'`key`,`value`',
			'where'=>'`var`=\''.$var.'\'',
			'order'=>'`order`'
		),SQL_SELECT_LIST,'key','value');
	}

	function &get($var='config',$key=''){
		$cache=L('cache');
		$cache->dir='';
		$cache->name=$var;
		$cache->callback=array(&$this,'_get',array($var));
		if($key){
			$setup=$cache->get();
			return $setup[$key];
		}else
			return $cache->get();
	}
	function set($var,&$_data){
		$datas=array();
		$order=0;
		foreach($_data as $key=>$value)
			$datas[]=array($var,$key,$value,$order++);
		DB()->inserts('setup',array('var','key','value','order'),$datas,1);
		$cache=L('cache');
		$cache->dir='';
		$cache->name=$var;
		$cache->drop();
	}
}
