<style type="text/css">
#emailform th{width:120px;}
</style>
<h1 class="head">邮件设置</h1>
<form id="emailform" class="formtable" action="{link ctrl=setup method=email}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>发送方式：</th>
				<td>
					<input class="radio" name="email[type]" value="sendmail"{if $email.type == 'sendmail'} checked{/if} onclick="$('.opt1,.opt2','#emailform').hide();" type="radio"> 通过 unix 系统的 sendmail 发送(仅 unix系统有效)<br>
					<input class="radio" name="email[type]" value="smtp"{if $email.type == 'smtp'} checked{/if} onclick="$('.opt1,.opt2','#emailform').show();" type="radio"> 通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)<br>
					<input class="radio" name="email[type]" value="mail"{if $email.type == 'mail'} checked{/if} onclick="$('#emailform .opt1').show();$('#emailform .opt2').hide();" type="radio"> 通过 PHP 函数 SMTP 发送 Email(仅 Windows 主机下有效, 不支持 ESMTP 验证)
				</td>
			</tr>
			<tr class="opt1">
				<th>smtp服务器：</th>
				<td><input name="email[host]" type="text" value="{$email.host}" size="30" /></td>
			</tr>
			<tr class="opt1">
				<th>smtp端口：</th>
				<td><input name="email[port]" type="text" value="{$email.port}" size="30" /></td>
			</tr>
			<tr class="opt2">
				<th>安全连接：</th>
				<td><input class="radio" name="email[secure]" value="ssl" type="radio"{if $email.secure == 'ssl'} checked{/if}/> SSL &nbsp; &nbsp;
				<input class="radio" name="email[secure]" value="tls" type="radio"{if $email.secure == 'tls'} checked{/if}/> TLS &nbsp; &nbsp;
				<input class="radio" name="email[secure]" value="" type="radio"{if $email.secure == ''} checked{/if}/> 默认</td>
			</tr>
			<tr class="opt2">
				<th>要求身份验证：</th>
				<td><input class="radio" name="email[auth]" value="1"{if $email.auth == 1} checked{/if} type="radio"> 是 &nbsp; &nbsp;
				<input class="radio" name="email[auth]" value="0"{if $email.auth != '1'} checked{/if} type="radio"> 否</td>
			</tr>
			<tr class="opt2">
				<th>发信人邮件地址：</th>
				<td><input type="text" name="email[from]" value="{$email.from}" /></td>
			</tr>
			<tr class="opt2">
				<th>发信人名称：</th>
				<td><input type="text" name="email[fromname]" value="{$email.fromname}" /></td>
			</tr>
			<tr class="opt2">
				<th>Email帐号：</th>
				<td><input name="email[username]" type="text" value="{$email.username}" size="30" /></td>
			</tr>
			<tr class="opt2">
				<th>Email密码：</th>
				<td><input name="email[password]" type="password" value="{$email.password}" size="30" /></td>
			</tr>
			<tr class="opt2">
				<th>答复邮件地址：</th>
				<td><input type="text" name="email[reply]" value="{$email.reply}" /></td>
			</tr>
			<tr class="opt2">
				<th>答复人名称：</th>
				<td><input type="text" name="email[replyname]" value="{$email.replyname}" /></td>
			</tr>
			<tr>
				<th>Email设置测试：</th>
				<td><input id="testEmailText" type="text" value="" size="30" /><input id="testEmailBtn" type="button" value="发送Email" style="margin-left:10px;"/></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="emailsubmit" type="submit" value="提交"/><input name="emailhash" type="hidden" value="$emailhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#emailform input[name="email[type]"]:checked').click();
$('#emailform').validate({
	rules:{
		'email[port]':{
			digits:true
		},
		'email[from]':{
			email:true
		},
		'email[replay]':{
			email:true
		}
	},messages:{
	}
});
$('#testEmailBtn').click(function(){
	if($('#testEmailText').val().length>0)
		$.post('{link ctrl=setup method=test.email}',{email:$('#testEmailText').val(),testEmailsubmit:1,testEmailhash:'$testEmailhash'});
	else{
		$('#testEmailText').focus();
		alert('Email地址不能为空');
	}
});
</script>