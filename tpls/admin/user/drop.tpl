<form class="formtable" id="dropform" action="{link ctrl=user method=drop}" method="post">
<table cellspacing="0" cellpadding="0" border="0">
	<thead><tr><th>你确定要删除该用户吗？</th></tr></thead>
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
