<?php
define('IN_PROJ','install');
define('CTRL_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR);

define('IN_ZBC',TRUE);

define('ROOT_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR);
define('ROOT_URL',substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/')+1));

define('TPL_DIR',ROOT_DIR.'tpls'.DIRECTORY_SEPARATOR);//ģ��Ŀ¼
define('TPL_URL',ROOT_URL.'tpls/');//ģ��URL

define('CACHE_DIR',ROOT_DIR.'cache'.DIRECTORY_SEPARATOR);//����Ŀ¼

define('RES_DIR',ROOT_DIR.'resource'.DIRECTORY_SEPARATOR);//��ԴĿ¼
define('RES_URL',ROOT_URL.'resource/');//��ԴURL

include_once(ROOT_DIR.'source/loader.php');

CFG()->urlFormat='base';

loader();
