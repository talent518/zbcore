<?
if(!defined('IN_SITE'))
exit('Access Denied');

class LibUrlBase{
	private $get;
	function LibUrlBase(){
		if(isset($_GET['c'])){
			$_GET['ctrl']=$_GET['c'];
		}
		if(isset($_GET['m'])){
			$_GET['method']=$_GET['m'];
		}
		$this->get=&$_GET;
	}
	function get($key){
		return $this->get[$key];
	}
	function link($args=array()){
		$url=($args['proj']?$args['proj']:(IN_PROJ=='front'?'index':IN_PROJ)).'.php';
		unset($args['proj']);
		$q=array();
		if(isset($args['ctrl'])){
			$q[]='c='.urlencode($args['ctrl']);
			unset($args['ctrl']);
		}
		if(isset($args['method'])){
			$q[]='m='.urlencode($args['method']);
			unset($args['method']);
		}
		foreach($args as $key=>$value){
			$q[]=$key.'='.urlencode($value);
		}
		if($q)
			$url.='?'.implode('&',$q);
		return ROOT_URL.$url;
	}
}
