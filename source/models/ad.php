<?
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelAd extends ModelBase{
	protected $table='ad';
	protected $priKey='id';
	protected $order='`order` DESC';
	protected $rules=array(
		'pid'=>array(
			'required'=>true,
			'integer'=>true,
			'min'=>1
		),
		'aid'=>array(
			'required'=>true,
			'integer'=>true
		),
		'name'=>array(
			'required'=>true,
			'maxlength'=>50,
		),
		'code'=>array(
			'check'=>array(
				'html'=>array(
					'required'=>true
				),
				'flash'=>array(
					'required'=>true
				),
				'image'=>array(
					'check'=>array(
						'src'=>array(
							'required'=>true,
						),
						'url'=>array(
							'required'=>true,
						),
						'alt'=>array(
							'maxlength'=>50,
						),
					),
				),
				'text'=>array(
					'check'=>array(
						'text'=>array(
							'required'=>true,
						),
						'url'=>array(
							'required'=>true,
						),
						'size'=>array(
							'custom'=>"^[0-9]+(\.[0-9]+)?(px|pt|em|cm|mm|in|pc|ex)$",
						),
						'color'=>array(
							'custom'=>"^((#[a-fA-F0-9]{3,6})|([a-zA-Z]+))$",
						),
					),
				),
			),
		),
		'enable'=>array(
			'required'=>true,
			'integer'=>true
		),
		'expired'=>array(
			'required'=>true,
			'date'=>true
		),
		'order'=>array(
			'integer'=>true
		),
	);
	protected $messages=array(
		'pid'=>array(
			'required'=>'所属广告位不能为空',
			'integer'=>'所属广告位不是一个整数',
			'min'=>'请选择所属广告位'
		),
		'aid'=>array(
			'required'=>'所属地区不能为空',
			'integer'=>'所属地区不是一个整数'
		),
		'name'=>array(
			'required'=>'广告名称不能为空',
			'maxlength'=>'广告名称字数不能超过{0}个字',
			'query'=>'在同级广告位中广告“{0}”已存在',
		),
		'code'=>array(
			'check'=>array(
				'html'=>array(
					'required'=>'广告代码不能为空'
				),
				'flash'=>array(
					'required'=>'Flash地址不能为空'
				),
				'image'=>array(
					'check'=>array(
						'src'=>array(
							'required'=>'图片地址不能为空',
						),
						'url'=>array(
							'required'=>'图片链接不能为空',
						),
						'alt'=>array(
							'maxlength'=>'图片替换文字不能超过{0}个字',
						),
					),
				),
				'text'=>array(
					'check'=>array(
						'text'=>array(
							'required'=>'文字内容不能为空',
						),
						'url'=>array(
							'required'=>'文字链接不能为空',
						),
						'size'=>array(
							'custom'=>'格式不正确，如：12px,12em,12pt,2em,2cm,10mm,10in,10pc,2ex',
						),
						'color'=>array(
							'custom'=>'文字样式颜色格式不正确，如：red,#0f0,#ff0000',
						),
					),
				),
			),
		),
		'enable'=>array(
			'required'=>'状态不能为空',
			'integer'=>'状态不是一个整数'
		),
		'expired'=>array(
			'required'=>'过期时间不能为空',
			'date'=>'过期时间格式不正确'
		),
		'order'=>array(
			'integer'=>'排序不是一个整数'
		),
	);
	function __construct(){
		parent::__construct();
	}
	function setCodeRule($type){
		if(in_array($type,array('html','flash','image','text'))){
			$this->rules['code']=$this->rules['code']['check'][$type];
			$this->messages['code']=$this->messages['code']['check'][$type];
		}else{
			$this->error='广告位类型错误或没有选择所属广告位';
			return false;
		}
	}
	function add(&$data){
		$this->rules['name']['query']=array('ad','pid='.$data['pid'].' AND `name`=\''.$data['name'].'\'');
		if($this->check($data)){
			$data['expired']=strtotime($data['expired']);
			if(is_array($data['code']))
				$data['code']=serialize($data['code']);
			DB()->insert('ad',$data);
			return true;
		}
		return false;
	}
	function edit($id,&$data){

		$this->rules['name']['query']=array('ad','id<>'.$id.' AND pid='.$data['pid'].' AND `name`=\''.$data['name'].'\'');

		return parent::edit($id,$data);
	}
	function drop($id,$type){
		if($ad=$this->get($id)){
			switch($type){
				case 'flash':
					@unlink(RES_UPLOAD_DIR.$ad['code']);
					break;
				case 'image':
					@unlink(RES_UPLOAD_DIR.$ad['code']['src']);
					break;
			}
			DB()->delete('ad','id='.$id);
			return true;
		}else{
			$this->error='广告不存在！';
			return false;
		}
	}
	function order($pid,$ids){
		foreach($ids as $id=>$order){
			DB()->update('ad',array('order'=>intval($order)),'id='.intval($id));
		}
	}
	function &get($id=0){
		if($data=parent::get($id))
			$data['code']=(unserialize($data['code'])?unserialize($data['code']):$data['code']);
		return $data;
	}
	function show($adp,$ad){
		$html='';
		@extract($adp);
		@extract($ad);
		switch($type){
			case 'flash':
				$html='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$size['width'].'" height="'.$size['height'].'"><param name="movie" value="'.UPLOAD_URL.$code.'" /><param name="quality" value="high" /><embed src="'.UPLOAD_URL.$code.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$size['width'].'" height="'.$size['height'].'"></embed></object>';
				break;
			case 'image':
				$html='<a href="'.$code['url'].'" title="'.$code['alt'].'"><img src="'.UPLOAD_URL.$code['src'].'" alt="'.$code['alt'].'" width="'.$size['width'].'" height="'.$size['height'].'" border="0"/></a>';
				break;
			case 'text':
				$text=$code['text'];
				if($code['color'] || $code['size'])
					$code['text']='<font'.($code['color']?' color="'.$code['color'].'"':'').($code['size']?' style="font-size:'.$code['size'].'"':'').'>'.$code['text'].'</font>';
				if($code['italic'])
					$code['text']='<em>'.$code['text'].'</em>';
				if($code['bold'])
					$code['text']='<b>'.$code['text'].'</b>';
				$html='<p><a href="'.$code['url'].'" title="'.$text.'">'.$code['text'].'</a></p>';
				break;
			case 'html':
				$html=$code;
				break;
		}
		return $html;
	}
}
