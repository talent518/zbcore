<?
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelPictureImage extends ModelBase{
	protected $table='picture_image';
	protected $priKey='img_id';
	protected $forKey='pic_id';
	protected $order='`order` desc,img_id';
}
