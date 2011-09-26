<h1 class="head">用户配置</h1>
<form id="userform" class="formtable" action="{link ctrl=setup method=user}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>验证邮件：</th>
				<td>
					<input name="user[verifyemail]" type="radio" value="1"{if $user.verifyemail==1} checked{/if}/>是&nbsp;
					<input name="user[verifyemail]" type="radio" value="0"{if $user.verifyemail==0} checked{/if}/>否
				</td>
			</tr>
			<tr>
				<th>默认用户组：</th>
				<td><select name="user[gid]"/></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="usersubmit" type="submit" value="提交"/><input name="userhash" type="hidden" value="$userhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#userform').validate({
	rules:{
		'user[gid]':{
			required:true,
			min:1
		}
	},messages:{
		'user[gid]':{
			min:'请选择'
		}
	}
});
$('#userform select[name="user[gid]"]').staged('{link ctrl=user method=json}',{val:0{$user.gid},isStaged:false});
</script>