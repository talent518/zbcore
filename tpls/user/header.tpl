{if !IN_AJAX}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{if $head.title}{$head.title} - {/if}Powered by {ZBC_NAME}!</title>
	<meta name="keywords" content="{if $head.keywords}{$head.keywords}{else}{$config.sitekeywords}{/if}" />
	<meta name="description" content="{if $head.description}{$head.description}{else}{$config.sitedesription}{/if}" />
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
	<meta http-equiv="content-language" content="zh-CN"/>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<link rel="stylesheet" type="text/css" href="{SKIN_URL}user.css" />
	<link rel="shortcut icon" href="favicon.ico"/>
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="{SKIN_URL}ie.css" />
	<![endif]-->
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-cookie.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-md5.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-float.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-ui_core.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-ui_draggable.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-dialog.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-validate.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-datepick.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-datepick_zh_CN.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-form.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-staged.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-global.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-window.js"></script>
</head>
<body>
<div id="wpl">
	<div id="hd" class="wp">
		<p>
			<php>
			{if $LOGINED}
				<b>{$MEMBER.username}</b><span class="split">|</span>
				<a href="{link method=index}">用户中心</a><span class="split">|</span>
				{if $MEMBER[ismanage]}
					<a href="{link proj=admin}" target="_blank">管理中心</a><span class="split">|</span>
				{/if}
				<a href="{link method=logout}">退出</a>
			{else}
				<a href="{link method=login}">登录</a> | <a href="{link method=register}">注册</a>
			{/if}
			</php>
			<span class="split">|</span><a href="{ROOT_URL}" class="gray">返回首页</a>
		</p>
	</div>
	<div id="wp" class="wp">
{/if}