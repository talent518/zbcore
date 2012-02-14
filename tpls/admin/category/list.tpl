<h1 class="head">
	<a href="{link ctrl=category method=add id=$id}" class="add button" title="添加栏目">添加栏目</a>
	<a href="{link ctrl=category method=update id=$id}" class="button" title="更新缓存" onclick="$.post(this.href);return false;">更新缓存</a>
	栏目管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=category method=list id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
	{if $catpos}
		<caption><b>当前位置：</b><a href="{link ctrl=category}" class="list">栏目管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=category method=list id=$r.cid}" class="list">{$r.cname}</a>{/loop}</caption>
	{/if}
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">栏目名称</th>
				<th>站点数</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.cid}]" type="text" value="{$r.corder}" style="width:24px" />
					<input name="_ids[{$r.cid}]" type="hidden" value="{$r.corder}"/>
				</td>
				<td>{$r.cid}</td>
				<td class="l"><a href="{link ctrl=category method=list id=$r.cid}" class="list">{$r.cname}</a></td>
				<td class="l">{$r.counts}</td>
				<td>
					<a href="{link ctrl=category method=add id=$r.cid}" class="add" title="添加栏目">添加</a>
					<span class="split">|</span>
					<a href="{link ctrl=category method=edit id=$r.cid}" class="edit" title="编辑栏目">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=category method=drop id=$r.cid}" class="drop" title="删除栏目">删除</a>
				</td>
			</tr>
			{/loop}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加{if !$id}子{/if}栏目！</td>
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
$('#listform').validate();
</script>
