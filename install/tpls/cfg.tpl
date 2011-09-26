{template header}

<div class="step">
	<div class="stepnum step2">
		<h2>网站配置</h2>
		<p>数据库及网站的基本配置</p>
	</div>

	<div class="stepstat">
		<ul>
			<li>1</li>
			<li class="current">2 </li>
			<li class="unactivated">3 </li>
			<li class="unactivated last">4 </li>
		</ul>
		<div class="stepstatbg stepstat2"></div>
	</div>
</div>
<form id="cfgForm" class="formtable" action="{link method=cfg}" method="post">
	<table class="base">
		<caption>数据库配置</caption>
		<tbody>
			<tr>
				<th>连接类型：</th>
				<td>
					<select name="db[type]">
						<option value="">请选择</option>
						<option value="mysql">mysql</option>
						<option value="mysqli"} selected{/if}>mysqli</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>服务器：</th>
				<td><input name="db[host]" type="text" value="localhost"/><label for="db[host]" class="ungenerated">数据库服务器地址，一般为 localhost</label></td>
			</tr>
			<tr>
				<th>持久连接：</th>
				<td><input name="db[pconnect]" type="radio" value="TRUE" checked/>是<input name="db[pconnect]" type="radio" value="FALSE" checked/>否</td>
			</tr>
			<tr>
				<th>用户名：</th>
				<td><input name="db[user]" type="text" value="zbcms"/></td>
			</tr>
			<tr>
				<th>密　码：</th>
				<td><input name="db[pwd]" type="password" value=""/></td>
			</tr>
			<tr>
				<th>数据库名：</th>
				<td><input name="db[name]" type="text" value="zbcms"/></td>
			</tr>
			<tr>
				<th>表前缀：</th>
				<td><input name="db[tablepre]" type="text" value="zbc_"/><label for="db[tablepre]" class="ungenerated">同一数据库运行多个ABCMS时，请修改前缀，否则会替换</label></td>
			</tr>
		</tbody>
	</table>
	<table class="base">
		<caption>基本配置</caption>
		<tbody>
			<tr>
				<th>缓存时间：</th>
				<td><input name="cfg[timeout]" type="text" value="5" size="4"/></td>
			</tr>
			<tr>
				<th>清除空白字符：</th>
				<td><input name="cfg[tplClearWhite]" type="radio" value="TRUE" checked/>是(推荐)<input name="cfg[tplClearWhite]" type="radio" value="FALSE"/>否&nbsp;<label for="cfg[tplClearWhite]"/></td>
			</tr>
			<tr>
				<th>URL格式：</th>
				<td>
					<input name="cfg[urlFormat]" type="radio" value="base" checked/>普通
					<input name="cfg[urlFormat]" type="radio" value="phpinfo"/>PHPINFO(SEO优化)
					<input name="cfg[urlFormat]" type="radio" value="rewrite"/>REWRITE(SEO优化和安全)
				</td>
			</tr>
		</tbody>
	</table>
	<table class="base">
		<caption>管理员设置(默认用户名为admin)</caption>
		<tbody>
			<tr>
				<th>EMail：</th>
				<td><input name="adm[email]" type="text" value="service@yourdomain.com" size="20"/></td>
			</tr>
			<tr>
				<th>密码：</th>
				<td><input name="adm[password]" type="password" value="" size="20"/></td>
			</tr>
			<tr>
				<th>确认密码：</th>
				<td><input name="adm[confirm_password]" type="password" value="" size="20"/></td>
			</tr>
		</tbody>
	</table>
	<center><button onclick="location.href='{link method=index}'">上一步</button><button type="submit" value="1">下一步</button></center>
	<script type="text/javascript">
	$.sXML=$.XML;
	$('#cfgForm').validate({
		rules:{
			'db[type]':{
				required:true
			},
			'db[host]':{
				required:true
			},
			'db[user]':{
				required:true
			},
			'db[pwd]':{
				required:true
			},
			'db[name]':{
				required:true
			},
			'db[tablepre]':{
				required:true
			},
			'cfg[timeout]':{
				required:true,
				number:true,
				min:5
			},
			'adm[email]':{
				required:true,
				email:true
			},
			'adm[password]':{
				required:true,
				password:true
			},
			'adm[confirm_password]':{
				required:true,
				password:true,
				equalTo:'#cfgForm input[name="adm[password]"]'
			}
		},
		messages:{
			'db[type]':{
				required:'请选择'
			}
		}
	});
	</script>
</form>
{template footer}