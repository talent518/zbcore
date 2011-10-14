<form id="editform" class="formtable" action="{link ctrl=user method=edit id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
		{if $edit.uid>1}
			<tr>
				<th>用户组：</th>
				<td><select name="gid" val="{$edit.gid}"></select></td>
			</tr>
		{/if}
			<tr>
				<th>用户名：</th>
				<td><input name="username" type="text" value="{$edit.username|html}" size="30" /></td>
			</tr>
			<tr>
				<th>密　码：</th>
				<td><input name="password" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<th>电子邮件：</th>
				<td><input name="email" type="text" value="{$edit.email|html}" size="30" /></td>
			</tr>
		{if $edit.uid>1}
			<tr>
				<th>登入后台：</th>
				<td><input name="ismanage" type="radio" value="1"{if $edit.ismanage==1} checked{/if}/>是 <input name="ismanage" type="radio" value="0"{if $edit.ismanage==0} checked{/if}/>否</td>
			</tr>
			<tr>
				<th>受保护：</th>
				<td><input name="protected" type="radio" value="1"{if $edit.protected} checked{/if}/>是&nbsp;<input name="protected" type="radio" value="0"{if !$edit.protected} checked{/if}/>否</td>
			</tr>
		{/if}
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
$('#editform select[name=gid]').staged('{link ctrl=user method=json}');
</script>