{template header}
<form id="loginForm" action="{link method=login}" method="post">
	<ul>
		<h1>{$version.name} {$version.number} Administrator's Control Panel</h1>
		<li>{$version.describe}</li>
	</ul>
	<dl>
		<dd><label>用户名：</label><b>{$MEMBER.username}</b><input name="username" type="hidden" value="{$MEMBER.username}"/></dd>
		<dd><label>密　码：</label><input name="password" type="password" value=""/></dd>
	{if M('setup')->get('verify','adminlogin')}
		<dd><label>验证码：</label><input name="verify" type="text" value="" style="width:42px"/><img src="{link method=verify}" id="verifyImage" height="30" style="cursor:pointer"/></dd>
	{/if}
		<dd><input name="loginsubmit" type="submit" value="登&nbsp;录"/><a href="{ROOT_URL}" class="button">返回主页</a></dd>
	</dl><input name="loginhash" type="hidden" value="$loginhash"/>
</form>
<script type="text/javascript">
$('#loginForm').float('center');
$('#verifyImage').click(function(){
	var uri=$.URL2URI(this.src);
	uri.querys.rand=Math.floor(Math.random()*100000);
	this.src=$.URI2URL(uri);
});
</script>
{template footer}