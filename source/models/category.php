<?
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelCategory extends ModelBase{
	protected $table='category';
	protected $priKey='cat_id';
	protected $order='`corder` desc,`cat_id` desc';
	protected $rules=array(
		'pid'=>array(
			'required'=>true,
			'integer'=>true
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
		'cname'=>array(
			'required'=>'栏目名称不能为空',
			'maxlength'=>'栏目名称字数不能超过20个字',
			'query'=>'在同级栏目“{0}”已存在',
		),
		'corder'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function add(&$data){
		$this->rules['cname']['query']=array('category','pid='.$data['pid'].' AND cat_name=\''.$data['cname'].'\'');

		if(parent::add($data)){
			$this->drop_cache($data['pid']);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if(!$cat=$this->get($id)){
			$this->error='编辑的栏目不存在！';
			return false;
		}

		$this->rules['cname']['query']=array('category','cid<>'.$id.' AND pid='.$data['pid'].' AND cname=\''.$data['cname'].'\'');

		if($this->check($data)){
			DB()->update('category',$data,'cid='.$id);
			$this->drop_cache($data['pid'],$id);
			$this->drop_cache($cat['pid']);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($cat=$this->get($id)){
			$cids=$this->get_child($id);
			DB()->delete('category','cid in('.iimplode($cids).')');
			$count=DB()->count('picture','cid in('.iimplode($cids).')');
			if($cat['pid']>0)
			DB()->update('category',array('counts'=>'counts-'.$count),'cid in ('.$cat['pids'].')',false);

			$sids=DB()->select(array(
				'table'=>'picture_position p',
				'field'=>'p.posid,count(p.id) as `counts`',
				'join'=>array('picture s'=>'s.id=p.id'),
				'where'=>'s.cid in('.iimplode($cids).')',
				'group'=>'p.posid'
			),-1,'posid','counts');

			if($sids){
				foreach($sids as $posid=>$counts)
					DB()->update('position',array('counts'=>'counts-'.$counts),'posid='.$posid,false);
			}

			$ids=DB()->select(array(
				'table'=>'picture',
				'field'=>'id',
				'where'=>'cid in('.iimplode($cids).')'
			),-1,'','id');
			if($ids)
				DB()->delete('picture_position','id in('.iimplode($ids).')');

			DB()->delete('picture','cid in('.iimplode($cids).')');

			$this->drop_cache(intval($cat['pid']));
			foreach($cids as $id)
				$this->drop_cache($id,$id);
			return true;
		}else{
			$this->error='栏目不存在！';
			return false;
		}
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order){
			DB()->update('category',array('corder'=>intval($order)),'cid='.intval($id));
			$this->drop_cache($pid,$id);
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
		$tree=$this->get_tree('id');
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
		$cache=L('cache');
		$cache->dir='category/only';
		$cache->name=$id;
		$cache->callback=array(DB(),'select',array(array(
			'table'=>'category',
			'field'=>'*',
			'where'=>'cid='.$id
		),1));
		return $cache->get();
	}
	function &get_list($pid=0){
		$pid=intval($pid);
		if($pid<0)
			return array();
		$cache=L('cache');
		$cache->dir='category/list';
		$cache->name=$pid;
		$cache->callback=array(&$this,'get_list_by_where',array('pid='.$pid));
		return $cache->get();
	}
	function &get_tree($key=''){
		$cache=L('cache');
		$cache->dir='category';
		$cache->name='tree'.($key?'_'.$key:'');
		$cache->callback=array(&$this,'_get_tree',array($key));
		return $cache->get();
	}
	function &_get_tree($key=''){
		$mapTree=array();
		$q=DB()->select(array(
			'table'=>'category',
			'field'=>'cat_id,pid,cat_name,counts',
			'order'=>'corder DESC'
		));
		while($value=DB()->row($q)){
			if($key=='id')
				$mapTree[$value['pid']][]=$value['cid'];
			elseif($key=='name')
				$mapTree[$value['pid']][$value['cid']]=$value['cname'];
			else
				$mapTree[$value['pid']][$value['cid']]=$value;
		}
		return $mapTree;
	}
	function drop_cache($pid=-1,$id=0){
		if($pid>-1 || $id>0){
			$cache=L('cache');
			if($pid>-1){
				$cache->dir='category/list';
				$cache->name=$pid;
				$cache->drop();
			}
			if($id>0){
				$cache->dir='category/only';
				$cache->name=$id;
				$cache->drop();
			}
			$cache->dir='category';
			foreach(array('','id','name') as $name){
				$cache->name='tree'.($name?'_'.$name:'');
				$cache->drop();
			}
			return true;
		}
		return L('io.dir')->drop(DATA_DIR.'category',true);
	}
	function updatepids(){
		$tree=array();
		$this->_upids(0,&$tree);
		foreach($tree as $id=>$list){
			$data=array('pids'=>implode(',',$list));
			DB()->update('category',$data,'cid='.$id);
		}
		$this->drop_cache();
	}
	function _upids($pid,$tree){
		static $_tree;
		if(!$_tree)
			$_tree=$this->get_tree();
		foreach($_tree[$pid] as $id=>$list){
			if(count($tree[$pid])>0)
				$tree[$id]=$tree[$pid];
			if($pid)
				$tree[$id][]=$pid;
			if(is_array($_tree[$id])){
				$this->_upids($id,&$tree);
			}
		}
	}

	function updatecounts(){
		$list=DB()->select(array(
			'table'=>'picture s',
			'field'=>'s.cid,c.pids,count(s.cid) as counts',
			'join'=>array('category c'=>'c.cid=s.cid'),
			'group'=>'cid'
		),-1);
		$data=array('counts'=>0);
		DB()->update('category',$data);
		foreach($list as $v){
			extract($v);
			$data=array('counts'=>'counts+'.$counts);
			DB()->update('category',$data,'cid in('.($pids?$pids.',':'').$cid.')',false);
		}
		$this->drop_cache();
	}
}
