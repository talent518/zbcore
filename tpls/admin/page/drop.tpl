<form class="formtable" id="dropform" action="{link ctrl=page method=drop}" method="post">
<table cellspacing="0" cellpadding="0" border="0">
	<thead><tr><th>你确定要删除该文章吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">短标题：</th><td class="l">{$page.title}</td>
				</tr>
				<tr>
					<th class="r">页标题：</th><td class="l">{$page.page_title}</td>
				</tr>
				<tr>
					<th class="r">页名称：</th><td class="l">{$page.page_name}</td>
				</tr>
				<tr>
					<th class="r">页内容：</th><td class="l" style="position:relative;width:360px;height:220px;overflow:hidden;"><div style="position:absolute;border:1px #d8ecff solid;width:345px;height:200px;overflow:auto;">{$page.page_content}</div></td>
				</tr>
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
				<input name="pid" type="hidden" value="{$page.pid}"/>
				<input name="dropsubmit" type="submit" value="提交"/>
				<input name="drophash" type="hidden" value="$drophash"/>
			</td>
		</tr>
	</tfoot>
</table>
</form>
<script type="text/javascript">
$('#dropform').validate();
</script>
