<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlSpace extends CtrlBase{
	var $UID=0;
	var $USERNAME='';
	var $EMAIL='';
	var $USER=array();
	var $DATUM=array();
	function __construct(){
		parent::__construct();
		$this->USER=(GET('uid')?M('user')->get((int)GET('uid')):M('user')->get_by_where('username=\''.GET('username').'\''));
		if($this->USER){
			$this->UID=$this->USER['uid'];
			$this->USERNAME=$this->USER['username'];
			$this->EMAIL=$this->USER['email'];
			$this->DATUM=M('user.datum')->get($this->UID);

			$this->setVar('UID',$this->UID);
			$this->setVar('USERNAME',$this->USERNAME);
			$this->setVar('EMAIL',$this->EMAIL);
			$this->setVar('USER',$this->USER);
			$this->setVar('DATUM',$this->DATUM);
		}else{
			$this->message('空间不存在');
		}
	}
	function onIndex(){
		$this->setVar('head',array('title'=>'首页'));
		$this->display('space/index');
	}
	function onPicture(){
		$this->setVar('head',array('title'=>'图片'));
		$this->display('space/picture');
	}
	function onProduct(){
		$this->setVar('head',array('title'=>'产品'));
		if(($product=M('user.product')->get((int)GET('id'))) && $product['uid']==$this->UID){
			$product['views']++;
			$this->setVar('head',array('title'=>$product['title'].' - 产品'));
			$this->setVar('product',$product);
			$this->display('space/product_view');
			M('user.product')->update(array('views'=>'views+1'),$product['prod_id'],false);
		}else{
			$this->display('space/product');
		}
	}
	function onArticle(){
		$this->setVar('head',array('title'=>'文章'));
		if(($article=M('user.article')->get((int)GET('id'))) && $article['uid']==$this->UID){
			$article['views']++;
			$this->setVar('head',array('title'=>$article['title'].' - 产品'));
			$this->setVar('article',$article);
			$this->display('space/article_view');
			M('user.article')->update(array('views'=>'views+1'),$article['art_id'],false);
		}else{
			$this->display('space/article');
		}
	}
	function onAbout(){
		$this->setVar('head',array('title'=>'关于我们'));
		$this->display('space/about');
	}
	function onContact(){
		$this->setVar('head',array('title'=>'联系我们'));
		$this->display('space/contact');
	}
}
