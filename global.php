<?php
if(!defined('IN_PROJ'))
	exit;

set_time_limit(0);
set_magic_quotes_runtime(0);
date_default_timezone_set('PRC');

define('IS_DEBUG',TRUE);
define('IN_SITE',TRUE);

IS_DEBUG?error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE):error_reporting(0);

list($msec,$sec)=explode(' ',microtime());
define('TIMESTAMP',(float)$sec);$sec=null;unset($sec);
define('MICROTIME',(float)$msec);$msec=null;unset($msec);

define('IS_MQ_GPC',get_magic_quotes_gpc());
define('DIR_SEP',DIRECTORY_SEPARATOR);

define('ROOT_DIR',dirname(__FILE__).DIR_SEP);
define('ROOT_URL',substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/')+1));
define('SITE_URL','http://'.$_SERVER['HTTP_HOST']);
define('SITE_FULL_URL',SITE_URL.ROOT_URL);

define('SRC_DIR',ROOT_DIR.'source'.DIR_SEP);
define('PROJ_DIR',SRC_DIR.'projects'.DIR_SEP);

defined('CTRL_DIR') or define('CTRL_DIR',PROJ_DIR.IN_PROJ.DIR_SEP);
define('MOD_DIR',SRC_DIR.'models'.DIR_SEP);
define('LIB_DIR',SRC_DIR.'libs'.DIR_SEP);
define('WID_DIR',SRC_DIR.'widgets'.DIR_SEP);
define('PLG_DIR',SRC_DIR.'plugins'.DIR_SEP);

define('CACHE_DIR',ROOT_DIR.'cache'.DIR_SEP);//缓存目录
define('CACHE_URL',ROOT_URL.'cache/');//缓存目录

define('DATA_DIR',CACHE_DIR.'data'.DIR_SEP);//数据缓存目录
define('HTML_DIR',ROOT_DIR.'html'.DIR_SEP);//数据缓存目录
define('HTML_URL',ROOT_URL.'html/');//数据缓存目录

define('TPL_DIR',ROOT_DIR.'tpls'.DIR_SEP);//模板目录
define('TPL_URL',ROOT_URL.'tpls/');//模板URL
define('TPL_CACHE_DIR',CACHE_DIR.'tpls'.DIR_SEP);//模板缓存目录
define('TPL_CACHE_URL',CACHE_URL.'tpls/');//模板缓存URL

define('RES_DIR',ROOT_DIR.'resource'.DIR_SEP);//资源目录
define('RES_URL',ROOT_URL.'resource/');//资源URL

define('RES_CACHE_DIR',CACHE_DIR.'resource'.DIR_SEP);//资源缓存目录
define('RES_CACHE_URL',CACHE_URL.'resource/');//资源缓存URL

define('RES_FONT_DIR',RES_DIR.'fonts'.DIR_SEP);//字体目录

define('RES_IMAGE_DIR',RES_DIR.'images'.DIR_SEP);//图片目录
define('RES_IMAGE_URL',RES_URL.'images/');//图片URl

define('RES_SCRIPT_DIR',RES_DIR.'scripts'.DIR_SEP);//脚本目录
define('RES_SCRIPT_URL',RES_URL.'scripts/');//脚本URL

define('RES_UPLOAD_DIR',RES_DIR.'uploads'.DIR_SEP);//上传目录
define('RES_UPLOAD_URL',RES_URL.'uploads/');//上传URL

define('RES_THUMB_DIR',RES_DIR.'thumb'.DIR_SEP);//缩略图目录
define('RES_THUMB_URL',RES_URL.'thumb/');//缩略图URL

is_dir(CTRL_DIR) or exit('Project not exists.');

include_once(SRC_DIR.'config.php');
include_once(SRC_DIR.'zbcore.php');//核心对象
include_once(SRC_DIR.'functions.php');//核心函數

CFG()->isCookie or session_start();

if(!IS_MQ_GPC){
	$_GET		= saddslashes($_GET);
	$_POST		= saddslashes($_POST);
    $_COOKIE	= saddslashes($_COOKIE);
    $_FILES		= saddslashes($_FILES);
	$_REQUEST	= saddslashes($_REQUEST);
}

$inAjax=GET('inAjax');
define('INAJAX',!empty($inAjax));
unset($inAjax);

if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
	$onlineip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
	$onlineip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
	$onlineip = getenv('REMOTE_ADDR');
} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
	$onlineip = $_SERVER['REMOTE_ADDR'];
}

if(!eregi("[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}",$onlineip))
	$onlineip='unknown';

define('CLIENT_IP',$onlineip);
$onlineip=null;unset($onlineip);

defined('AUTORUN') && autorun();
