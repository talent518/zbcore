<?php
if(! defined('IN_ZBC'))
	exit('Access Denied');

class LibTemplate {

	private $tpl, $cache, $html;

	private $calls, $vars;

	private $stackTpls, $subTpls;

	private $block_i, $block_searchs, $block_replaces;

	private $php_i, $php_searchs, $php_replaces;

	private $func_i, $func_searchs, $func_replaces;

	public $tpldir, $cachedir, $datadir, $suffix, $clearWhite, $timeout, $isEncrypt = false, $isCheckTpl = true;

	function __construct() {
		$this->calls = $this->vars = array();
		$this->init();
	}

	function __destruct() {
		$this->calls = $this->vars = array();
		$this->init();
	}

	private function init() {
		$this->subTpls = array();
		$this->block_i = 0;
		$this->block_searchs = $this->block_replaces = array();
		$this->php_i = 0;
		$this->php_searchs = $this->php_replaces = array();
		$this->func_i = 0;
		$this->func_searchs = $this->func_replaces = array();
	}

	private function getTplFile($tpl) {
		$tpl = preg_replace("/[\\|\/]+/", DIR_SEP, $tpl);
		$tpl = str_replace(DIR_SEP . '.' . DIR_SEP, DIR_SEP, $tpl);
		$tplfile = $this->tpldir . $tpl . $this->suffix;
		if(! file_exists($tplfile)) {
			if(! empty($this->defdir) && file_exists($this->defdir . $tpl . $this->suffix)) {
				$tplfile = $this->defdir . $tpl . $this->suffix;
			} else {
				exit("Template file : '$tplfile' Not found or have no access!");
			}
		}
		return $tplfile;
	}

	private function getCacheFile($html = false) {
		$tpl = preg_replace("/[\\|\/]+/", DIR_SEP, $this->tpl);
		$tpl = str_replace(DIR_SEP . '.' . DIR_SEP, DIR_SEP, $tpl);
		$cache = ($html ? 'html' . DIR_SEP . $tpl . (($html && $this->cache) ? '_' . $this->cache : '') : $tpl . ($this->html ? '_html' : ''));
		return $this->cachedir . ($this->isEncrypt ? md5($cache) : $cache) . '.php';
	}

	private function checkTpl($html = false) {
		$checked = false;
		$tplfile = $this->getTplFile($this->tpl);
		$cachefile = $this->getCacheFile($html);
		if(file_exists($cachefile)) {
			$checked = $this->isCheckTpl && filemtime($tplfile) > filemtime($cachefile);
			$checked = $checked || ($html && (filemtime($cachefile) + $this->timeout * 60 < TIMESTAMP));
		} else {
			$checked = true;
		}
		return ($checked);
	}

	private function checkSubTpl($subfiles, $mktime, $html) {
		$return = false;
		$subfiles = explode('|', $subfiles);
		foreach($subfiles as $subfile) {
			if(@filemtime($this->getTplFile($subfile)) > $mktime) {
				$return = true;
				break;
			}
		}
		if($return) {
			@unlink($this->getCacheFile());
		}
		if(($this->html && $return) || ($html && ($mktime + $this->timeout * 60 < TIMESTAMP))) {
			@unlink($this->getCacheFile(true));
			$return = true;
		}
		if($return)
			$this->display($this->tpl, $this->cache, $this->html);
		return ($return);
	}

	function setVar($var = null, $value = null) {
		if(empty($var))
			$this->vars = array();
		else
			$this->vars[$var] = $value;
	}

	function setCall($var = null, $call = null) {
		if(empty($var))
			$this->calls = array();
		else
			$this->calls[$var] = $call;
	}

	function display($tpl, $cache = '', $html = false) {
		$this->tpl = $tpl;
		$this->cache = $cache;
		$this->html = $html;
		if($this->checkTpl())
			$this->parse();

		foreach($this->vars as $var => $value)
			$$var = $value;
		if($this->html) {
			if($this->checkTpl(true)) {
				foreach($this->calls as $var => $value)
					$$var = L('cache')->getData($value);
				ob_start();
				include ($this->getCacheFile());
				$c = ob_get_contents();
				ob_end_clean();
				ob_start();
				if($this->php_i > 0)
					$c = str_replace($this->php_searchs, $this->php_replaces, $c);
				L('io.file')->write($this->getCacheFile(true), $this->with($c, true));
				$c = null;
			}
			include ($this->getCacheFile(true));
		} else {
			if($this->calls) {
				$c = L('cache');
				$c->dir = $this->datadir;
				$c->name = $tpl . $cache;
				$c->callback = array(
					&$this,
					'cache'
				);
				foreach($c->get($this->timeout) as $var => $value)
					$$var = $value;
			}
			include ($this->getCacheFile());
		}
	}

