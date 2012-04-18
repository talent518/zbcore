<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelCount{
	function &get(){
		return array(
			'user'=>DB()->count('user'),
		);
	}

	function &week(){
		return array(
			'regUser'=>DB()->count('user','regtime>'.strtotime('-1 week')),
			'activeUser'=>DB()->count('user','lastactivetime>'.strtotime('-1 week')),
		);
	}

	function &user($id){
		return array(
			'picture'=>DB()->count('user_picture','uid='.$id),
			'product'=>DB()->count('user_product','uid='.$id),
			'product_image'=>DB()->count('user_product_image','uid='.$id),
			'article'=>DB()->count('user_article','uid='.$id),
		);
	}

	function &runtime(){
		$dbsize = 0;
		$query=DB()->query("SHOW TABLE STATUS LIKE '{DB()->tablepre}%'");
		while($table=DB()->row($query)){
			$dbsize+=$table['Data_length']+$table['Index_length'];
		}
		return array(
			'mysql'=>DB()->version(),
			'dbsize'=>formatsize($dbsize),
			'fileupload'=>@ini_get('file_uploads')?ini_get('upload_max_filesize'):'<font color="red">-</font>',
		);
	}
}
