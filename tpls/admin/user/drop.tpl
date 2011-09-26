<form class="formtable" id="dropform" action="{link ctrl=user method=drop}" method="post">
<table class="list">
{if $catpos}
	<caption><b>当前位置：</b><a href="{link ctrl=user}" class="list">用户管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=user method=list id=$r.cid}" class="list">{$r.cname}</a>{/loop}</caption>
{/if}
	<thead><tr><th>你确定要删除该用户吗？</th></tr></thead>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
				<input name="cid" type="hidden" value="{$user.cid}"/>
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
