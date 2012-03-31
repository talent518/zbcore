<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelAdp extends ModelBase{
	protected $table='adp';
	protected $priKey='pid';
	protected $order='`porder` DESC';
	protected $rules=array(
		'ptitle'=>array(
			'required'=>true,
			'chinese'=>true,
			'maxlength'=>20,
		),
		'pname'=>array(
			'required'=>true,
			'english'=>true,
			'maxlength'=>20,
			'query'=>'adp',
		),
		'porder'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'ptitle'=>array(
			'required'=>'标题不能为空',
			'maxlength'=>'标题字数不能超过{0}个字',
			'chinese'=>'标题只能包括中文和英文、数字和非特殊符号',
		),
		'pname'=>array(
			'required'=>'名称不能为空',
			'maxlength'=>'名称字数不能超过{0}个字',
			'english'=>'名称只能包括英文字母、数字和非特殊符号',
			'query'=>'广告位“{0}”已存在',
		),
		'porder'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function add(&$data){
		$data['size']=serialize($data['size']);
		return parent::add($data);
	}
	function edit($id,&$data){
		$this->rules['pname']['query']=array('adp','pid<>'.$id.' AND pname=\''.$data['pname'].'\'');
		$data['size']=serialize($data['size']);
		return parent::edit($id,$data);
	}
	function order($ids){
		foreach($ids as $id=>$order)
			DB()->update('adp',array('porder'=>intval($order)),'pid='.intval($id));
	}
	function &get($id=0){
		if($data=parent::get($id))
			$data['size']=unserialize($data['size']);
		return $data;
	}
	function &get_list(){
		$q=DB()->select(array(
			'table'=>'adp p',
			'field'=>'p.*,count(a.id) as `adnum`',
			'join'=>array('ad a'=>'a.pid=p.pid'),
			'group'=>'p.pid',
			'order'=>'porder DESC,p.pid'
		));
		$list=array();
		while($value=DB()->row($q)){
			$value['size']=unserialize($value['size']);
			$list[$value['pid']]=$value;
		}
		return $list;
	}
	function show($id,$delimiter='',$isCode=true){
		$html='';
		$adp=(is_int($id)?$this->get($id):$this->get_by_where('pname=\''.addslashes($id).'\''));

		$ads=M('ad')->get_list_by_where($adp['pid']);
		foreach($ads as $id=>$ad){
			$ad['code']=(unserialize($ad['code'])?unserialize($ad['code']):$ad['code']);
			if($isCode){
				if($html && $delimiter)
					$html.=$delimiter;
				$html.=M('ad')->get_code($adp,$ad);
			}else{
				$ads[$id]=$ad;
			}
		}
		return $isCode?$html:$ads;
	}
}
