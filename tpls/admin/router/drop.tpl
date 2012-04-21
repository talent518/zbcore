<form class="formtable" id="dropform" action="{link ctrl=router method=drop}" method="post">
<table cellspacing="0" cellpadding="0" border="0">
	<thead><tr><th>你确定要删除该URL路由吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">源规则</th>
					<td class="l">{$router.src|text}</td>
				</tr>
				<tr>
					<th class="r">目标规则</th>
					<td class="l">{$router.dest|text}</td>
				</tr>
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
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
