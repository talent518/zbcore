{template header}
<form id="loginForm" action="{link method=login}" method="post">
	<ul>
		<h1>{ZBC_NAME} {ZBC_VERSION} user's Control Panel</h1>
		<li>{ZBC_DESCRIBE}</li>
	</ul>
	<dl>
		<dd><label>用户名：</label><input name="username" type="text" value="" /></dd>
		<dd><label>密　码：</label><input name="password" type="password" value="" /></dd>
	{if M('setup')->get('verify','frontlogin')}
		<dd style="position:relative;"><label>验证码：</label><input name="verify" type="text" value="" style="width:42px;"/><img src="" id="verifyImage" height="30" style="cursor:pointer"/></dd>
	{/if}
		<dd><input name="loginsubmit" type="submit" value="登&nbsp;录"/><input name="loginhash" type="hidden" value="$loginhash"/></dd>
	</dl><input name="isRefer" type="hidden" value="{IN_AJAX}"/>
</form>
<script type="text/javascript">
$('#loginForm input[name=username]').fblur('请输入用户名/邮箱');
$('#loginForm input[name=password]').fblur('请输入密码');
$('#loginForm').validate();
{if M('setup')->get('verify','frontlogin')}
	$('#loginForm input[name=verify]').fblur('验证码');
	$('#verifyImage').click(function(){
		$('#loginForm input[name=verify]').val('');
		this.src='{link method=verify timesamp=TIMESTAMP rand=RANDOM}'.replace('TIMESTAMP',{TIMESTAMP}).replace('RANDOM',Math.random()*100000);
		setTimeout(function(){$('#verifyImage').click();},60000);
	}).click();
{/if}
</script>
{template footer}
