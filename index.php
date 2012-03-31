<?php

define('IN_ZBC',TRUE);

define('ROOT_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR);
define('ROOT_URL',substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/')+1));

define('TPL_DIR',ROOT_DIR.'tpls'.DIRECTORY_SEPARATOR);//模板目录
define('TPL_URL',ROOT_URL.'tpls/');//模板URL

define('CACHE_DIR',ROOT_DIR.'cache'.DIRECTORY_SEPARATOR);//缓存目录

define('RES_DIR',ROOT_DIR.'resource'.DIRECTORY_SEPARATOR);//资源目录
define('RES_URL',ROOT_URL.'resource/');//资源URL

include_once(ROOT_DIR.'source/loader.php');

define('IN_PROJ',strtolower(GET('proj')!='' && GET('proj')!='index'?GET('proj'):'front'));

loader();