{if !IN_AJAX}
	{template global_header}
	<div id="hd" class="wp">
		<a href="{ROOT_URL}" class="fl"><img src="{SKIN_URL}images/logo.gif"/></a>
	<php>
		<h3 class="fr">
		{if $LOGINED}
			<b>{$MEMBER.username}</b>&nbsp;
			<a href="{link proj=user}">用户中心</a>&nbsp;
			{if $MEMBER.ismanage}<a href="{link proj=admin}" target="_blank">管理中心</a>&nbsp;{/if}
			<a href="{link proj=user method=logout}">退出</a>
		{else}
			<a href="{link proj=user method=login}">登录</a>&nbsp;<a href="{link proj=user method=register}">注册</a>
		{/if}
		</h3>
	</php>
	</div>
	<div id="wp" class="wp">
{/if}
