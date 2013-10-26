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
</head>
<body>
