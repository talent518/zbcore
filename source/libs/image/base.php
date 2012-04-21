<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

/**
 * Enter description here ...
 * @author Administrator
 *
 */
abstract class LibImageBase{
	var $info;

	/**
	 * Enter description here ...
	 * @param unknown_type $img
	 * @return multitype:unknown string number Ambigous <> |boolean
	 */
	function getInfo($img){
		$imageInfo=getimagesize($img);
		if( $imageInfo!==false){
			$imageType=strtolower(substr(image_type_to_extension($imageInfo[2]),1));
			$imageSize=filesize($img);
			return array(
				"width"=>$imageInfo[0],
				"height"=>$imageInfo[1],
				"type"=>$imageType,
				"size"=>$imageSize,
				"mime"=>$imageInfo['mime']
			);
		}else{
			return false;
		}
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $im
	 * @param unknown_type $type
	 * @param unknown_type $filename
	 */
	function output($im,$type='png',$filename=''){
		header("Content-type: image/".$type);
		if($type=='bmp'){
			import('image.gd_bmp_func',LIB_DIR);
		}
		$ImageFun='image'.$type;
		function_exists($ImageFun) or die('function '.$ImageFun.' not exists.');
		if(empty($filename)){
			$ImageFun($im);
		}else{
			$ImageFun($im,$filename);
		}
		imagedestroy($im);
	}
}
