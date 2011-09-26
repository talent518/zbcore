<?
if(!defined('IN_SITE'))
exit('Access Denied');

class LibUpload{
	private $_type=array(
		'image'=>array('jpg','png','gif','bmp'),
		'flash'=>array('swf','flv'),
	),$lib;
	var $error,$file,$url;
	function save($file,$type,$path='',$name=''){
		$this->error='';
		$this->url='';
		$this->file='';
		if(empty($file))
			return false;
		switch($file['error']){
			case UPLOAD_ERR_OK://0
				$ext=L('io.file')->ext($file['name']);
				if(!in_array($ext,$this->_type[$type])){
					$this->error='文件类型错误，只能上传扩展名为“.'.implode(',.',$this->_type[$type]).'”的文件';
					return false;
				}
				$path=($path?$path.'/':$type.'/');
				if($name){
					$this->url=$path.$name.'.'.$ext;
				}else{
					$rename=true;
					while($rename){
						$name=date('YmdHis',TIMESTAMP).rand(0,99999);
						$this->url=$path.$name.'.'.$ext;
						if(!file_exists(RES_UPLOAD_DIR.$this->url))
							$rename=false;
					}
				}
				$this->file=RES_UPLOAD_DIR.str_replace('/',DIR_SEP,$this->url);
				if(!is_dir(RES_UPLOAD_DIR.$path))
					@mkdir(RES_UPLOAD_DIR.$path,777,true);
				if(L('io.file')->move($file['tmp_name'],$this->file)){
					@chmod($target,777);
					return true;
				}else{
					return false;
				}
				break;
			case UPLOAD_ERR_INI_SIZE://1
				$this->error='上传的文件超过了 php.ini 中 '.@ini_get('upload_max_filesize').' 选项限制的值';
				break;
			case UPLOAD_ERR_FORM_SIZE://2
				$maxfilesize=@ini_get('MAX_FILE_SIZE')?@ini_get('MAX_FILE_SIZE'):@ini_get('upload_max_filesize');
				$this->error='上传文件的大小超过了 HTML 表单中 '.$maxfilesize.' 选项指定的值';
				break;
			case UPLOAD_ERR_PARTIAL://3
				$this->error='文件只有部分被上传';
				break;
			case UPLOAD_ERR_NO_FILE://4
				//没有文件被上传
				break;
			case UPLOAD_ERR_NO_TMP_DIR://6
				$this->error='找不到临时文件夹';
				break;
			case UPLOAD_ERR_CANT_WRITE://7
				$this->error='文件写入失败';
				break;
		}
		return false;
	}
	function saveImage($file,$path='',$name=''){
		return $this->save($file,'image',$path,$name);
	}
	function saveFlash($file,$path='',$name=''){
		return $this->save($file,'flash',$path,$name);
	}
}
