<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUserProduct extends ModelBase{
	protected $table='user_product';
	protected $priKey='prod_id';
	protected $forKey='uid';
	protected $order='`order` desc,prod_id desc';
	protected $rules=array(
		'title'=>array(
			'required'=>true,
			'maxlength'=>50,
		),
		'url'=>array(
			'required'=>true
		),
		'order'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'title'=>array(
			'required'=>'产品标题不能为空',
			'maxlength'=>'产品标题字数不能超过20个字',
			'query'=>'在同级栏目“{0}”已存在',
		),
		'url'=>array(
			'required'=>'请上传产品'
		),
		'order'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function add(&$data){
		$this->rules['name']['query']=array('user_product','uid='.$data['uid'].' AND `title`=\''.$data['title'].'\'');
		$data['addtime']=TIMESTAMP;
		return parent::add($data);
	}
	function edit($id,&$data,$uid=0){
		$this->rules['name']['query']=array('user_product','prod_id<>'.$id.' AND uid='.$data['uid'].' AND `title`=\''.$data['title'].'\'');
		$data['edittime']=TIMESTAMP;

		return ($product=$this->get($id)) && $product['uid']==$uid && parent::edit($id,$data);
	}
	function drop($id=0,$uid=0){
		if(($product=$this->get($id)) && $product['uid']==$uid){
			$this->delete('prod_id='.$id);
			foreach(M('user.product.image')->get_list_by_where('prod_id='.$id) as $r){
				@unlink(RES_UPLOAD_DIR.$r['url']);
			}
			M('user.product.image')->delete('prod_id='.$id);
			return true;
		}else{
			$this->error='产品不存在！';
			return false;
		}
	}
	function order($ids){
		foreach($ids as $id=>$order){
			$this->update(array('order'=>intval($order)),'prod_id='.intval($id));
		}
	}
}
