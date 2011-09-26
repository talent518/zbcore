<?
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelAdp extends ModelBase{
	protected $table='adp';
	protected $priKey='pid';
	protected $order='`porder` DESC';
	protected $rules=array(
		'pname'=>array(
			'required'=>true,
			'maxlength'=>20,
		),
		'porder'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'pname'=>array(
			'required'=>'广告位名称不能为空',
			'maxlength'=>'广告位名称字数不能超过{0}个字',
			'query'=>'广告位“{0}”已存在',
		),
		'porder'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function __construct(){
		parent::__construct();
	}
	function add(&$data){
		$this->rules['pname']['query']=array('adp','pname=\''.$data['pname'].'\'');
		$data['size']=serialize($data['size']);

		if($this->check($data)){
			DB()->insert('adp',$data);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if(!$pos=$this->get($id)){
			$this->error='编辑的广告位不存在！';
			return false;
		}

		$this->rules['pname']['query']=array('adp','pid<>'.$id.' AND pname=\''.$data['pname'].'\'');
		$data['size']=serialize($data['size']);

		if($this->check($data)){
			DB()->update('adp',$data,'pid='.$id);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($pos=$this->get($id)){
			DB()->delete('adp','pid='.$id);
			return true;
		}else{
			$this->error='广告位不存在！';
			return false;
		}
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
	function show($id){
		$html='';
		$adp=$this->get($id);
		foreach(M('ad')->get_list($id) as $ad){
			$ad['code']=(unserialize($ad['code'])?unserialize($ad['code']):$ad['code']);
			$html.=M('ad')->show($adp,$ad);
		}
		return $html;
	}
}
