<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{if $head.title}{$head.title} - {/if}{$config.sitetitle}</title>
	<meta name="keywords" content="{if $head.keywords}{$head.keywords}{else}{$config.sitekeywords}{/if}" />
	<meta name="description" content="{if $head.description}{$head.description}{else}{$config.sitedesription}{/if}" />
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
	<meta http-equiv="content-language" content="zh-CN"/>
	<meta name="google-site-verification" content="pKvlldIyRWGBkDzmGlp8VaerW0AV-7WEU8WtdvD1S_0" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<link rel="stylesheet" type="text/css" href="{SKIN_URL}{if $head.style}{$head.style}{else}style{/if}.css" />
	<link rel="shortcut icon" href="{ROOT_URL}favicon.ico"/>
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="{SKIN_URL}ie.css" />
	<![endif]-->
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-cookie.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-global.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-md5.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-float.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-ui_core.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-ui_draggable.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-window.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-dialog.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-validate.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-datepick.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-datepick_zh_CN.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-form.js"></script>
	<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-staged.js"></script>
</head>
<body>
