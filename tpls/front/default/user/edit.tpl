<form id="editform" class="formtable" action="{link ctrl=user method=edit}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>用户名：</th>
				<td><input name="username" type="text" value="{$MEMBER.username|html}" size="30" /></td>
			</tr>
			<tr>
				<th>旧密码：</th>
				<td><input name="oldpassword" type="password" value="" size="20" /></td>
			</tr>
			<tr>
				<th>新密码：</th>
				<td><input name="password" type="password" value="" size="20" /></td>
			</tr>
			<tr>
				<th>确认密码：</th>
				<td><input name="confirmpassword" type="password" value="" size="20" /></td>
			</tr>
			<tr>
				<th>电子邮件：</th>
				<td><input name="email" type="text" value="{$MEMBER.email|html}" size="30" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="editsubmit" type="submit" value="提交"/><input name="edithash" type="hidden" value="$edithash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#editform').validate({
	rules:{
		username:{
			required:true,
			rangelength:[3,25],
			username:true
		},
		oldpassword:{
			minlength:6,
			password:true
		},
		password:{
			minlength:6,
			password:true
		},
		confirmpassword:{
			minlength:6,
			password:true
		},
		email:{
			required:true,
			email:true
		}
	}
});
</script>