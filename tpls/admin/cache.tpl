<h1 class="head">更新缓存</h1>
<form id="cacheform" class="formtable" action="{link method=cache}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>数据缓存：</th>
				<td><input name="data" type="checkbox" value="1"/></td>
			</tr>
			<tr>
				<th>模版缓存：</th>
				<td><input name="tpls" type="checkbox" value="1"/></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="cachesubmit" type="submit" value="提交"/><input name="cachehash" type="hidden" value="$cachehash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#cacheform').validate();
</script>