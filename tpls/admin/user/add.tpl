<form id="addform" class="formtable" action="{link ctrl=user method=add}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>用户组：</th>
				<td><select name="gid" val="{$add.gid}"></select></td>
			</tr>
			<tr>
				<th>用户名：</th>
				<td><input name="username" type="text" value="{$add.username|html}" size="30" /></td>
			</tr>
			<tr>
				<th>密　码：</th>
				<td><input name="password" type="text" value="{$add.password|html}" size="20" /></td>
			</tr>
			<tr>
				<th>电子邮件：</th>
				<td><input name="email" type="text" value="{$add.email|html}" size="30" /></td>
			</tr>
			<tr>
				<th>登入后台：</th>
				<td><input name="ismanage" type="radio" value="1"/>是 <input name="ismanage" type="radio" value="0" checked/>否</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="addsubmit" type="submit" value="提交"/><input name="addhash" type="hidden" value="$addhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#addform').validate({
	rules:{
		gid:{
			required:true,
			min:1
		},
		username:{
			required:true,
			rangelength:[3,25],
			username:true
		},
		password:{
			required:true,
			minlength:6,
			password:true
		},
		email:{
			required:true,
			email:true
		}
	},messages:{
		gid:{
			min:'请选择'
		}
	}
});
$('#addform select[name=gid]').staged('{link ctrl=user method=json}');
</script>