	function &cache() {
		$list = array();
		foreach($this->calls as $var => $value) {
			$list[$var] = L('cache')->getData($value);
		}
		return $list;
	}

	private function with($content, $html = false) {
		return '<?php if(!defined("IN_ZBC")) exit("Access Denied");' . ($this->isCheckTpl ? 'if(!$this->checkSubTpl(\'' . implode('|', $this->subTpls) . '\',' . TIMESTAMP . ',' . ($html ? 'true' : 'false') . ')){ ' : '') . (($this->html && ! $html) ? '$this->subTpls=' . var_export($this->subTpls, true) . ';$this->php_i=' . $this->php_i . ';$this->php_searchs=' . var_export($this->php_searchs, true) . ';$this->php_replaces=' . var_export($this->php_replaces, true) . ';' : '') . '?>' . $content . ($this->isCheckTpl ? '<?php } ?>' : '');
	}

	private function parse() {
		$this->init();
		// 模板
		$template = $this->templateTags($this->tpl);

		$template = preg_replace_callback("/\<\?/s", function () {
			return '<<php>' . $this->srcTags("echo '?';") . '</php>';
		}, $template);
		$template = preg_replace_callback("/\?\>/s", function () {
			return '<php>' . $this->srcTags("echo '?';") . '</php>>';
		}, $template);

		$template = preg_replace_callback("/\{pages:?([^\s]+)?\s+(.+?)\}/i", function ($matches) {
			return $this->pageTags($matches[1], $matches[2]);
		}, $template);

		// PHP代码
		$template = preg_replace_callback("/\<\!\-\-\{php\s+(.+?)\s*\}\-\-\>/is", function ($matches) {
			return $this->srcTags($matches[1]);
		}, $template);
		$template = preg_replace_callback("/\{php\s+(.+?)\s*\}/is", function ($matches) {
			return $this->srcTags($matches[1]);
		}, $template);

		// 逻辑
		$template = preg_replace_callback("/\{elseif\s+(.+?)\}/is", function ($matches) {
			return $this->varPregTags("<?php elseif({$matches[1]}): ?>");
		}, $template);
		$template = preg_replace_callback("/\{else\}/is", function ($matches) {
			return $this->srcTags('else:');
		}, $template);

		// 循环
		for($i = 0; $i < 6; $i ++) {
			$template = preg_replace_callback("/\{loop\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/s", function ($matches) {
				return $this->varPregTags("<?php foreach({$matches[1]} as {$matches[2]}): ?>", $matches[3], '<?php endforeach; ?>');
			}, $template);
			$template = preg_replace_callback("/\{loops\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loops\}/s", function ($matches) {
				return $this->varPregTags("<?php for({$matches[1]} = {$matches[2]}; {$matches[1]} <= {$matches[3]}; {$matches[1]} ++): ?>", $matches[4], '<?php endfor; ?>');
			}, $template);
			$template = preg_replace_callback("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/s", function ($matches) {
				return $this->varPregTags("<?php foreach({$matches[1]} as {$matches[2]} => {$matches[3]}): ?>", $matches[4], '<?php endforeach; ?>');
			}, $template);
			$template = preg_replace_callback("/\{if\s+(.+?)\}(.+?)\{\/if\}/s", function ($matches) {
				return $this->varPregTags("<?php if({$matches[1]}): ?>", $matches[2], '<?php endif; ?>');
			}, $template);
		}

		// 函数(过程)
		$template = preg_replace_callback("/\{function:?([^\s]+)?\s+(.+?)\}(.+?)\{\/function\}/s", function ($matches) {
			return $this->functionTags($matches[1], $matches[2], $matches[3], true);
		}, $template);
		$template = preg_replace_callback("/\{function\s+(.+?)\}(.+?)\{\/function\s+(.+?)\}/s", function ($matches) {
			return $this->functionTags($matches[1], $matches[2], $matches[3], false);
		}, $template);
		$template = preg_replace_callback("/\{callback:([^\s]+)\s+(.+?)\}/s", function ($matches) {
			return $this->varPregTags("<?php {$matches[1]}({$matches[2]}); ?>");
		}, $template);

		$template = preg_replace_callback("/\{strcut\s+(.+?)\}/", function ($matches) {
			return $this->varPregTags("<?=strcut({$matches[1]})?>");
		}, $template);
		$template = preg_replace_callback("/\{date\s+(.+?)\}/", function ($matches) {
			return $this->varPregTags("<?=sgmdate({$matches[1]})?>");
		}, $template);
		$template = preg_replace_callback("/\{thumb\s+(.+?)\}/", function ($matches) {
			return $this->varPregTags("<?=thumb({$matches[1]})?>");
		}, $template);

		$template = preg_replace_callback("/\{ad\s+(.+?)\}/", function ($matches) {
			return $this->varPregTags("<?=M('ad')->show({$matches[1]})?>");
		}, $template);
		$template = preg_replace_callback("/\{ad:(.+?)\}/", function ($matches) {
			return $this->varPregTags("<?=M('ad')->show(\"{$matches[1]}\")?>");
		}, $template);
		$template = preg_replace_callback("/\{adp\s+(.+?)\}/", function ($matches) {
			return $this->varPregTags("<?=M('adp')->show({$matches[1]})?>");
		}, $template);
		$template = preg_replace_callback("/\{adp:(.+?)\}/", function ($matches) {
			return $this->varPregTags("<?=M('adp')->show(\"{$matches[1]}\")?>");
		}, $template);
		$template = preg_replace_callback("/\{link\s+(.+?)\}/", function ($matches) {
			return $this->linkTags($matches[1]);
		}, $template);

		// 变量
		$template = preg_replace_callback("/\{([a-zA-Z][a-zA-Z0-9_]+)\}/", function ($matches) {
			return $this->varPregTags("<?={$matches[1]}?>");
		}, $template);
		$template = preg_replace_callback("/\{([a-zA-Z][a-zA-Z0-9_]+)\|(\w+)\}/", function ($matches) {
			return $this->varPregTags("<?=\$this->format({$matches[1]}, '{$matches[2]}')?>");
		}, $template);
		$template = preg_replace_callback("/\{(\\\$[a-zA-Z0-9_\.:]+)\}/", function ($matches) {
			return $this->varPregTags("<?={$matches[1]}?>");
		}, $template);
		$template = preg_replace_callback("/\{(\\\$[a-zA-Z0-9_\.:]+)\|(\w+)\}/", function ($matches) {
			return $this->varPregTags("<?=\$this->format({$matches[1]}, '{$matches[2]}')?>");
		}, $template);
		$template = preg_replace_callback("/\{(\\\$[a-zA-Z0-9_\-\+\=\[\]]+\;)\}/", function ($matches) {
			return $this->varPregTags("<?php {$matches[1]} ?>");
		}, $template);
		$template = preg_replace_callback("/\{var\s+(\\\$.+?\;)\}/", function ($matches) {
			return $this->varPregTags("<?php {$matches[1]} ?>");
		}, $template);
		$template = preg_replace_callback("/(\\\$[a-zA-Z0-9]+)/", function ($matches) {
			return $this->varPregTags("<?={$matches[1]}?>");
		}, $template);

		// 清除空白字符
		if($this->clearWhite) {
			$template = preg_replace("/\s*[\r\n]+\s*/s", '', $template);
		}

		// 替换
		if($this->block_i > 0) {
			$template = str_replace($this->block_searchs, $this->block_replaces, $template);
		}
		if($this->func_i > 0) {
			$template = str_replace($this->func_searchs, $this->func_replaces, $template);
		}
		$template = preg_replace("/ \?\>[\s]*\<\?php /s", '', $template);

		// 静态缓存动态代码
		if($this->html) {
			$template = preg_replace_callback("/\<php\>(.+?)\<\/php\>/s", function ($matches) {
				return $this->phpTags($matches[1]);
			}, $template);
		} else {
			$template = strtr($template, [
				'<php>' => '',
				'</php>' => ''
			]);
		}
		L('io.file')->write($this->getCacheFile(), $this->with($template)) or die('Template : Cache file "' . $this->getCacheFile() . '" cannot write.');
		$template = null;
	}

