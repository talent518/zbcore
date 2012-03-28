<?
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelPage extends ModelBase{
	protected $table='page';
	protected $priKey='page_id';
	protected $order='`order` desc,`page_id` desc';
	protected $rules=array(
		'cat_id'=>array(
			'required'=>true,
			'integer'=>true
		),
		'title'=>array(
			'required'=>true,
			'maxlength'=>20,
			'query'=>'page',
			'chinese'=>true
		),
		'page_title'=>array(
			'required'=>true,
			'maxlength'=>40,
			'query'=>'page',
			'chinese'=>true
		),
		'page_name'=>array(
			'required'=>true,
			'maxlength'=>40,
			'query'=>'page',
			'english'=>true
		),
		'page_content'=>array(
			'required'=>true,
			'minlength'=>20
		),
		'order'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'cat_id'=>array(
			'required'=>'所属栏目不能为空',
			'integer'=>'所属栏目不是一个整数'
		),
		'title'=>array(
			'required'=>'短标题不能为空',
			'maxlength'=>'短标题字数不能超过20个字',
			'chinese'=>'页短标题只能为汉字',
			'query'=>'页短标题“{0}”已存在',
		),
		'page_title'=>array(
			'required'=>'页标题不能为空',
			'maxlength'=>'页标题字数不能超过20个字',
			'chinese'=>'页标题只能为汉字',
			'query'=>'页标题“{0}”已存在',
		),
		'page_name'=>array(
			'required'=>'页名称不能为空',
			'maxlength'=>'页名称字数不能超过20个字',
			'english'=>'页名称只能为英文',
			'query'=>'页名称“{0}”已存在',
		),
		'page_content'=>array(
			'required'=>'文章内容至少得写的什么吧',
			'minlength'=>'文章内容至少得写的什么吧'
		),
		'order'=>array(
			'integer'=>'排序必须是整数'
		),
	);
	function add(&$data,$isCheck=true,$isReplace=false){
		if(parent::add($data,$isCheck,$isReplace)){
			$pos=M('category')->get($data['cat_id']);
			$ids=explode(',',$pos['pids']);
			$ids[]=$data['cat_id'];
			M('category')->update(array('counts'=>'counts+1'),'cat_id in ('.iimplode($ids).')',false);
			return true;
		}
		return false;
	}
	function edit($id,$data,$isCheck=true,$isString=true){
		$this->rules['title']['query']=array('page','page_id<>'.$id.' AND title=\''.$data['title'].'\'');
		$this->rules['page_title']['query']=array('page','page_id<>'.$id.' AND page_title=\''.$data['page_title'].'\'');
		$this->rules['page_name']['query']=array('page','page_id<>'.$id.' AND page_name=\''.$data['page_name'].'\'');
		if(parent::edit($id,$data,$isCheck,$isString)){
			$page=$this->get($id);
			if($page['cat_id']!=$data['cat_id']){
				$pos=M('category')->get($page['cat_id']);
				$ids=explode(',',$pos['pids']);
				$ids[]=$page['cat_id'];
				M('category')->update(array('counts'=>'counts-1'),'cat_id in ('.iimplode($ids).')',false);
				$pos=M('category')->get($data['cat_id']);
				$ids=explode(',',$pos['pids']);
				$ids[]=$data['cat_id'];
				M('category')->update(array('counts'=>'counts+1'),'cat_id in ('.iimplode($ids).')',false);
			}
			return true;
		}
		return false;
	}
	function drop($id=0){
		$page=$this->get($id);
		$pos=M('category')->get($page['cat_id']);
		$ids=explode(',',$pos['pids']);
		$ids[]=$page['cat_id'];
		M('category')->update(array('counts'=>'counts-1'),'cat_id in ('.iimplode($ids).')',false);
		return parent::drop($id);
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order)
			$this->update(array('order'=>intval($order)),'page_id='.intval($id));
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
			return $this->get_by_where('`order`>='.$get['order'].' AND page_id>'.$id,'`order`,page_id');
		else
			return array();
	}
	function &get_next($id){
		if($get=$this->get($id))
			return $this->get_by_where('`order`<='.$get['order'].' AND page_id<'.$id,'`order` DESC,page_id DESC');
		else
			return array();
	}
}
