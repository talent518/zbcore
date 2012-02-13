<?php
if(!defined('IN_SITE'))
	exit('Access Denied');

function __autoload($class){
	$cls=explode('.',strtolower(substr(preg_replace("/([A-Z])/",".\\1",$class),1)));
	$type=array_shift($cls);
	$dir=implode(DIR_SEP,$cls);
	switch($type){
		case 'ctrl':
			include_once(CTRL_DIR.$dir.'.php');
			break;
		case 'model':
			include_once(MOD_DIR.$dir.'.php');
			break;
		case 'lib':
			include_once(LIB_DIR.$dir.'.php');
			break;
	}
	if(!class_exists($class,false)){
		trigger_error("Unable to load class: $class", E_USER_WARNING);
	}
}

function GD($dir){
	return str_replace('.',DIR_SEP,strtolower($dir));
}
function GN($dir){
	$dirs=array();
	foreach(explode('.',strtolower($dir)) as $dir)
		$dirs[]=ucfirst($dir);
	return implode('',$dirs);
}

function &M($model){
	static $models;
	if(!$models){
		@include_once(MOD_DIR.'base.php');
		$models=array();
	}
	if(!$models[$model]){
		@include_once(MOD_DIR.GD($model).'.php');
		$class='Model'.GN($model);
		if(class_exists($class,false)){
			$models[$model]=new $class();
		}else{
			exit('class <b>'.$class.'</b> not exists!');
		}
	}
	return $models[$model];
}
function &C($ctrl){
	static $ctrls;
	if(!$ctrls){
		@include(CTRL_DIR.'base.php');
		$ctrls=array();
	}
	if(!$ctrls[$ctrl]){
		@include_once(CTRL_DIR.GD($ctrl).'.php');
		$_ctrl='Ctrl'.GN($ctrl);
		if(class_exists($_ctrl,false)){
			$ctrls[$ctrl]=new $_ctrl();
		}else{
			exit('class <b>'.$_ctrl.'</b> not exists!');
		}
	}
	return $ctrls[$ctrl];
}
function &L($lib){
	static $libs;
	if(!$libs){
		//@include(LIB_DIR.'base.php');
		$libs=array();
	}
	if(!$libs[$lib]){
		@include(LIB_DIR.GD($lib).'.php');
		$class='Lib'.GN($lib);
		if(class_exists($class,false)){
			$libs[$lib]=new $class();
		}else{
			exit('class <b>'.$class.'</b> not exists!');
		}
	}
	return $libs[$lib];
}
function &W($wid){
	static $wids;
	if(!$wids){
		@include(WID_DIR.'base.php');
		$wids=array();
	}
	if(!$wids[$wid]){
		@include(WID_DIR.GD($wids).'.php');
		$class='Widgets'.GN($wids);
		if(class_exists($class,false)){
			$wids[$wid]=new $class();
		}else{
			exit('class <b>'.$class.'</b> not exists!');
		}
	}
	return $wids[$wid];
}

function &DB(){
	static $db;
	if(!$db){
		$cfg=new DbConfig;
		if(IN_PROJ!='install' && file_exists(ROOT_DIR.'install.php') && !extension_loaded($cfg->type)){
			header('Location:'.ROOT_URL.'install.php');
			exit;
		}
		$db=L('db.'.$cfg->type);
		$db->charset=$cfg->charset;
		$db->host=$cfg->host;
		$db->user=$cfg->user;
		$db->pwd=$cfg->pwd;
		$db->name=$cfg->name;
		$db->pconnect=$cfg->pconnect;
		$db->tablepre=$cfg->tablepre;
		if(IN_PROJ!='install' && file_exists(ROOT_DIR.'install.php') && !($db->connect(true) && $db->sdb($cfg->name))){
			header('Location:'.ROOT_URL.'install.php');
			exit;
		}else{
			$db->connect();
		}
	}
	return $db;
}

function &CFG(){
	static $config;
	if(!$config){
		$config=new Config;
	}
	return $config;
}

function autorun(){
	$ctrl=GET('ctrl');
	$ctrl=empty($ctrl)?'index':$ctrl;

	$method=GET('method');
	$method=empty($method)?'Index':GN($method);
	$method='on'.$method;

	ob_start();

	define('IN_CTRL',$ctrl);
	define('IN_METHOD',$method);

	if(method_exists(C($ctrl),$method))
		C($ctrl)->$method();
	else
		exit("controller <b>'$ctrl'</b> no method <b>'$method'</b>!");

	exit;
}

function GET($key){
	return L('url.'.CFG()->urlFormat)->get($key);
}

function URL($args=array()){
	return L('url.'.CFG()->urlFormat)->link($args);
}

function saddslashes($string) {
	if(is_array($string)){
		foreach($string as $key=>$val){
			$string[$key]=saddslashes($val);
		}
	}else{
		$string=addslashes($string);
	}
	return $string;
}

//去掉slassh
function sstripslashes($string){
	if(is_array($string)){
		foreach($string as $key=>$val){
			$string[$key]=sstripslashes($val);
		}
	}else{
		$string=stripslashes($string);
	}
	return $string;
}

//连接整数
function iimplode(&$ints){
	foreach($ints as $k=>$v)
		$ints[$k]=intval($v);
	return implode(',',$ints);
}

