<form class="formtable" id="dropform" action="{link ctrl=category method=drop}" method="post">
<table class="list">
{if $catpos}
	<caption><b>当前位置：</b><a href="{link ctrl=category}" class="list">栏目管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=category method=list id=$r.cat_id}" class="list">{$r.cat_name}</a>{/loop}</caption>
{/if}
	<thead><tr><th>你确定要删除该栏目及栏目下的网址吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">栏目名称：</th><td class="l">{$cat.cat_name}</td>
				</tr>
				<tr>
					<th class="r">网址数：</th><td class="l"><b class="numeric">{$cat.counts}</b></td>
				</tr>
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
				<input name="pid" type="hidden" value="{$cat.pid}"/>
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
