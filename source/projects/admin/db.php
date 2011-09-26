<?
if(!defined('IN_SITE'))
	exit('Access Denied');

define('DB_CACHE_DIR',CACHE_DIR.'db'.DIR_SEP);

class CtrlDb extends CtrlBase{
	var $id=0;
	function __construct(){
		parent::__construct();
		is_dir(DB_CACHE_DIR) or mkdir(DB_CACHE_DIR,777,true);
	}
	function onIndex(){
		$this->onList();
	}
	function onList(){
		$sqls=array();
		if($dh=@opendir(DB_CACHE_DIR)){
			while ($file=readdir($dh)){
				if(!@in_array($dir,array('.','..')) && is_file(DB_CACHE_DIR.$file))
					$sqls[]=substr($file,0,-4);
			}
			closedir($dh);
		}
		$this->setVar('sqls',$sqls);
		$this->display('db');
	}
	function onBackup(){
		if(M('db')->export(DB_CACHE_DIR.sgmdate('YmdHis').'.sql'))
			$this->message('数据库还原成功',URL(array('ctrl'=>'db','method'=>'list')),true);
		else
			$this->message('数据库还原失败，或文件不存在','',false);
	}
	function onRestore(){
		$fn=DB_CACHE_DIR.GET('fn').'.sql';
		if(file_exists($fn) && M('db')->import($fn))
			$this->message('数据库还原成功','',true);
		else
			$this->message('数据库还原失败，或文件不存在','',false);
	}
	function onDelete(){
		@unlink(DB_CACHE_DIR.GET('fn').'.sql');
		$this->message('删除成功',URL(array('ctrl'=>'db','method'=>'list')),true);
	}
}
