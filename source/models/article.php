<?
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelArticle extends ModelBase{
	protected $table='article';
	protected $priKey='art_id';
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
		$data['addtime']=TIMESTAMP;
		return parent::add($data,$isCheck,$isReplace);
	}
	function edit($id,$data,$isCheck=true,$isString=true){
		$this->rules['title']['query']=array('article','art_id<>'.$id.' AND title=\''.$data['title'].'\'');
		$data['edittime']=TIMESTAMP;

		return parent::edit($id,$data,$isCheck,$isString);
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order)
			$this->update(array('order'=>intval($order)),'id='.intval($id));
	}
	function &get($id=0){
		static $data;
		if(!$data)
			$data=array();
		if(!$id)
			return array();
		if(!isset($data[$id]))
			$data[$id]=parent::get($id);
		return $data[$id];
	}
	function &get_prev($id){
		if($get=$this->get($id))
			return $this->get_by_where('`order`>='.$get['order'].' AND id>'.$id,'`order`,id');
		else
			return array();
	}
	function &get_next($id){
		if($get=$this->get($id))
			return $this->get_by_where('`order`<='.$get['order'].' AND id<'.$id,'`order` DESC,id DESC');
		else
			return array();
	}
}
