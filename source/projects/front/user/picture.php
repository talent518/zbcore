<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlUserPicture extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct(IN_METHOD=='upload');
		$this->id=intval('0'.GET('id'));
		$this->setVar('id',$this->id);
		$this->mod=M('user.picture');
	}
	function onIndex(){
		if($this->is_submit('list')){
			$idata=array();
			foreach($_POST['remarkes'] as $k=>$v){
				$idata['remark']=$v;
				$idata['order']=$_POST['orderes'][$k];
				M('picture.image')->edit($k,$idata,false);
			}
			$this->message('提交成功',URL(array('ctrl'=>'user.picture','method'=>'index')),true);
		}else{
			$this->setVar('auth',encodestr($this->MEMBER['uid']."|".$this->MEMBER['password']));
			$this->setVar('list',$this->mod->get_list_by_where($this->MEMBER['uid'],20));
			$this->setVar('listhash',$this->formhash('list'));
			$this->display('user/picture');
		}
	}
	function onUpload(){
		@list($uid,$password)=explode("|",decodestr(str_replace(' ','+',GET('auth'))));
		$uid=intval($uid);
		$password=addslashes($password);
		$logined=($uid && $password && DB()->count('user',"`uid`=$uid AND `password`='$password'"));
		if(!$logined)
			$this->echoJson($uid.' '.$password.' 管理员没有登录，不允许上传！');

		if(L('upload')->saveImage($_FILES['Filedata'],'user_picture/'.$uid)){
			$data=array(
				'uid'=>$uid,
				'url'=>L('upload')->url,
				'size'=>$_FILES['Filedata']['size'],
				//'type'=>$_FILES['Filedata']['type'],
				'remark'=>$_FILES['Filedata']['name']
			);
			M('user.picture')->add($data);
			$data['src']=RES_UPLOAD_URL.$data['url'];
			$data['img_id']=DB()->insert_id();
			$this->echoJson($data);
		}else{
			$this->echoJson(L('upload')->error?L('upload')->error:'没有文件被上传');
		}
	}
	function onDrop(){
		$id=GET('id')+0;
		$this->echoJson(M('user.picture')->drop($id,$this->MEMBER['uid']));
	}
}
