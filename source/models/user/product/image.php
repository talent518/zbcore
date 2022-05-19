<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUserProductImage extends ModelBase{
	protected $table='user_product_image';
	protected $priKey='img_id';
	protected $forKey='prod_id';
	protected $order='`order` desc,img_id';
}
