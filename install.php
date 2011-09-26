<?php
define('IN_PROJ','install');
define('CTRL_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR);

include_once('./global.php');
CFG()->urlFormat='base';
autorun();