	private function templateTags($name) {
		if(! in_array($name, $this->subTpls))
			$this->subTpls[] = $name;
		if(isset($this->stackTpls[md5($name)]))
			return 'Template : "' . $name . '" already exists!';

		$content = L('io.file')->read($this->getTplFile($name));
		$this->stackTpls[md5($name)] = 1;

		// 清除模版中的防下载
		$content = str_replace("<?exit('Access Denied')?>", '', $content);
		$content = preg_replace_callback("/{template\s+([a-z0-9_\/]+)\}/i", function ($matches) {
			return $this->templateTags($matches[1]);
		}, $content);

		$this->stackTpls[md5($name)] = 0;
		return $content;
	}

	private function mkVar($var) {
		$vars = explode('.', $var);
		$var = array_shift($vars);
		$var = str_replace(':', '->', $var);
		foreach($vars as $v) {
			$vs = explode(':', $v);
			$v = array_shift($vs);
			$var .= (is_numeric($v) ? "[{$v}]" : "['{$v}']");
			if(count($vs) > 0)
				$var .= '->' . implode('->', $vs);
		}
		return $var;
	}

	private function srcTags($php) {
		$this->block_i ++;
		$this->block_searchs[$this->block_i] = '<!--EVAL_TAG_' . $this->block_i . '-->';
		$this->block_replaces[$this->block_i] = "<?php $php ?>";
		return $this->block_searchs[$this->block_i];
	}

