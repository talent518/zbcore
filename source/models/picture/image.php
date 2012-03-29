<?
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelPictureImage extends ModelBase{
	protected $table='picture_image';
	protected $priKey='img_id';
	protected $order='`order` desc,img_id';
}
