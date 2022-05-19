<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUserPicture extends ModelBase{
	protected $table='user_picture';
	protected $priKey='pic_id';
	protected $forKey='uid';
	protected $order='`order` desc,pic_id desc';
	function drop($id=0,$uid=0){
		if(($picture=$this->get($id)) && $picture['uid']==$uid){
			parent::drop($id);
			@unlink(RES_UPLOAD_DIR.$picture['url']);
			return true;
		}else{
			$this->error='图片不存在！';
			return false;
		}
	}
}