	private function pageTags($tpl, $parameter) {
		static $isTpl;
		if(! $isTpl)
			$this->subTpls[] = $tpl ? $tpl : 'page';
		$tpl = L('io.file')->read($this->getTplFile($tpl ? $tpl : 'page'));
		return preg_replace("/\{page\|(.+?)}/", "{link $parameter page=\\1}", $tpl);
	}

	private function linkTags($parameter) {
		$args = array();
		foreach(explode(' ', $parameter) as $arg) {
			list($key, $value) = explode('=', $arg);
			$args[] = sprintf('\'%s\'=>%s', $key, substr($value, 0, 1) == '$' ? $this->mkVar($value) : "'$value'");
		}
		$this->block_i ++;
		$this->block_searchs[$this->block_i] = '<!--LINK_TAG_' . $this->block_i . '-->';
		$this->block_replaces[$this->block_i] = "<?=URL(array(" . implode(',', $args) . "))?>";
		return $this->block_searchs[$this->block_i];
	}

	private function varPregTags($beginTag, $_return = '', $endTag = '') {
		$beginTag = preg_replace_callback("/(\\\$[a-zA-Z0-9_\.:]+)/", function ($matches) {
			return $this->mkVar($matches[1]);
		}, $beginTag);
		$this->block_i ++;
		$this->block_searchs[$this->block_i] = '<!--BEGIN_TAG_' . $this->block_i . '-->';
		$this->block_replaces[$this->block_i] = $beginTag;
		$return = $this->block_searchs[$this->block_i];
		if($_return)
			$return .= $_return;
		if($endTag) {
			$this->block_i ++;
			$this->block_searchs[$this->block_i] = '<!--END_TAG_' . $this->block_i . '-->';
			$this->block_replaces[$this->block_i] = $endTag;
			$return .= $this->block_searchs[$this->block_i];
		}
		return $return;
	}

	private function phpTags($php) {
		$this->php_i ++;
		$this->php_searchs[$this->php_i] = '<!--PHP_TAG_' . $this->php_i . '-->';
		$this->php_replaces[$this->php_i] = $php;
		return $this->php_searchs[$this->php_i];
	}

	private function functionTags($name, $args, $code, $isName) {
		if($isName) {
			$args = preg_replace_callback("/(\\\$[a-zA-Z0-9_\.:]+)/", function ($matches) {
				return $this->mkVar($matches[1]);
			}, $args);
		} else {
			$args1 = preg_replace_callback("/(\\\$[a-zA-Z0-9_\.:]+)/", function ($matches) {
				return $this->mkVar($matches[1]);
			}, $code);
			$code = $args;
			$args = preg_replace_callback("/(\\\$[a-zA-Z0-9_\.:]+)/", function ($matches) {
				return $this->mkVar($matches[1]);
			}, $name);
			static $funcs;
			if(! $funcs)
				$funcs = 1;
			else
				$funcs ++;
			$name = preg_replace('/[^a-z0-9_]+/i', '', $this->tpl) . $funcs;
		}
		$code = preg_replace_callback("/\{callback\s+(.+?)\}/s", function ($matches) use ($name) {
			return $this->varPregTags("<?php {$name}({$matches[1]}); ?>");
		}, $code);
		$this->func_i ++;
		$this->func_searchs[$this->func_i] = '<!--FUNC_BEGIN_TAG_' . $this->func_i . '-->';
		$this->func_replaces[$this->func_i] = '<?php function ' . $name . '(' . $args . '){ ?>';
		$code = $this->func_searchs[$this->func_i] . $code;
		$this->func_i ++;
		$this->func_searchs[$this->func_i] = '<!--FUNC_END_TAG_' . $this->func_i . '-->';
		$this->func_replaces[$this->func_i] = ($isName ? '<?php } ?>' : '<?php } ' . $name . '(' . $args1 . '); ?>');
		return $code . $this->func_searchs[$this->func_i];
	}

	private function format($string, $format) {
		switch($format) {
			case 'text': // html to text
				$string = shtmlspecialchars($string);
				break;
			case 'html': // text to html
				$string = str_replace(array(
					' ',
					"\r\n",
					"\r",
					"\n"
				), array(
					'&nbsp;',
					'<br/>',
					'<br/>',
					'<br/>'
				), htmlspecialchars($string));
				break;
			case 'js': // php var to json
				$string = L('json')->encode($string);
				break;
			case 'money': // numeric to money
				$string = sprintf('<b class="numeric"><font style="font-family:Arial;">¥</font>%.2f</b>', $string);
				break;
		}
		return $string;
	}
}