//连接字符
function simplode(&$strs) {
	return "'".implode("','", $strs)."'";
}

//计算两个时间之差的函数(年,月,周,日,小时,分钟,秒数) 
function timediff($aTime,$bTime){
	$td=array();
	$td['second']=$aTime-$bTime;
	$td['mintue']=round($td ['second']/60);
	$td['hour']=round($td ['mintue']/60);
	$td['day']=round($td ['hour']/24);
	$td['week']=round($td ['day']/7);
	$td['month']=round($td ['day']/30);
	$td['year']=round($td ['day']/365);
	return $td;
}

//最小时间之差
function lowtimediff($aTime,$bTime){
	$td=timediff($aTime,$bTime);
	if($td['year']>0){
		return $td['year'].'天';
	}elseif($td['month']>0){
		return $td['month'].'月';
	}elseif($td['week']>0){
		return $td['week'].'周';
	}elseif($td['day']>0){
		return $td['day'].'天';
	}elseif($td['hour']>0){
		return $td['hour'].'小时';
	}elseif($td['mintue']>0){
		return $td['mintue'].'分钟';
	}elseif($td['second']>0){
		return $td['second'].'秒';
	}
}

//时间格式化
function sgmdate($dateformat, $timestamp='', $format=0){
	global $_SC, $_SGLOBAL;
	if(empty($timestamp)){
		$timestamp=TIMESTAMP;
	}
	$timeoffset=strlen(M('user')->MEMBER['timeoffset'])>0?intval(M('user')->MEMBER['timeoffset']):intval(M('setup')->get('config','timeoffset'));
	$result='';
	if($format){
		$time=TIMESTAMP - $timestamp;
		if($time > 24*3600){
			$result=gmdate($dateformat, $timestamp + $timeoffset * 3600);
		} elseif ($time > 3600){
			$result=intval($time/3600).'小时前';
		} elseif ($time > 60){
			$result=intval($time/60).'分钟前';
		} elseif ($time > 0){
			$result=$time.'秒前';
		} else {
			$result='现在';
		}
	} else {
		$result=gmdate($dateformat, $timestamp + $timeoffset * 3600);
	}
	return $result;
}

//字符截取...
function strcut($string,$length,$suffix='…'){
	return L('string')->cut($string,0,$length,CFG()->charset,$suffix);
}

//显示进程处理时间
function echodebug(){
	if(IS_DEBUG || M('setup')->get('config','isshowdebug')){
		$stime=bcadd(TIMESTAMP,MICROTIME,8);
		$etime=smicrotime();
		$totaltime=bcmul(bcsub($etime,$stime,8),1000,3);
		echo '<p class="base">Processed in '.$totaltime.' second(ms), '.count(DB()->querys).' queries'.(CFG()->isGziped?', Gzip enabled':NULL).', peak of memory '.formatsize(memory_get_peak_usage()).', amount of memory '.formatsize(memory_get_usage()).'</p>';
	}
	if(M('setup')->get('config','isshowdebug') && M('user')->MEMBER['ismanage']){
		@include_once(SRC_DIR.'debug.php');
	}
}

//取消HTML代码
function shtmlspecialchars($string){
	if(is_array($string)){
		foreach($string as $key => $val){
			$string[$key]=shtmlspecialchars($val);
		}
	} else {
		$string=preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

//格式化大小函数
function formatsize($size){
	$size=round(abs($size));
	$units=array(0=>'B',1=>'KB',2=>'MB',3=>'GB',4=>'TB');
	$unit=($size<1024?0:min(4,floor(log($size,1024))));
	$size=($unit==0?$size:round($size/pow(1024,$unit),3));
	return $size.$units[$unit];
}

function smicrotime(){
    list($usec,$sec)=explode(' ',microtime());
    return bcadd($usec,$sec,8);
}

//加密字符串
function encodestr($str){
	return L('crypt')->encode($str,M('setup')->get('config','sitekey'));
}

//解密字符串
function decodestr($str){
	return L('crypt')->decode($str,M('setup')->get('config','sitekey'));
}

//生成缩略图
function thumb($image,$maxWidth=100,$maxHeight=100,$center=true,$bgcolor=0xFFFFFF){
	// 获取原图信息
	if($image=realpath($image)){
		$_image=substr($image,strlen(RES_DIR));
		$thumbfile=substr($_image,0,strrpos($_image,'.')).'_'.$maxWidth.'x'.$maxHeight.($center?'_'.$bgcolor:'').'.'.L('io.file')->ext($image);
		$thumburl=RES_THUMB_URL.(DIR_SEP=='/'?$thumbfile:str_replace('\\','/',$thumbfile));
		$path=dirname(RES_THUMB_DIR.$thumbfile);
		is_dir($path) or mkdir($path,777,true);
		$_image=$info=$path=null;
		unset($_image,$info,$path);
		if(file_exists(RES_THUMB_DIR.$thumbfile))
			return $thumburl;
		L('image.thumb')->open($image);
		L('image.thumb')->save(RES_THUMB_DIR.$thumbfile,$maxWidth,$maxHeight,$center,$bgcolor);
		L('image.thumb')->close();
		return $thumburl;
	 }
	 return false;
}
