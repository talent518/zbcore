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
		if($url=='index/index' && in_array($args['method'],array('category','picture','article'))){
			switch($args['method']){
				case 'category':
					$url='category/'.$args['cid'].($args['page']?'/'.$args['page']:null);
					unset($args['cid'],$args['page']);
					break;
				case 'picture':
					$url='picture/'.$args['id'];
					unset($args['id']);
					break;
				case 'article':
					$url='article'.($args['page']?'/'.$args['page']:($args['id']?'/show_'.$args['id']:null));
					unset($args['page'],$args['id']);
					break;
			}
		}elseif($args['method'])
			$url.='/'.$args['method'];
		else
			$url.='/index';
		unset($args['proj'],$args['ctrl'],$args['method']);
		foreach($args as $k=>$v)
			$url.='/'.$k.'/'.urlencode($v);
		if(strlen($url)>1)
			$url.='.html';
		return ROOT_URL.$url;
	}
}
