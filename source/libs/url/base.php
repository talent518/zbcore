<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class LibUrlBase{
	protected $get;
	function LibUrlBase(){
		$this->get=&$_GET;
		if(isset($_GET['c'])){
			$_GET['ctrl']=$_GET['c'];
		}
		if(isset($_GET['m'])){
			$_GET['method']=$_GET['m'];
		}
		if(isset($_GET['q'])){
			$this->decode($_GET['q']);
		}
	}
	function get($key){
		return $this->get[$key];
	}
	function decode($url){
		if($url{0}=='/'){
			$url=substr($url,1);
		}
		$dot=strrpos($url,'.');
		if($dot!==false){
			$url=(in_array(substr($url,$dot),array(CFG()->urlSuffix,CFG()->shortUrlSuffix))?substr($url,0,$dot):$url);
		}
		$rws = explode('/',$url);
		if(count($rws))
			$this->get['proj']=array_shift($rws);
		if(count($rws))
			$this->get['ctrl']=array_shift($rws);
		if(count($rws))
			$this->get['method']=array_shift($rws);
		$get=$g=array();
		for ($rw_i=0;$rw_i<count($rws);$rw_i=$rw_i+2) {
			$get[]=$rws[$rw_i].'='.$rws[$rw_i+1];
			//$this->get[$rws[$rw_i]] = urldecode(empty($rws[$rw_i+1])?'':$rws[$rw_i+1]);
		}
		parse_str(implode('&',$get),$g);
		$this->get=array_merge($this->get,$g);
		$get=$g=null;
	}
	function encode($args=array()){
		$proj=(IN_PROJ=='front'?'index':IN_PROJ);
		$ctrl=IN_CTRL;
		$method=IN_METHOD;

		$url='/'.($args['proj']?$args['proj']:$proj);
		$url.='/'.($args['ctrl']?$args['ctrl']:$ctrl);
		$url.='/'.($args['method']?$args['method']:$method);

		unset($args['proj'],$args['ctrl'],$args['method']);

		foreach($args as $k=>$v){
			$url.='/'.$k.'/'.urlencode($v);
		}
		if(strlen($url)>1)
			$url.=CFG()->urlSuffix;
		return $url;
	}
	function link($args=array()){
		return ROOT_URL.'?q='.$this->encode($args);
	}
}
