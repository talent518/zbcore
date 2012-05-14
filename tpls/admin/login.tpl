{template header}
<form id="loginForm" action="{link method=login}" method="post">
	<ul>
		<h1>{ZBC_NAME} {ZBC_VERSION} Administrator's Control Panel</h1>
		<li>{ZBC_DESCRIBE}</li>
	</ul>
	<dl>
		<dd class="ipt"><label>用户名：</label><b>{$MEMBER.username}</b><input name="username" type="hidden" value="{$MEMBER.username}"/></dd>
		<dd class="ipt"><label>密　码：</label><input name="password" type="password" value=""/></dd>
	{if M('setup')->get('verify','adminlogin')}
		<dd class="ipt"><label>验证码：</label><input name="verify" type="text" value="" style="width:42px"/><img src="" id="verifyImage" height="30" style="cursor:pointer"/></dd>
	{/if}
		<dd><input name="loginsubmit" type="submit" value="登&nbsp;录"/><a href="{ROOT_URL}" class="button">返回主页</a></dd>
	</dl><input name="loginhash" type="hidden" value="$loginhash"/><input name="isRefer" type="hidden" value="{IN_AJAX}"/>
</form>
<script type="text/javascript">
$('#loginForm input[name=username]').fblur('用户名/邮箱');
$('#loginForm input[name=password]').fblur('密码');
$('#loginForm').validate();
{if !IN_AJAX}
	$(window).resize(function(){$('#loginForm').float('center')}).resize();
{/if}
{if M('setup')->get('verify','adminlogin')}
	$('#loginForm input[name=verify]').fblur('验证码');
	$('#verifyImage').click(function(){
		$('#loginForm input[name=verify]').val('');
		this.src='{link method=verify timesamp=TIMESTAMP rand=RANDOM}'.replace('TIMESTAMP',{TIMESTAMP}).replace('RANDOM',Math.random()*100000);
		setTimeout(function(){$('#verifyImage').click();},60000);
	}).click();
{/if}
</script>
{template footer}