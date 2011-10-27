{template user/header}
<form id="registerForm" class="formtable" action="{link ctrl=user method=register}" method="post">
	<table>
		<tbody>
			<tr>
				<td colspan="3">
					<h1>用户注册</h1>
				</td>
			</tr>
			<tr>
				<td rowspan="7" width="150" class="t">
					<h2>友情提示：</h2>
					<p><b>1. 基本信息；</b></p>
					<p>2. 基本资料；</p>
					<p>3. 提交网站信息；</p>
				</td>
				<th>用户名：</th>
				<td><input name="username" type="text" value="" /></td>
			</tr>
			<tr>
				<th>密　码：</th>
				<td><input name="password" type="password" value="" /></td>
			</tr>
			<tr>
				<th>确认密码：</th>
				<td><input name="confirm_password" type="password" value="" /></td>
			</tr>
			<tr>
				<th>电子邮件：</th>
				<td><input name="email" type="text" value="" /></td>
			</tr>
		{if M('setup')->get('verify','frontregister')}
			<tr>
				<th>验证码：</th>
				<td><input name="verify" type="text" value="" size="4" style="margin-right:10px;"/><img src="{link ctrl=user method=verify}" id="verifyImage" height="30" style="cursor:pointer"/><label for="verify"/></td>
			</tr>
		{/if}
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="agree" type="checkbox" value="1"/>我已阅读并同意 <a href="#">用户服务协议</a><label for="agree"/></td>
			</tr>
			<tr>
				<th class="hide">&nbsp;</th>
				<td>
					<input name="registersubmit" type="submit" value="登&nbsp;录"/>
					<input name="registerhash" type="hidden" value="$registerhash"/>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
$('#verifyImage').click(function(){
	var uri=$.URL2URI(this.src);
	uri.querys.rand=Math.floor(Math.random()*100000);
	this.src=$.URI2URL(uri);
});
$.sXML=$.XML;
$('#registerForm').validate({
	rules:{
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
		confirm_password:{
			required:true,
			equalTo:'#registerForm input[name=password]'
		},
		email:{
			required:true,
			email:true
		}{if M('setup')->get('verify','frontregister')},
		verify:{
			required:true,
			equalLength:{php echo M('setup')->get('verify','length');}
		}{/if},
		agree:{
			required:true
		}
	},
	messages:{
	}
});
</script>
{template user/footer}