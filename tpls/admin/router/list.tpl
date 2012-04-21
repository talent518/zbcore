<h1 class="head">
	<a href="{link ctrl=router method=add}" class="add button" title="添加URL路由规则">添加规则</a>
	URL路由规则管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=router method=list}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="50%">源规则</th>
				<th class="l" width="50%">目标规则</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.rid}]" type="text" value="{$r.order}" style="width:24px" />
					<input name="_ids[{$r.rid}]" type="hidden" value="{$r.order}"/>
				</td>
				<td>{$r.rid}</td>
				<td class="l">{$r.src|text}</td>
				<td class="l">{$r.dest|text}</td>
				<td>
					<a href="{link ctrl=router method=edit id=$r.rid}" class="edit" title="编辑URL路由">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=router method=drop id=$r.rid}" class="drop" title="删除URL路由">删除</a>
				</td>
			</tr>
			{/loop}
			{if PAGES>1}
				<tr><td colspan="5">{pages ctrl=router method=list}</td></tr>
			{/if}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加URL路由！</td>
			</tr>
		{/if}
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" class="l">
					<input name="listsubmit" type="submit" value="排序"/>
					<input name="listhash" type="hidden" value="$listhash"/>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('h1.head a.add,#listform a.add,#listform a.edit').window();
$('#listform a.drop').window({width:320});
$('#listform a.list').click(function(){
	$('#bd').load(this.href);
	return false;
});
$('#bd .load').click(function(){
	$('#bd').load(this.href);
	return false;
});
$('#listform').validate();
</script>
