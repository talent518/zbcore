<?php if(!defined("IN_ZBC")) exit("Access Denied");if(!$this->checkSubTpl('db|header|footer',1337920260,false)){ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title><?=ZBC_NAME?> V<?=ZBC_VERSION?> (<?=ZBC_RELEASE?>)</title><meta name="keywords" content="<?=ZBC_NAME?>" /><meta name="description" content="<?=ZBC_DESCRIBE?>" /><meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" /><link rel="stylesheet" type="text/css" href="<?=SKIN_URL?>style.css" /><link rel="shortcut icon" href="<?=ROOT_URL?>favicon.ico"/><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery.js"></script><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery-global.js"></script><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery-float.js"></script><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery-ui_core.js"></script><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery-ui_draggable.js"></script><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery-dialog.js"></script><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery-validate.js"></script><script type="text/javascript" src="<?=RES_SCRIPT_URL?>jquery-form.js"></script></head><body><div id="wpl"><div id="hd" class="wp"><p>V<?=ZBC_VERSION?> 简体中文UTF8版 <?=ZBC_RELEASE?></p></div><div id="wp" class="wp"><div class="step"><div class="stepnum step3"><h2>安装数据库</h2><p>正在执行数据库安装</p></div><div class="stepstat"><ul><li>1</li><li>2 </li><li class="current">3 </li><li class="unactivated last">4 </li></ul><div class="stepstatbg stepstat3"></div></div></div><iframe name="license" class="license" width="100%" height="300" scrolling="yes" frameborder="0" style="padding:0px;overflow:auto;" src="install.php?method=db&adm[email]=<?=$adm['email']?>&adm[password]=<?=$adm['password']?>&begin=1"></iframe><center><button onclick="location.href='install.php?method=cfg'">上一步</button><button onclick="location.href='install.php?method=finish'" id="nextStep" disabled>下一步</button></center><script type="text/javascript">window.down_move_func=function(){license.document.body.scrollTop=license.document.body.scrollHeight-$('iframe.license').height();setTimeout(window.down_move_func,100);};down_move_func();</script></div><div id="ft" class="wp"><p>©2001 - 2010 <a href="<?=ZBC_SITE?>" target="_blank"><?=ZBC_AUTHOR?></a> Inc.</p></div></div></body></html><?php } ?>