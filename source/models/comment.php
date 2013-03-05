<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelComment extends ModelBase{
	protected $table='comment';
	protected $priKey='cid';
	protected $order='`cid` desc';
	protected $rules=array(
		'nickname'=>array(
			'required'=>true,
			'chinese'=>true,
			'maxlength'=>20,
		),
		'email'=>array(
			'required'=>true,
			'email'=>true,
		),
		'phone'=>array(
			'required'=>true,
		),
		'subject'=>array(
			'required'=>true,
		),
		'avatar'=>array(
			'required'=>true,
		),
		'content'=>array(
			'required'=>true,
		),
	);
	protected $messages=array(
		'nickname'=>array(
			'required'=>'姓名不能为空！',
			'chinese'=>'姓名只能包括中文和英文、数字和非特殊符号！',
			'maxlength'=>'姓名长度不能超过{0}字！',
		),
		'email'=>array(
			'required'=>'E-mail不能为空！',
			'email'=>'E-mail格式不合法！',
		),
		'phone'=>array(
			'required'=>'电话不能空！',
		),
		'subject'=>array(
			'required'=>'留言主题不能空！',
		),
		'avatar'=>array(
			'required'=>'请选择头像！',
		),
		'content'=>array(
			'required'=>'留言内容不能空！',
		),
	);
}
