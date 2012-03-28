<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class ZBCore{
	private $isEchoPaged=false,$isAutoEchoPage=false;
	var $isCachePage=false;
	function __construct(){
		$this->ZBCore();
	}
	function ZBCore(){
		$tpl=L('template');
		$tpl->suffix=CFG()->tplSuffix;
		$tpl->clearWhite=CFG()->tplClearWhite;
		$tpl->timeout=CFG()->timeout;
		$tpl->isEncrypt=CFG()->isEncrypt;
		$tpl->isCheckTpl=!CFG()->isServiceMode;
		$tpl->setVar('version',$this->version());
		$tpl->setVar('charset',CFG()->charset);
	}

	function &version(){
		return array(
			'name'=>'ZBCore',
			'number'=>'1.0.0',
			'release'=>'20110118',
			'describe'=>'ZBCore是一个简易PHP框架。',
			'author'=>'abao',
			'copyright'=>'ZBCore',
			'link'=>'http://www.zbcore.com/'
		);
	}

	//判断提交是否正确
	function is_submit($key){
		if(!empty($_POST[$key.'submit']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
			if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST[$key.'hash'] == $this->formhash($key)){
				return true;
			} else {
				$this->message('您的请求来路不正确或表单验证串不符，无法提交。请尝试使用标准的web浏览器进行操作。');
			}
		} else {
			return false;
		}
	}

	function formhash($key){
		static $formhashs;
		if(!$formhashs)
			$formhashs=array();
		if(empty($formhashs[$key])){
			$formhashs[$key]=md5(substr(TIMESTAMP,0,-7).'|'.M('setup')->get('config','sitekey').'|'.$key);
			$this->setVar($key.'hash',$formhashs[$key]);
		}
		return $formhashs[$key];
	}

	function setVar($var=null,$data=null){
		L('template')->setVar($var,$data);
	}

	function setCall($var=null,$data=null){
		L('template')->setCall($var,$data);
	}

	function display($tpl,$cache='',$html=false){
		$this->isAutoEchoPage=true;
		L('template')->display($tpl,$cache,$html);
	}

	function message($message,$backurl='',$status=false,$timeout=3){
		$this->isCachePage=false;
		$timeout*=1000;
		$this->obclean();
		if(IN_AJAX){
			if(is_array($message)){
				if(!isset($message['status'])){
					$message['status']=$status;
				}
				settype($message['status'],'boolean');
				if(!isset($message['backurl'])){
					$message['backurl']=$backurl;
				}
			}else{
				$message=array(
					'message'=>$message,
					'backurl'=>$backurl,
					'status'=>$status,
					'timeout'=>$timeout,
				);
			}
			$message=(function_exists('json_encode')?json_encode($message):L('json')->encode($message));
			$content='<'.'?xml version="1.0" encoding="'.CFG()->charset.'"?>';
			$content.='<message><![CDATA['.str_replace(']]>',']]&gt;',$message).']]></message>';
			$this->echoPage(array('Content-type'=>'text/xml; charset='.CFG()->charset),$content);
		}else{
			$this->setCall();
			is_array($message) && extract($message);
			$this->setVar('head',array('title'=>$succes?'成功提醒':'失败提醒'));
			$this->setVar('status',$status);
			$this->setVar('message',$message);
			$this->setVar('backurl',$backurl);
			$this->setVar('timeout',$timeout);
			$this->display('message');
			$this->obEchoPage();
		}
	}
	private function mkJson($json,$grade){
		if(is_array($json) && ($grade==-1 || $grade>0)){
			$return=array();
			foreach($json as $k=>$v)
				$return['k'.$k]=$this->mkJson($v,$grade>0?$grade-1:$grade);
			return $return;
		}
		return $json;
	}
	function echoKJson($json,$grade=-1){
		$this->echoJson($this->mkJson($json,$grade));
	}
	function echoJson($json){
		$this->obclean();
		$this->echoPage(array('Content-type'=>'application/json; charset='.CFG()->charset),function_exists('json_encode')?json_encode($json):L('json')->encode($json));
	}
	function obEchoPage(){
		$content=trim(ob_get_contents());
		$this->obclean();
		if(IN_AJAX){
			$content=str_replace(']]>',']]&gt;',$content);
			$content='<'.'?xml version="1.0" encoding="'.CFG()->charset.'"?><root><![CDATA['.$content.']]></root>';
			$this->echoPage(array('Content-type'=>'text/xml; charset='.CFG()->charset),$content);
		}else{
			$this->echoPage(array('Content-type'=>'text/html; charset='.CFG()->charset),$content);
		}
	}
	function echoPage($headers,$body,$isFile=false){
		$this->isEchoPaged=true;
		if($isFile){
			$mtime=filemtime($body);
			$body=L('io.file')->read($body);
		}
		$page=array(
			"header"=>$headers,
			"cache"=>$this->isCachePage,
			"body"=>$body,
			"size"=>strlen($body)
		);
		$page['header']['Etag']=md5($page['body']);
		$page['header']['Last-Modified']=gmdate( "D, d M Y H:i:s",$isFile?$mtime:TIMESTAMP)." GMT";
		if((CFG()->isGziped && !ini_get('zlib.output_compression')) && function_exists( "gzencode" ) && $page['gziped']=gzencode($page['body'],3)){
			$page['gziped-size']=strlen( $page['gziped'] );
		}

		$header_sent=headers_sent();
		header('Connection: close');
		if($page['cache']){
			header('Cache-Control: private');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		}else{
			header('Cache-Control: no-cache, no-store, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Pragma: no-cache');
		}
		if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH']==$page['header']['Etag']){
			header('Etag: '.$page['header']['Etag'],TRUE,304);
			exit(0);
		}
		foreach($page['header'] as $k=>$v){
			header($k.': '.$v);
		}
		if($page['gziped'] && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && !$header_sent){
			if(strpos(' '.$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
				header('Content-Encoding: gzip');
				header('Content-Length: '.$page['gziped-size']);
				if(strtoupper($_SERVER['REQUEST_METHOD'])=='HEAD')
					exit(0);
				echo $page['gziped'];
			}elseif(strpos(' '.$_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip')){
				header('Content-Encoding: x-gzip');
				header('Content-Length: '.$page['gziped-size']);
				if(strtoupper($_SERVER['REQUEST_METHOD'])=='HEAD')
					exit(0);
				echo $page['gziped'];
			}else{
				header('Content-Length: '.$page['size']);
				if(strtoupper($_SERVER['REQUEST_METHOD'])=='HEAD')
					exit(0);
				echo $page['body'];
			}
		}else{
			header('Content-Length: '.$page['size']);
			if(strtoupper($_SERVER['REQUEST_METHOD'])=='HEAD')
				exit(0);
			echo $page['body'];
		}
		exit();
	}

	function obclean(){
		ob_end_clean();
		ob_start();
	}
	function __destruct(){
		if($this->isEchoPaged==false && $this->isAutoEchoPage==true){
			$this->obEchoPage();
		}
	}
}
