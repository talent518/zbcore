<?php
if(! defined('IN_ZBC'))
	exit('Access Denied');

class Config {

	// 缓存超时(单位：分钟)，推荐范围5-30
	var $timeout = 5;

	// 模版文件后缀
	var $tplSuffix = '.tpl';

	// 是否清除空白
	var $tplClearWhite = TRUE;

	// 缓存文件名是否加密
	var $isEncrypt = FALSE;

	// Cookie前缀
	var $cookiePre = 'zbcore_';

	// cookie 作用域。请设置为 .yourdomain.com 形式
	var $cookieDomain = '';

	// cookie 作用路径
	var $cookiePath = '/';

	// 页面字符集(可选 'gbk', 'big5', 'utf-8')
	var $charset = 'utf-8';

	// 启用gzip
	var $isGziped = TRUE;

	// url格式化('base','pathinfo','rewrite')
	var $urlFormat = 'base';

	// URL后缀
	var $urlSuffix = '.html';

	// 短URL后缀
	var $shortUrlSuffix = '.shtml';

	// true使用cookie记住登录，否则使用session
	var $isCookie = TRUE;

	// 静态缓存
	var $isServiceMode = FALSE;
}

class DbConfig {

	var $type = 'mysqli';

	// 数据库服务器(一般为本地localhost)
	var $host = 'localhost';

	// 数据库用户名
	var $user = 'root';

	// 数据库密码
	var $pwd = '123456';

	// 数据库名
	var $name = 'zbcore';

	// 表名前缀(不能与论坛的表名前缀相同)
	var $tablepre = 'zbc_';

	// 数据库持久连接 0=关闭, 1=打开
	var $pconnect = FALSE;

	// 数据库字符集
	var $charset = 'utf8';
}
