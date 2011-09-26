<?php
if(!defined('IN_SITE'))
	exit('Access Denied');

class Config{
	var $timeout		= 5;			//缓存超时(单位：分钟)，推荐范围5-30

	var $tplSuffix		= '.tpl';		//模版文件后缀
	var $tplClearWhite	= TRUE;			//是否清除空白

	var $isEncrypt		= FALSE;		//缓存文件名是否加密

	var $cookiePre		= 'zbcore_';	//Cookie前缀
	var $cookieDomain	= '';			//cookie 作用域。请设置为 .yourdomain.com 形式
	var $cookiePath		= '/';			//cookie 作用路径

	var $charset		= 'utf-8';		//页面字符集(可选 'gbk', 'big5', 'utf-8')
	var $isGziped 		= TRUE;			//启用gzip
	var $urlFormat		= 'rewrite';	//url格式化('base','phpinfo','rewrite','html')

	var $isCookie		= TRUE;
}

class DbConfig{
	var $type		= 'mysqli';
	var $host		= 'localhost';	//数据库服务器(一般为本地localhost)
	var $user		= 'root';		//数据库用户名
	var $pwd		= '123456';		//数据库密码
	var $name		= 'zbcore';	//数据库名
	var $tablepre	= 'zbc_';		//表名前缀(不能与论坛的表名前缀相同)
	var $pconnect	= TRUE;		//数据库持久连接 0=关闭, 1=打开
	var $charset	= 'utf8';		//数据库字符集
}
