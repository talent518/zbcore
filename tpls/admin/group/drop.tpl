<form class="formtable" id="dropform" action="{link ctrl=group method=drop}" method="post">
<table cellspacing="0" cellpadding="0" border="0">
{if $catpos}
	<caption><b>当前位置：</b><a href="{link ctrl=group}" class="list">用户组管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=group method=list id=$r.gid}" class="list">{$r.cname}</a>{/loop}</caption>
{/if}
	<thead><tr><th>你确定要删除该用户组吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">用户组名称：</th><td class="l">{$group.gname|html}</td>
				</tr>
				<tr>
					<th class="r">备注：</th><td class="l">{$group.remark|html}</td>
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
