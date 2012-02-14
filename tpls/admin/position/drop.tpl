<form class="formtable" id="dropform" action="{link ctrl=position method=drop}" method="post">
<table class="list">
	<thead><tr><th>你确定要删除该栏目及栏目下的网址吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">栏目名称：</th><td class="l">{$pos.pname}</td>
				</tr>
				<tr>
					<th class="r">网址数：</th><td class="l"><b class="numeric">{$pos.counts}</b></td>
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
