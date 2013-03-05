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
			case 'pathinfo':
				$decode=$_SERVER['PATH_INFO'];
				break;
			case 'rewrite':
				if(isset($_GET['rewrite'])){
					$decode=$_GET['rewrite'];
				}else{
					$len=strpos($_SERVER['REQUEST_URI'],'?');
					$rewrite=($len===false?$_SERVER['REQUEST_URI']:substr($_SERVER['REQUEST_URI'],0,$len));
					$decode=((strlen(ROOT_URL)>1 && substr($rewrite,0,strlen(ROOT_URL))==ROOT_URL)?substr($rewrite,strlen(ROOT_URL)):$rewrite);
				}
				break;
		}
		if($decode!==false){
			$this->decode($decode=='/'?'':$decode);
		}
	}
	function get($key){
		return $this->get[$key];
	}
	function decode($url){
		$dot=strrpos($url,'.');
		if($dot!==false){
			$is_router=substr($url,$dot)==CFG()->shortUrlSuffix;
			$url=(in_array(substr($url,$dot),array(CFG()->urlSuffix,CFG()->shortUrlSuffix))?substr($url,0,$dot):$url);
		}else{
			$is_router=true;
		}
		if($is_router && ($decode=M('router')->decode(strtolower($_SERVER['HTTP_HOST']).$url))!==false){
			$url=$decode;
		}

		$rws = explode('/',$url{0}=='/'?substr($url,1):$url);

		if(count($rws)<3){
			return;
		}

		list($this->get['proj'],$this->get['ctrl'],$this->get['method'])=$rws;

		for ($rw_i=3;$rw_i<count($rws);$rw_i=$rw_i+2) {
			$this->get[$rws[$rw_i]]=$rws[$rw_i+1];
			//$this->get[$rws[$rw_i]] = urldecode(empty($rws[$rw_i+1])?'':$rws[$rw_i+1]);
		}
		$rws=null;
	}
	function encode(&$args=array()){
		defined('IN_PROJ') or define('IN_PROJ','front');
		defined('IN_CTRL') or define('IN_PROJ','index');
		defined('IN_METHOD') or define('IN_PROJ','index');

		$proj=IN_PROJ;
		if(empty($args['proj']) || $proj==$args['proj']){
			$ctrl=IN_CTRL;
			if($ctrl==$args['ctrl']){
				$method=IN_METHOD;
			}else{
				$method='index';
			}
		}else{
			$ctrl=$method='index';
		}

		foreach(array('proj','ctrl','method') as $var){
			if(!isset($args[$var])){
				$args[$var]=$$var;
			}
		}

		$url='/'.($args['proj']=='front'?'index':$args['proj']);
		$url.='/'.$args['ctrl'];
		$url.='/'.$args['method'];

		unset($args['proj'],$args['ctrl'],$args['method']);

		if(($encode=M('router')->encode($url,$args))!==false){
			$url=$encode.CFG()->shortUrlSuffix;
		}else{
			foreach($args as $k=>$v){
				$url.='/'.$k.'/'.urlencode($v);
				unset($args[$k]);
			}
			$url.=CFG()->urlSuffix;
		}
		return $url;
	}
	function link($args=array()){
		$path=ROOT_URL;
		$q=$this->encode($args);
		if($q{0}!='/'){
			$pos=strpos($q,'/');
			if($pos===false){
				$pos=strlen($q);
			}
			$path=substr($q,0,$pos);
			$path=str_replace($_SERVER['HTTP_HOST'],$path,SITE_FULL_URL);
			$q=substr($q,$pos);
		}
		if(count($args)){
			$ext='';
			foreach($args as $k=>$v){
				if($ext!=''){
					$ext.='&';
				}
				$ext.=$k.'='.urlencode($v);
			}
		}else{
			$ext=false;
		}
		switch(CFG()->urlFormat){
			case 'base':
				return $path.'?q='.$q.($ext?'&'.$ext:null);
			case 'pathinfo':
				return $path.'index.php'.$q.($ext?'?'.$ext:null);
			case 'rewrite':
				return substr($path,0,-1).$q.($ext?'?'.$ext:null);
		}
	}
}
