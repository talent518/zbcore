<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class LibUrlRewrite{
	private $get;
	function LibUrlRewrite(){
		$this->get=&$_GET;
		$rws = explode('/', $_GET['rewrite']);
		$get=$g=array();
		for ($rw_i=0;$rw_i<count($rws);$rw_i=$rw_i+2){
			$get[]=$rws[$rw_i].'='.$rws[$rw_i+1];
		}
		parse_str(implode('&',$get),$g);
		$this->get=array_merge($this->get,$g);
		unset($get,$g,$_GET['rewrite']);
	}
	function get($key){
		return $this->get[$key];
	}
	function link($args=array()){
		$url=($args['proj']?$args['proj']:(IN_PROJ=='front'?'index':IN_PROJ));
		$url.='/'.($args['ctrl']?$args['ctrl']:'index');
		if($args['method'])
			$url.='/'.$args['method'];
		else
			$url.='/index';
		unset($args['proj'],$args['ctrl'],$args['method']);
		foreach($args as $k=>$v){
			$url.='/'.$k.'/'.urlencode($v);
		}
		if(strlen($url)>1)
			$url.='.html';
		return ROOT_URL.$url;
	}
}
