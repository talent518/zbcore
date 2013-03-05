<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelJoin extends ModelBase{
	protected $table='join';
	protected $priKey='jid';
	protected $order='`jid` desc';
	protected $rules=array(
		'realname'=>array(
			'required'=>true,
			'chinese'=>true,
			'maxlength'=>20,
		),
		'mobile'=>array(
			'required'=>true,
			'mobile'=>true,
		),
		'qq'=>array(
			'required'=>true,
			'uinteger'=>true,
		),
	);
	protected $messages=array(
		'nickname'=>array(
			'required'=>'姓名不能为空！',
			'chinese'=>'姓名只能包括中文和英文、数字和非特殊符号！',
			'maxlength'=>'姓名长度不能超过{0}字！',
		),
		'mobile'=>array(
			'required'=>'手机号不能为空！',
			'mobile'=>'手机号不合法',
		),
		'qq'=>array(
			'required'=>'QQ不能为空！',
			'uinteger'=>'QQ号不合法',
		),
	);
}
