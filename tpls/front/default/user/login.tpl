{template user/header}
<form id="loginForm" action="{link ctrl=user method=login}" method="post">
	<ul>
		<h1>{$version.name} {$version.number} user's Control Panel</h1>
		<li>{$version.describe}</li>
	</ul>
	<dl>
		<dd><label>用户名：</label><input name="username" type="text" value="" /></dd>
		<dd><label>密　码：</label><input name="password" type="password" value="" /></dd>
	{if M('setup')->get('verify','frontlogin')}
		<dd style="position:relative;"><label>验证码：</label><input name="verify" type="text" value="" style="width:42px;"/><img src="{link ctrl=user method=verify}" id="verifyImage" height="30" style="cursor:pointer"/></dd>
	{/if}
		<dd><input name="loginsubmit" type="submit" value="登&nbsp;录"/><input name="loginhash" type="hidden" value="$loginhash"/></dd>
	</dl>
</form>
<script type="text/javascript">
$('#loginForm input[name=username]').fblur('请输入用户名/邮箱');
$('#loginForm input[name=password]').fblur('000000');
{if IN_AJAX}
	$('#loginForm').validate();
{/if}
{if M('setup')->get('verify','frontlogin')}
$('#verifyImage').click(function(){
	$('#loginForm input[name=verify]').val('');
	var uri=$.URL2URI(this.src);
	uri.querys.timestamp={TIMESTAMP};
	uri.querys.rand=Math.floor(Math.random()*100000);
	this.src=$.URI2URL(uri);
		setTimeout(function(){$('#verifyImage').click();},60000);
	}).click();
{/if}
</script>
{template user/footer}
