<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelArticle extends ModelBase{
	protected $table='article';
	protected $priKey='art_id';
	protected $forKey='cat_id';
	protected $order='`order` desc,`art_id` desc';
	protected $rules=array(
		'cat_id'=>array(
			'required'=>true,
			'integer'=>true
		),
		'title'=>array(
			'required'=>true,
			'maxlength'=>50,
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
		'cat_id'=>array(
			'required'=>'所属栏目不能为空',
			'integer'=>'所属栏目不是一个整数'
		),
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
		$this->rules['title']['query']=array('article','art_id<>'.$id.' AND title=\''.$data['title'].'\'');
		$data['edittime']=TIMESTAMP;

		if(parent::edit($id,$data,$isCheck,$isString)){
			$article=$this->get($id);
			if($article['cat_id']!=$data['cat_id']){
				$pos=M('category')->get($article['cat_id']);
				$ids=explode(',',$pos['pids']);
				$ids[]=$article['cat_id'];
				M('category')->update(array('counts'=>'counts-1'),'counts>0 AND cat_id in ('.iimplode($ids).')',false);
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
		if(!($article=$this->get($id))){
			$this->error='删除的文章不存在！';
			return false;
		}
		$pos=M('category')->get($article['cat_id']);
		$ids=explode(',',$pos['pids']);
		$ids[]=$article['cat_id'];
		M('category')->update(array('counts'=>'counts-1'),'counts>0 AND cat_id in ('.iimplode($ids).')',false);
		if($article['thumb']){
			@unlink(RES_UPLOAD_DIR.$article['thumb']);
		}
		return parent::drop($id);
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order)
			$this->update(array('order'=>intval($order)),'art_id='.intval($id));
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
			return $this->get_by_where('`order`>='.$get['order'].' AND art_id>'.$id,'`order`,art_id');
		else
			return array();
	}
	function &get_next($id){
		if($get=$this->get($id))
			return $this->get_by_where('`order`<='.$get['order'].' AND art_id<'.$id,'`order` DESC,art_id DESC');
		else
			return array();
	}
}
