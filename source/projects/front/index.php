<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class CtrlIndex extends CtrlBase{
	var $isCachePage=true;
	function __construct(){
		parent::__construct();
	}
	function onIndex(){
		$this->display('index',null,true);
	}
	function onDefault(){
		$this->setVar('catid',0);
		$this->display('default',null,true);
	}
	function onCategory(){
		$id=GET('id')+0;
		if(!$cat=M('category')->get($id))
			$this->message('栏目不存在!');
		$this->setVar('catid',$id);
		$this->setVar('category',$cat);
		$head=unserialize($cat['cseo']);
		$ctpl=unserialize($cat['ctpl']);
		if(empty($head['title']))
			$head['title']=$cat['cat_name'];
		if(empty($head['keyword']))
			$head['keyword']=$cat['cat_name'];
		if(empty($head['description']))
			$head['description']=$cat['cat_name'];
		$this->setVar('head',$head);
		$page=GET('page')+0;
		if($cat['ctype']!='page' && M('category')->get_child($id,array())){
			$tpl=$ctpl['cate'];
		}else{
			$tpl=$ctpl['list'];
		}
		$this->display($cat['ctype'].'/'.$tpl,$id.'-'.$page,true);
	}
	function onPicture(){
		$id=GET('id')+0;
		if($pic=M('picture')->get($id)){
			$_GET['method']='category';
			$_GET['id']=$pic['cat_id'];
			$cat=M('category')->get($pic['cat_id']);
			$this->setVar('head',array('title'=>$pic['title'].' - '.$cat['cat_name'],'keyword'=>$pic['title'],'description'=>$pic['remark']));
			$this->setVar('category',$cat);
			$this->setVar('catid',$pic['cat_id']);
			$this->setVar('picture',$pic);
			$this->setVar('pictures',M('picture.image')->get_list_by_where($id));

			$ctpl=unserialize($cat['ctpl']);
			$this->display('picture/'.$ctpl['view'],$id,true);
		}else
			$this->message('图片不存在!');
	}
	function onPictureView(){
		$id=GET('id')+0;
		if($pic=M('picture')->get($id)){
			$pic['views']++;
			M('picture')->update(array('views'=>'views+1'),$id,false);
			if(GET('vid')){
				exit('document.getElementById("'.GET('vid').'").innerHTML="'.$pic['views'].'"');
			}
		}else
			exit('alert("错误!")');
	}
	function onArticle(){
		$id=GET('id')+0;
		if($art=M('article')->get($id)){
			$_GET['method']='category';
			$_GET['id']=$art['cat_id'];
			$head=unserialize($art['seo']);
			if(!$cat=M('category')->get($art['cat_id']))
				$this->message('栏目不存在!');
			if(empty($head['title']))
				$head['title']=$art['title'].' - '.$cat['cat_name'];
			if(empty($head['keyword']))
				$head['keyword']=$art['title'].' - '.$cat['cat_name'];
			if(empty($head['description']))
				$head['description']=$art['title'].' - '.$cat['cat_name'];
			$this->setVar('category',$cat);
			$head=array_merge(unserialize($cat['cseo']),$head);
			$ctpl=unserialize($cat['ctpl']);

			$this->setVar('catid',$art['cat_id']);
			$this->setVar('head',$head);
			$this->setVar('article',$art);

			$this->display('article/'.$ctpl['view'],$id,true);
		}else
			$this->message('错误地址');
	}
	function onArticleView(){
		$id=GET('id')+0;
		if($art=M('article')->get($id)){
			$art['views']++;
			M('article')->edit($id,array('views'=>'views+1'),false,false);
			if(GET('vid')){
				exit('document.getElementById("'.GET('vid').'").innerHTML="'.$art['views'].'"');
			}
		}else
			exit('alert("错误!")');
	}
	function onPage(){
		$id=GET('id')+0;
		if($pg=M('page')->get($id)){
			$_GET['method']='category';
			$_GET['id']=$pg['cat_id'];
			$head=unserialize($pg['seo']);
			if(empty($head['title']))
				$head['title']=$pg['title'];
			if(!$cat=M('category')->get($pg['cat_id']))
				$this->message('栏目不存在!');
			$this->setVar('category',$cat);
			$head=array_merge(unserialize($cat['cseo']),$head);
			$ctpl=unserialize($cat['ctpl']);

			$this->setVar('catid',$pg['cat_id']);
			$this->setVar('head',$head);
			$this->setVar('page',$pg);

			$this->display('page/'.$ctpl['view'],$id,true);
		}else
			$this->message('错误地址');
	}
}
