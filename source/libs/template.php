<?php
if(!defined('IN_SITE'))
	exit('Access Denied');

class LibTemplate{
	private $tpl,$cache,$html;
	private $calls,$vars;
	private $stackTpls,$subTpls;
	private $block_i,$block_searchs,$block_replaces;
	private $php_i,$php_searchs,$php_replaces;
	private $func_i,$func_searchs,$func_replaces;
	public $tpldir,$cachedir,$datadir,$suffix,$clearWhite,$timeout,$isEncrypt=false,$isCheckTpl=true;

	function __construct(){
		$this->calls=$this->vars=array();
		$this->init();
	}

	function __destruct(){
		$this->calls=$this->vars=array();
		$this->init();
	}

	private function init(){
		$this->subTpls=array();
		$this->block_i=0;
		$this->block_searchs=$this->block_replaces=array();
		$this->php_i=0;
		$this->php_searchs=$this->php_replaces=array();
		$this->func_i=0;
		$this->func_searchs=$this->func_replaces=array();
	}
	private function getTplFile($tpl){
		$tpl=preg_replace("/[\\|\/]+/",DIR_SEP,$tpl);
		$tpl=str_replace(DIR_SEP.'.'.DIR_SEP,DIR_SEP,$tpl);
		$tplfile=$this->tpldir.$tpl.$this->suffix;
		if(!file_exists($tplfile)){
			if(!empty($this->defdir) && file_exists($this->defdir.$tpl.$this->suffix)){
				$tplfile=$this->defdir.$tpl.$this->suffix;
			}else{
				exit("Template file : '$tplfile' Not found or have no access!");
			}
		}
		return $tplfile;
	}
	private function getCacheFile($html=false){
		$tpl=preg_replace("/[\\|\/]+/",DIR_SEP,$this->tpl);
		$tpl=str_replace(DIR_SEP.'.'.DIR_SEP,DIR_SEP,$tpl);
		$cache=($html?'html'.DIR_SEP.$tpl.(($html && $this->cache)?'_'.$this->cache:''):$tpl.($this->html?'_html':''));
		return $this->cachedir.($this->isEncrypt?md5($cache):$cache).'.php';;
	}
	private function checkTpl($html=false){
		$checked=false;
		$tplfile=$this->getTplFile($this->tpl);
		$cachefile=$this->getCacheFile($html);
		if(file_exists($cachefile)){
			$checked=$this->isCheckTpl && filemtime($tplfile)>filemtime($cachefile);
			$checked=$checked || ($html && (filemtime($cachefile)+$this->timeout*60<TIMESTAMP));
		}else{
			$checked=true;
		}
		return($checked);
	}
	private function checkSubTpl($subfiles,$mktime,$html){
		$return=false;
		$subfiles = explode('|', $subfiles);
		foreach ($subfiles as $subfile){
			if(@filemtime($this->getTplFile($subfile))>$mktime){
				$return=true;
				break;
			}
		}
		if($return){
			@unlink($this->getCacheFile());
		}
		if(($this->html && $return) || ($html && ($mktime+$this->timeout*60<TIMESTAMP))){
			@unlink($this->getCacheFile(true));
			$return=true;
		}
		if($return)
			$this->display($this->tpl,$this->cache,$this->html);
		return($return);
	}
	function setVar($var=null,$value=null){
		if(empty($var))
			$this->vars=array();
		else
			$this->vars[$var]=$value;
	}
	function setCall($var=null,$call=null){
		if(empty($var))
			$this->calls=array();
		else
			$this->calls[$var]=$call;
	}
	function display($tpl,$cache='',$html=false){
		$this->tpl=$tpl;
		$this->cache=$cache;
		$this->html=$html;
		if($this->checkTpl())
			$this->parse();

		foreach($this->vars as $var=>$value)
			$$var=$value;
		if($this->html){
			if($this->checkTpl(true)){
				foreach($this->calls as $var=>$value)
					$$var=L('cache')->getData($value);
				ob_start();
				include($this->getCacheFile());
				$c=ob_get_contents();ob_end_clean();ob_start();
				if($this->php_i>0)
					$c=str_replace($this->php_searchs,$this->php_replaces,$c);
				L('io.file')->write($this->getCacheFile(true),$this->with($c,true));$c=null;
			}
			include($this->getCacheFile(true));
		}else{
			if($this->calls){
				$c=L('cache');
				$c->dir=$this->datadir;
				$c->name=$tpl.$cache;
				$c->callback=array(&$this,'cache');
				foreach($c->get($this->timeout) as $var=>$value)
					$$var=$value;
			}
			include($this->getCacheFile());
		}
	}
	function &cache(){
		$list=array();
		foreach($this->calls as $var=>$value){
			$list[$var]=L('cache')->getData($value);
		}
		return $list;
	}
	private function with($content,$html=false){
		return '<?php if(!defined("IN_SITE")) exit("Access Denied");'.($this->isCheckTpl?'if(!$this->checkSubTpl(\''.implode('|', $this->subTpls).'\','.TIMESTAMP.','.($html?'true':'false').')){ ':'').(($this->html && !$html)?'$this->subTpls='.var_export($this->subTpls,true).';$this->php_i='.$this->php_i.';$this->php_searchs='.var_export($this->php_searchs,true).';$this->php_replaces='.var_export($this->php_replaces,true).';':'').'?>'.$content.($this->isCheckTpl?'<?php } ?>':'');
	}
	private function parse(){
		$this->init();
		//模板
		$template = $this->templateTags($this->tpl);

		$template = preg_replace("/\<\?/es", "'<<php>'.\$this->srcTags('echo \'?\';').'</php>'", $template);
		$template = preg_replace("/\?\>/es", "'<php>'.\$this->srcTags('echo \'?\';').'</php>>'", $template);

		$template = preg_replace("/\{pages:?([^\s]+)?\s+(.+?)\}/ie", "\$this->pageTags('\\1','\\2')", $template);

		//PHP代码
		$template = preg_replace("/\<\!\-\-\{php\s+(.+?)\s*\}\-\-\>/ies", "\$this->srcTags('\\1')", $template);
		$template = preg_replace("/\{php\s+(.+?)\s*\}/ies", "\$this->srcTags('\\1')", $template);

		//逻辑
		$template = preg_replace("/\{elseif\s+(.+?)\}/ies", "\$this->varPregTags('<?php }elseif(\\1){ ?>')", $template);
		$template = preg_replace("/\{else\}/is", "<?php }else{ ?>", $template);
		//循环
		for($i = 0; $i < 6; $i++){
			$template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/es", "\$this->varPregTags(substr('\\1',0,1)=='\$'?'<?php if(is_array(\\1)){ foreach(\\1 as \\2){ ?>':'<?php \$_arrays=\\1;if(is_array(\$_arrays)){ foreach(\$_arrays as \\2){ ?>','\\3',substr('\\1',0,1)!='\$'?'<?php } }\$_arrays=null;unset(\$_arrays); ?>':'<?php } } ?>')", $template);
			$template = preg_replace("/\{loops\s+(\S+)\s+(\S+)\}(.+?)\{\/loops\}/es", "\$this->varPregTags('<?php for(\\1=1;\\1<=\\2;\\1++){ ?>','\\3','<?php } ?>')", $template);
			$template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/es", "\$this->varPregTags(substr('\\1',0,1)=='\$'?'<?php if(is_array(\\1)){ foreach(\\1 as \\2 => \\3){ ?>':'<?php \$_arrays=\\1;if(is_array(\$_arrays)){ foreach(\$_arrays as \\2 => \\3){ ?>','\\4',substr('\\1',0,1)!='\$'?'<?php } }\$_arrays=null;unset(\$_arrays); ?>':'<?php } } ?>')", $template);
			$template = preg_replace("/\{if\s+(.+?)\}(.+?)\{\/if\}/es", "\$this->varPregTags('<?php if(\\1){ ?>','\\2','<?php } ?>')", $template);
		}
		//函数(过程)
		$template = preg_replace("/\{function:?([^\s]+)?\s+(.+?)\}(.+?)\{\/function\}/es", "\$this->functionTags('\\1','\\2','\\3',true)", $template);
		$template = preg_replace("/\{function\s+(.+?)\}(.+?)\{\/function\s+(.+?)\}/es", "\$this->functionTags('\\1','\\2','\\3',false)", $template);
		$template = preg_replace("/\{callback:([^\s]+)\s+(.+?)\}/es", "\$this->varPregTags('<?php \\1(\\2); ?>')", $template);

		$template = preg_replace("/\{strcut\s+(.+?)\}/e", "\$this->varPregTags('<?=strcut(\\1)?>')", $template);
		$template = preg_replace("/\{date\s+(.+?)\}/e", "\$this->varPregTags('<?=sgmdate(\\1)?>')", $template);
		$template = preg_replace("/\{thumb\s+(.+?)\}/e", "\$this->varPregTags('<?=thumb(\\1)?>')", $template);

		$template = preg_replace("/\{ad\s+(.+?)\}/e", "\$this->varPregTags('<?=M(\\'adp\\')->show(\\1)?>')", $template);
		$template = preg_replace("/\{link\s+(.+?)\}/e", "\$this->linkTags('\\1')", $template);

		//变量
		$template = preg_replace("/\{([a-zA-Z][a-zA-Z0-9_]+)\}/", "<?=\\1?>", $template);
		$template = preg_replace("/\{([a-zA-Z][a-zA-Z0-9_]+)\|(\w+)\}/", "\$this->varPregTags('<?=\$this->format(\\1,\\'\\2\\')?>')", $template);
		$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\.:]+)\}/e", "\$this->varPregTags('<?=\\1?>')", $template);
		$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\.:]+)\|(\w+)\}/e", "\$this->varPregTags('<?=\$this->format(\\1,\\'\\2\\')?>')", $template);
		$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\-\+\=\[\]]+\;)\}/e", "\$this->varPregTags('<?php \\1 ?>')", $template);
		$template = preg_replace("/\{var (\\\$.+?)\}/e", "\$this->varPregTags('<?php \\1 ?>')", $template);
		$template = preg_replace("/(\\\$[a-zA-Z0-9]+)/", "<?=\\1?>", $template);

		//清除空白字符
		if($this->clearWhite){
			$template = preg_replace("/\s*[\r\n]+\s*/s",'', $template);
		}

		//替换
		if($this->block_i>0){
			$template = str_replace($this->block_searchs, $this->block_replaces, $template);
		}
		if($this->func_i>0){
			$template = str_replace($this->func_searchs, $this->func_replaces, $template);
		}
		$template = preg_replace("/ \?\>[\s]*\<\?php /s", '', $template);

		//静态缓存动态代码
		if($this->html){
			$template = preg_replace("/\<php\>(.+?)\<\/php\>/se", "\$this->phpTags('\\1')", $template);
		}else{
			$template = str_replace(array('<php>','</php>'),array('',''),$template);
		}
		L('io.file')->write($this->getCacheFile(),$this->with($template)) or die('Template : Cache file "'.$this->getCacheFile().'" cannot write.');
		$template=null;
	}

	private function templateTags($name){
		if(!in_array($name,$this->subTpls))	$this->subTpls[]=$name;
		if($this->stackTpls[md5($name)]) return 'Template : "'.$name.'" already exists!';

		$content = L('io.file')->read($this->getTplFile($name));
		$this->stackTpls[md5($name)]=1;

		//清除模版中的防下载
		$content = str_replace("<?exit('Access Denied')?>", '', $content);

		$content = preg_replace("/{template\s+([a-z0-9_\/]+)\}/ie", "\$this->templateTags('\\1')", $content);
		$this->stackTpls[md5($name)]=0;
		return $content;
	}

	private function mkVar($var){
		$vars=explode('.',$var);
		$var=array_shift($vars);
		$var=str_replace(':','->',$var);
		foreach($vars as $v){
			$vs=explode(':',$v);
			$v=array_shift($vs);
			$var.=(is_numeric($v)?"[{$v}]":"['{$v}']");
			if(count($vs)>0)
				$var.='->'.implode('->',$vs);
		}
		return $var;
	}

	private function srcTags($php){
		$this->block_i++;
		$this->block_searchs[$this->block_i] = '<!--EVAL_TAG_'.$this->block_i.'-->';
		$this->block_replaces[$this->block_i] = "<?php ".str_replace('\\"','"',$php)." ?>";
		return $this->block_searchs[$this->block_i];
	}
	private function pageTags($tpl,$parameter){
		static $isTpl;
		if(!$isTpl)
			$this->subTpls[]=$tpl?$tpl:'page';
		$tpl=L('io.file')->read($this->getTplFile($tpl?$tpl:'page'));
		return preg_replace("/\{page\|(.+?)}/", "{link $parameter page=\\1}", $tpl);
	}
	private function linkTags($parameter){
		$args=array();
		foreach(explode(' ',$parameter) as $arg){
			list($key,$value)=explode('=',$arg);
			$args[]=sprintf('\'%s\'=>%s',$key,substr($value,0,1)=='$'?$this->mkVar($value):"'$value'");
		}
		$this->block_i++;
		$this->block_searchs[$this->block_i] = '<!--LINK_TAG_'.$this->block_i.'-->';
		$this->block_replaces[$this->block_i] = "<?=URL(array(".implode(',',$args)."))?>";
		return $this->block_searchs[$this->block_i];
	}
	private function varPregTags($beginTag, $_return='',$endTag=''){
		$beginTag=preg_replace("/(\\\$[a-zA-Z0-9_\.:]+)/e", "\$this->mkVar('\\1')", $beginTag);
		$this->block_i++;
		$this->block_searchs[$this->block_i] = '<!--BEGIN_TAG_'.$this->block_i.'-->';
		$this->block_replaces[$this->block_i] = $beginTag;
		$return=$this->block_searchs[$this->block_i];
		if($_return)
			$return.=str_replace('\\"','"',$_return);
		if($endTag){
			$this->block_i++;
			$this->block_searchs[$this->block_i] = '<!--END_TAG_'.$this->block_i.'-->';
			$this->block_replaces[$this->block_i] = $endTag;
			$return.=$this->block_searchs[$this->block_i];
		}
		return $return;
	}
	private function phpTags($php){
		$this->php_i++;
		$this->php_searchs[$this->php_i] = '<!--PHP_TAG_'.$this->php_i.'-->';
		$this->php_replaces[$this->php_i] = str_replace('\\"','"',$php);
		return $this->php_searchs[$this->php_i];
	}
	private function functionTags($name,$args,$code,$isName){
		if($isName){
			$args=preg_replace("/(\\\$[a-zA-Z0-9_\.:]+)/e", "\$this->mkVar('\\1')", $args);
		}else{
			$args1=preg_replace("/(\\\$[a-zA-Z0-9_\.:]+)/e", "\$this->mkVar('\\1')", $code);
			$code=$args;
			$args=preg_replace("/(\\\$[a-zA-Z0-9_\.:]+)/e", "\$this->mkVar('\\1')", $name);;
			static $funcs;
			if(!$funcs)
				$funcs=1;
			else
				$funcs++;
			$name=preg_replace('/[^a-z0-9_]+/i','',$this->tpl).$funcs;
		}
		$code = preg_replace("/\{callback\s+(.+?)\}/es", "\$this->varPregTags('<?php {$name}(\\1); ?>')", $code);
		$this->func_i++;
		$this->func_searchs[$this->func_i] = '<!--FUNC_BEGIN_TAG_'.$this->func_i.'-->';
		$this->func_replaces[$this->func_i] = '<?php function '.$name.'('.$args.'){ ?>';
		$code=$this->func_searchs[$this->func_i].$code;
		$this->func_i++;
		$this->func_searchs[$this->func_i] = '<!--FUNC_END_TAG_'.$this->func_i.'-->';
		$this->func_replaces[$this->func_i] = ($isName?'<?php } ?>':'<?php } '.$name.'('.$args1.'); ?>');
		return $code.$this->func_searchs[$this->func_i];
	}
	private function format($string,$format){
		switch($format){
			case 'text'://html to text
				$string=shtmlspecialchars($string);
				break;
			case 'html'://text to html
				$string=str_replace(
					array(' ',"\r\n","\r","\n"),
					array('&nbsp;','<br/>','<br/>','<br/>'),
					htmlspecialchars($string)
				);
				break;
			case 'js'://php var to json
				$string=L('json')->encode($string);
				break;
			case 'money'://numeric to money
				$string=sprintf('<b class="numeric"><font style="font-family:Arial;">¥</font>%.2f</b>',$string);
				break;
		}
		return $string;
	}
}
