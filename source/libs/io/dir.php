<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class LibIoDir{
	//获取目录
	function gets($pdir,$exts=array(),$nots=array()){
		$dirs = array();
		if($dh = @opendir($pdir)) {
			while ($file=readdir($dh)){
				$full=$pdir.$file;
				if(@in_array($full,$nots)){
				}elseif(@in_array($file,array('.','..'))){
				}elseif(is_dir($full.DIR_SEP) && count($_dirs=$this->gets($full.DIR_SEP,$exts,$nots))>0){
					$dirs[]=$full.DIR_SEP;
					$dirs=array_merge($dirs,$_dirs);
				}elseif(is_file($full) && is_array($exts) && (count($exts)==0 || in_array(strtolower(L('io.file')->ext($full)),$exts))){
					$dirs[]=$full;
				}
			}
			closedir($dh);
		}
		return $dirs;
	}

	function mkdirs($dir,$mode=0777,$recursive=false){
		if(is_null($dir) || $dir===""){
			return FALSE;
		}
		if(is_dir($dir) || $dir==="/"){
			return TRUE;
		}
		if($this->mkdirs(dirname($dir), $mode, $recursive)){
			$_umask=umask(0);
			$ret=@mkdir($dir,$mode);
			umask($_umask);
			return $ret;
		}
		return FALSE;
	}

	function drop($dir,$ischild=false){
		if(!is_dir($dir)){
			return false;
		}
		if($ischild && ($handle=@opendir($dir))){
			while(($file=@readdir($handle))!==false){
				if(!in_array($file,array('.','..'))){
					$_dir=$dir.DIR_SEP.$file;
					if(is_dir($_dir))
						$this->drop($_dir,$ischild);
					else
						@unlink($_dir);
				}
			}
			closedir($handle);
		}
		return CFG()->isEncrypt?true:@rmdir($dir);
	}

	function writeable($dir){
		$writeable=FALSE;
		$this->mkdirs($dir,0777,true);
		if(is_dir($dir)){
			if($fp=@fopen($dir.DIR_SEP.'test.txt','wb')){
				@fwrite($fp,__FILE__);
				@fclose($fp);
				@unlink($dir.DIR_SEP.'test.txt');
				$writeable=TRUE;
			}
		}
		return $writeable;
	}
}