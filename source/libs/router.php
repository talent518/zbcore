<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class LibRouter{
	protected $get;
	function LibRouter(){
		$this->get=&$_GET;
		$decode=false;
		switch(CFG()->urlFormat){
			case 'base':
				$decode=$_GET['q'];
				break;
			case 'phpinfo':
				$decode=$_SERVER['PATH_INFO'];
				break;
			case 'rewrite':
				$len=strpos($_SERVER['REQUEST_URI'],'?');
				$rewrite=($len===false?$_SERVER['REQUEST_URI']:substr($_SERVER['REQUEST_URI'],0,$len));
				if($rewrite!=$_SERVER['SCRIPT_NAME']){
					$decode=((strlen(ROOT_URL)>1 && substr($rewrite,0,strlen(ROOT_URL))==ROOT_URL)?substr($rewrite,strlen(ROOT_URL)):$rewrite);
				}
				break;
		}
		if($decode!==false){
			$this->decode($decode);
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
		if(empty($args['proj']) || $proj==$args['proj']){
			$ctrl=IN_CTRL;
			$method=IN_METHOD;
		}else{
			$ctrl=$method='index';
		}

		foreach(array('proj','ctrl','method') as $var){
			if(!isset($args[$var])){
				$args[$var]=$$var;
			}
		}

		$url='/'.$args['proj'];
		$url.='/'.$args['ctrl'];
		$url.='/'.$args['method'];

		unset($args['proj'],$args['ctrl'],$args['method']);

		foreach($args as $k=>$v){
			$url.='/'.$k.'/'.urlencode($v);
		}
		if(strlen($url)>1)
			$url.=CFG()->urlSuffix;
		return $url;
	}
	function link($args=array()){
		switch(CFG()->urlFormat){
			case 'base':
				return ROOT_URL.'?q='.$this->encode($args);
			case 'phpinfo':
				return ROOT_URL.'index.php'.$this->encode($args);
			case 'rewrite':
				return substr(ROOT_URL,0,-1).$this->encode($args);
		}
	}
}
