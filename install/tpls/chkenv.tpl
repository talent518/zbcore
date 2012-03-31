{template header}

<div class="step">
	<div class="stepnum step1">
		<h2>开始安装</h2>
		<p>环境以及文件目录权限检查</p>
	</div>

	<div class="stepstat">
		<ul>
			<li class="current">1</li>
			<li class="unactivated">2</li>
			<li class="unactivated">3</li>
			<li class="unactivated last">4</li>
		</ul>
		<div class="stepstatbg stepstat1"></div>
	</div>
</div>
<div class="formtable">
	<table>
		<caption>环境检查</caption>
		<tbody>
			<tr>
				<th>项目</th>
				<th>所需配置</th>
				<th>最佳配置</th>
				<th>当前配置</th>
			</tr>
			<tr>
				<td>操作系统</td>
				<td>不限</td>
				<td>Unix</td>
				<td><span class="right">{PHP_OS}</span></td>
			</tr>
			<tr>
				<td>php版本</td>
				<td>4.3以上</td>
				<td>5.0以上</td>
				<td><span class="right">{PHP_VERSION}</span></td>
			</tr>
			<tr>
				<td>mysql扩展</td>
				<td>4.0以上</td>
				<td>5.0以上</td>
				<td><span class="{if extension_loaded('mysql')}right{elseif extension_loaded('mysqli')}without{else}wrong{/if}">{php echo function_exists('mysql_get_client_info')?mysql_get_client_info():'无';}</span></td>
			</tr>
			<tr>
				<td>mysqli扩展</td>
				<td>4.0以上</td>
				<td>5.0以上</td>
				<td><span class="{if extension_loaded('mysqli')}right{elseif extension_loaded('mysql')}without{else}wrong{/if}">{php echo function_exists('mysqli_get_client_info')?mysqli_get_client_info():'无';}</span></td>
			</tr>
			<tr>
				<td>GD扩展</td>
				<td>1.0</td>
				<td>2.0</td>
				<td><span class="{if extension_loaded('gd')}right{else}wrong{/if}">{php $gdinfo=gd_info();echo $gdinfo?$gdinfo['GD Version']:'无'; }</span></td>
			</tr>
			<tr>
				<td>mbstring扩展</td>
				<td>不限</td>
				<td><span class="right">开启</span></td>
			{if extension_loaded('mbstring')}
				<td><span class="right">开启</span></td>
			{else}
				<td><span class="wrong">关闭</span></td>
			{/if}
			</tr>
			<tr>
				<td>iconv扩展</td>
				<td>不限</td>
				<td><span class="right">开启</span></td>
			{if extension_loaded('iconv')}
				<td><span class="right">开启</span></td>
			{else}
				<td><span class="wrong">关闭</span></td>
			{/if}
			</tr>
			<tr>
				<td>附件上传</td>
				<td>不限</td>
				<td><span class="right">2M</span></td>
				<td><span class="right">{php echo @ini_get('file_uploads')?ini_get('upload_max_filesize'):'不限';}</span></td>
			</tr>
		</tbody>
	</table>
	<table>
		<caption>目录、文件权限检查</caption>
		<tbody>
			<tr>
				<th>目录文件</th>
				<th>所需状态</th>
				<th>当前状态</th>
			</tr>
			<tr>
				<td>/cache/</td>
				<td><span class="right">可写</span></td>
			{if L('io.dir')->writeable(CACHE_DIR)}
				<td><span class="right">可写</span></td>
			{elseif is_dir(CACHE_DIR)}
				<td><span class="wrong">只读</span></td>
			{else}
				<td><span class="wrong">无权限</span></td>
			{/if}
			</tr>
			<tr>
				<td>{SRC_DIR}config.php</td>
				<td><span class="right">可写</span></td>
			{if file_exists(SRC_DIR.'config.php') && is_writeable(SRC_DIR.'config.php')}
				<td><span class="right">可写</span></td>
			{elseif file_exists(SRC_DIR.'config.php')}
				<td><span class="right">只读</span></td>
			{else}
				<td><span class="wrong">无权限</span></td>
			{/if}
			</tr>
		</tbody>
	</table>
	<center><button onclick="location.href='install.php?method=index'">上一步</button><button id="nextStep" onclick="location.href='install.php?method=cfg'">下一步</button></center>
	<script type="text/javascript">
	$('#nextStep').attr('disabled',$('.formtable span.wrong').size()>0);
	</script>
</div>
{template footer}