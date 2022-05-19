<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUserArticle extends ModelBase{
	protected $table='user_article';
	protected $priKey='art_id';
	protected $forKey='uid';
	protected $order='`order` desc,`art_id` desc';
	protected $rules=array(
		'title'=>array(
			'required'=>true,
			'maxlength'=>20,
			'query'=>'article'
		),
		'content'=>array(
			'required'=>true,
			'minlength'=>20
		),
		'order'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'title'=>array(
			'required'=>'文章名称不能为空',
			'maxlength'=>'文章名称字数不能超过20个字',
			'query'=>'文章“{0}”已存在',
		),
		'content'=>array(
			'required'=>'文章内容至少得写的什么吧',
			'minlength'=>'文章内容至少得写的什么吧'
		),
		'order'=>array(
			'integer'=>'排序必须是整数'
		),
	);
	function add(&$data,$isCheck=true,$isReplace=false){
		$this->rules['title']['query']=array('user_article','uid='.$data['uid'].' AND title=\''.$data['title'].'\'');
		$data['addtime']=TIMESTAMP;
		return parent::add($data,$isCheck,$isReplace);
	}
	function edit($id,$data,$isCheck=true,$isString=true){
		$this->rules['title']['query']=array('user_article','art_id<>'.$id.' AND uid='.$data['uid'].' AND title=\''.$data['title'].'\'');
		$data['edittime']=TIMESTAMP;

		return parent::edit($id,$data,$isCheck,$isString);
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order)
			$this->update(array('order'=>intval($order)),'art_id='.intval($id));
	}
}
