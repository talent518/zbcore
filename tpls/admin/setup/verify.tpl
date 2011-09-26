<h1 class="head">验证码设置</h1>
<form id="verifyform" class="formtable" action="{link ctrl=setup method=verify}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>长度：</th>
				<td><input name="verify[length]" type="text" value="{$verify.length}" size="4" /></td>
			</tr>
			<tr>
				<th>类型：</th>
				<td>
					<input name="verify[type]" type="radio" value="0"{if $verify.type==0} checked{/if}/>默认&nbsp;
					<input name="verify[type]" type="radio" value="1"{if $verify.type==1} checked{/if}/>英文&nbsp;
					<input name="verify[type]" type="radio" value="2"{if $verify.type==2} checked{/if}/>数字&nbsp;
					<input name="verify[type]" type="radio" value="3"{if $verify.type==3} checked{/if}/>俩者&nbsp;
					<input name="verify[type]" type="radio" value="4"{if $verify.type==4} checked{/if}/>中文
				</td>
			</tr>
			<tr>
				<th>前台登录：</th>
				<td>
					<input name="verify[frontlogin]" type="radio" value="1"{if $verify.frontlogin==1} checked{/if}/>显示&nbsp;
					<input name="verify[frontlogin]" type="radio" value="0"{if $verify.frontlogin==0} checked{/if}/>隐藏
				</td>
			</tr>
			<tr>
				<th>前台注册：</th>
				<td>
					<input name="verify[frontregister]" type="radio" value="1"{if $verify.frontregister==1} checked{/if}/>显示&nbsp;
					<input name="verify[frontregister]" type="radio" value="0"{if $verify.frontregister==0} checked{/if}/>隐藏
				</td>
			</tr>
			<tr>
				<th>后台登录：</th>
				<td>
					<input name="verify[adminlogin]" type="radio" value="1"{if $verify.adminlogin==1} checked{/if}/>显示&nbsp;
					<input name="verify[adminlogin]" type="radio" value="0"{if $verify.adminlogin==0} checked{/if}/>隐藏
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="verifysubmit" type="submit" value="提交"/><input name="verifyhash" type="hidden" value="$verifyhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#verifyform').validate({
	rules:{
		'verify[length]':{
			required:true,
			digits:true,
			min:1
		}
	},messages:{
	}
});
</script>