<form class="formtable" id="dropform" action="{link ctrl=area method=drop}" method="post">
<table class="list">
{if $areapos}
	<caption><b>当前位置：</b><a href="{link ctrl=area}" class="list">地区管理</a>{loop $areapos $r}<b class="gray">&gt;</b><a href="{link ctrl=area method=list id=$r.id}" class="list">{$r.name}</a>{/loop}</caption>
{/if}
	<thead><tr><th>你确定要删除该地区及子地区吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">地区名称：</th><td class="l">{$area.name}</td>
				</tr>
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
				<input name="pid" type="hidden" value="{$area.pid}"/>
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
