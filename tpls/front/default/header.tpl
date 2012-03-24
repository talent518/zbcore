{if !IN_AJAX}
	{template global_header}
	<div id="hd" class="wp">
		<a href="{ROOT_URL}" class="fl"><img src="{SKIN_URL}images/logo.gif"/></a>
	<php>
	{if $LOGINED}
		<h3 class="fr"><b>{$MEMBER.username}</b> <a href="{link ctrl=user method=index}">用户中心</a> <a href="{link ctrl=user method=logout}">退出</a></h3>
	{else}
		<h2 class="fr"><a href="{link ctrl=user method=login}">登录</a> <a href="{link ctrl=user method=register}">注册</a></h2>
	{/if}
	</php>
	</div>
	<div id="wp" class="wp">
{/if}
