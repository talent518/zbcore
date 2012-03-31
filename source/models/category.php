<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelCategory extends ModelBase{
	protected $table='category';
	protected $priKey='cat_id';
	protected $order='`corder` desc,`cat_id`';
	protected $rules=array(
		'pid'=>array(
			'required'=>true,
			'integer'=>true
		),
		'ctype'=>array(
			'required'=>true,
			'custom'=>"page|article|picture"
		),
		'cat_name'=>array(
			'required'=>true,
			'maxlength'=>20,
		),
		'corder'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'pid'=>array(
			'required'=>'上级栏目不能为空',
			'integer'=>'上级栏目不是一个整数'
		),
		'ctype'=>array(
			'required'=>'栏目类型不能为空',
			'custom'=>'栏目类型只能为单页、文章和图片'
		),
		'cat_name'=>array(
			'required'=>'栏目名称不能为空',
			'maxlength'=>'栏目名称字数不能超过20个字',
			'query'=>'在同级栏目“{0}”已存在',
		),
		'corder'=>array(
			'integer'=>'排序不是一个整数'
		),
	);

	var $ctypes=array(
		'page'=>'单页',
		'article'=>'文章',
		'picture'=>'图片',
	);
	var $ctpls=array(
		'page'=>array('list'=>'列表模板','view'=>'单页模板'),
		'article'=>array('cate'=>'栏目模板','list'=>'列表模板','view'=>'单页模板'),
		'picture'=>array('cate'=>'栏目模板','list'=>'列表模板','view'=>'单页模板'),
	);

	function add(&$data){
		$this->rules['cat_name']['query']=array('category','pid='.$data['pid'].' AND cat_name=\''.$data['cat_name'].'\'');
		$cache=L('cache');
		$cache->dir='';
		$cache->name='category_id_tree';
		$cache->drop();
		return parent::add($data);
	}
	function edit($id,&$data){
		if(!$cat=$this->get($id)){
			$this->error='编辑的栏目不存在！';
			return false;
		}

		$this->rules['cat_name']['query']=array('category',$this->priKey.'<>'.$id.' AND pid='.$data['pid'].' AND cat_name=\''.$data['cat_name'].'\'');

		if(parent::edit($id,$data)){
			if($data['ctype'] && $data['ctpl'] && $cat['ctype']!=$data['ctype']){
				$cat_ids=$this->get_child($id);
				$this->update(array('ctype'=>$data['ctype'],'ctpl'=>$data['ctpl']),$this->priKey.' in ('.iimplode($cat_ids).')');
			}
			$cache=L('cache');
			$cache->dir='';
			$cache->name='category_id_tree';
			$cache->drop();
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($cat=$this->get($id)){
			$cat_ids=$this->get_child($id);
			$count=$this->{'drop'.ucfirst($cat['ctype'])}($cat_ids);
			$this->delete($this->priKey.' in('.iimplode($cat_ids).')');
			if($count>0 && $cat['pid']>0)
				$this->update(array('counts'=>'counts-'.$count),$this->priKey.' in ('.$cat['pids'].')',false);
			$cache=L('cache');
			$cache->dir='';
			$cache->name='category_id_tree';
			$cache->drop();
			return true;
		}else{
			$this->error='栏目不存在！';
			return false;
		}
	}
	function dropPage($cat_ids){
		$count=DB()->count('page',$this->priKey.' in('.iimplode($cat_ids).')');
		DB()->delete('page','cat_id in('.iimplode($cat_ids).')');
		return $count;
	}
	function dropArticle($cat_ids){
		$count=DB()->count('aritlce',$this->priKey.' in('.iimplode($cat_ids).')');
		$sids=DB()->select(array(
			'table'=>'aritlce_position p',
			'field'=>'p.posid,count(p.id) as `counts`',
			'join'=>array('aritlce s'=>'s.art_id=p.id'),
			'where'=>'s.cat_id in('.iimplode($cat_ids).')',
			'group'=>'p.posid'
		),-1,'posid','counts');

		if($sids){
			foreach($sids as $posid=>$counts)
				DB()->update('position',array('counts'=>'counts-'.$counts),'posid='.$posid,false);
		}

		$ids=DB()->select(array(
			'table'=>'aritlce',
			'field'=>'id',
			'where'=>'cat_id in('.iimplode($cat_ids).')'
		),-1,'','id');
		if($ids)
			DB()->delete('aritlce_position','id in('.iimplode($ids).')');

		DB()->delete('aritlce','cat_id in('.iimplode($cat_ids).')');
		return $count;
	}
	function dropPicture($cat_ids){
		$count=DB()->count('picture',$this->priKey.' in('.iimplode($cat_ids).')');
		$sids=DB()->select(array(
			'table'=>'picture_position p',
			'field'=>'p.posid,count(p.id) as `counts`',
			'join'=>array('picture s'=>'s.pic_id=p.id'),
			'where'=>'s.cat_id in('.iimplode($cat_ids).')',
			'group'=>'p.posid'
		),-1,'posid','counts');

		if($sids){
			foreach($sids as $posid=>$counts)
				DB()->update('position',array('counts'=>'counts-'.$counts),'posid='.$posid,false);
		}

		$ids=DB()->select(array(
			'table'=>'picture',
			'field'=>'id',
			'where'=>'cat_id in('.iimplode($cat_ids).')'
		),-1,'','id');
		if($ids)
			DB()->delete('picture_position','id in('.iimplode($ids).')');

		DB()->delete('picture','cat_id in('.iimplode($cat_ids).')');
		return $count;
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order){
			DB()->update('category',array('corder'=>intval($order)),$this->priKey.'='.intval($id));
		}
	}
	function catpos($id){
		if(!$cat=$this->get($id))
			return false;
		$pos=array();
		foreach(explode(',',$cat['pids']) as $id)
			if($_cat=$this->get(intval($id)))
				$pos[]=$_cat;
		$pos[]=$cat;
		return $pos;
	}
	function &get_child($id,$list=false){
		$tree=$this->get_id_by_tree();
		if(!is_array($list))
			$list=array($id);
		if($tree[$id]){
			foreach($tree[$id] as $_id){
				$list[]=$_id;
				$this->get_child($_id,&$list);
			}
		}
		return $list;
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
	function get_id_by_tree(){
		$cache=L('cache');
		$cache->dir='';
		$cache->name='category_id_tree';
		$cache->callback=array(&$this,'get_tree',array('id'));
		return $cache->get();
	}
	function &get_tree($key='',$type=''){
		static $trees;
		if(!is_array($trees)){
			$trees=array();
		}
		$k='tree'.($key?'_'.$key:'').($type?'_'.$type:'');
		if(!isset($trees[$k]))
			$trees[$k]=$this->_get_tree($key,$type);
		return $trees[$k];
	}
	private function &_get_tree($key='',$type=''){
		$mapTree=array();
		$q=DB()->select(array(
			'table'=>'category',
			'field'=>'cat_id,pid,cat_name,counts',
			'where'=>$type?'ctype=\''.$type.'\'':'',
			'order'=>'corder DESC'
		));
		while($value=DB()->row($q)){
			if($key=='id')
				$mapTree[$value['pid']][]=$value[$this->priKey];
			elseif($key=='name')
				$mapTree[$value['pid']][$value[$this->priKey]]=$value['cat_name'];
			else
				$mapTree[$value['pid']][$value[$this->priKey]]=$value;
		}
		return $mapTree;
	}
}
