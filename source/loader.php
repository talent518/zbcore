<?php
if(! defined('IN_ZBC'))
	exit();

set_time_limit(0);
date_default_timezone_set('PRC');

define('IS_DEBUG', TRUE);

IS_DEBUG ? error_reporting(E_ALL & ~ E_WARNING & ~ E_NOTICE) : error_reporting(0);

list($msec, $sec) = explode(' ', microtime());
define('TIMESTAMP', (float) $sec);
$sec = null;
unset($sec);
define('MICROTIME', (float) $msec);
$msec = null;
unset($msec);

define('IS_MQ_GPC', ini_get('magic_quotes_runtime'));
define('DIR_SEP', DIRECTORY_SEPARATOR);

define('SITE_URL', strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))) . '://' . $_SERVER['HTTP_HOST'] . (in_array($_SERVER['SERVER_PORT'], array(
	80,
	443
)) ? null : ':' . $_SERVER['SERVER_PORT']));
define('SITE_FULL_URL', SITE_URL . ROOT_URL);

define('SRC_DIR', dirname(__FILE__) . DIR_SEP);

define('MOD_DIR', SRC_DIR . 'models' . DIR_SEP);
define('LIB_DIR', SRC_DIR . 'libs' . DIR_SEP);
define('WID_DIR', SRC_DIR . 'widgets' . DIR_SEP);
define('PLG_DIR', SRC_DIR . 'plugins' . DIR_SEP);

define('DATA_DIR', CACHE_DIR . 'data' . DIR_SEP); // 数据缓存目录

define('TPL_CACHE_DIR', CACHE_DIR . 'tpls' . DIR_SEP); // 模板缓存目录

define('RES_FONT_DIR', RES_DIR . 'fonts' . DIR_SEP); // 字体目录

define('RES_IMAGE_DIR', RES_DIR . 'images' . DIR_SEP); // 图片目录
define('RES_IMAGE_URL', RES_URL . 'images/'); // 图片URl

define('RES_SCRIPT_DIR', RES_DIR . 'scripts' . DIR_SEP); // 脚本目录
define('RES_SCRIPT_URL', RES_URL . 'scripts/'); // 脚本URL

define('RES_UPLOAD_DIR', RES_DIR . 'uploads' . DIR_SEP); // 上传目录
define('RES_UPLOAD_URL', RES_URL . 'uploads/'); // 上传URL

define('RES_THUMB_DIR', RES_DIR . 'thumb' . DIR_SEP); // 缩略图目录
define('RES_THUMB_URL', RES_URL . 'thumb/'); // 缩略图URL

if(! file_exists(SRC_DIR . 'config.php')) {
	if(! defined('IN_INSTALL')) {
		header('Location:' . ROOT_URL . 'install.php');
		exit();
	} else {
		include_once (SRC_DIR . 'config.sample.php');
	}
} else {
	include_once (SRC_DIR . 'config.php');
}

include_once (SRC_DIR . 'zbcore.php'); // 核心对象
include_once (SRC_DIR . 'functions.php'); // 核心函數

defined('IN_MIME') or define('IN_MIME', 'text/html');

CFG()->isCookie or session_start();

if(IS_MQ_GPC) {
	$_GET = sstripslashes($_GET);
	$_POST = sstripslashes($_POST);
	$_COOKIE = sstripslashes($_COOKIE);
	$_FILES = sstripslashes($_FILES);
	$_REQUEST = sstripslashes($_REQUEST);
}

if(! defined('IN_AJAX')) {
	$IN_AJAX = GET('inAjax');
	define('IN_AJAX', ! empty($IN_AJAX));
	unset($IN_AJAX);
}

if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
	$onlineip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
	$onlineip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
	$onlineip = getenv('REMOTE_ADDR');
} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
	$onlineip = $_SERVER['REMOTE_ADDR'];
}

if(! preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $onlineip))
	$onlineip = 'unknown';

define('CLIENT_IP', $onlineip);
$onlineip = null;
unset($onlineip);

function loader() {
	defined('CTRL_DIR') or define('CTRL_DIR', SRC_DIR . 'projects' . DIR_SEP . IN_PROJ . DIR_SEP);
	is_dir(CTRL_DIR) or exit(IS_DEBUG ? 'Project <b>' . IN_PROJ . '</b> not exists.' : 0);

	$ctrl = GET('ctrl') ?: 'index';
	$method = GET('method') ?: 'index';

	define('IN_CTRL', strtolower($ctrl));
	define('IN_METHOD', strtolower($method));

	$method = 'on' . GN($method);

	ob_start();
	ob_implicit_flush(false);

	if(method_exists(C($ctrl), $method))
		C($ctrl)->$method();
	else
		exit(IS_DEBUG ? "controller <b>'$ctrl'</b> no method <b>'$method'</b>!" : 0);
}
