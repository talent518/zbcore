<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelPosition extends ModelBase{
	protected $table='position';
	protected $priKey='posid';
	protected $order='`porder` desc,`posid`';
	var $error,$rules=array(
		'pname'=>array(
			'required'=>true,
			'maxlength'=>20,
			'pname'=>'position',
		),
		'porder'=>array(
			'integer'=>true
		),
	),$messages=array(
		'pname'=>array(
			'required'=>'推荐位名称不能为空',
			'maxlength'=>'推荐位名称字数不能超过20个字',
			'query'=>'在同级推荐位“{0}”已存在',
		),
		'porder'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function edit($id,&$data,$isCheck=true,$isString=true){
		$this->rules['pname']['query']=array('position','posid<>'.$id.' AND pname=\''.$data['pname'].'\'');
		return parent::edit($id,$data,$isCheck,$isString);
	}
	function order($ids){
		foreach($ids as $id=>$order){
			DB()->update('position',array('porder'=>intval($order)),'posid='.intval($id));
		}
	}
	function &get($id=0){
		if(!$id)
			return;
		static $ids;
		if(!is_array($ids)){
			$ids=array();
		}
		if(!isset($ids[$id]))
			$ids[$id]=parent::get($id);
		return $ids[$id];
	}
}
