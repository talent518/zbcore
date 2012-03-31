<h1 class="head">
	<a href="{link ctrl=ad method=add id=$id}" class="add button" title="添加广告">添加广告</a>
	广告管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=ad method=list id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
	{if $poslist}
		<thead>
			<tr><th colspan="8" class="l">广告位列表</th></tr>
		</thead>
		<tbody>
			<tr>
			{$i=0;}
			{loop $poslist $r}
				<td width="12.5%"{if $r.pid==$id} class="active"{/if}><a href="{link ctrl=ad method=list id=$r.pid}" class="list">{$r.ptitle}</a></td>
				{if ($i++)%8==9}
				</tr><tr>
				{/if}
			{/loop}
			{loop array_pad(array(),8-($i%8),0) $i}
				<td width="12.5%"></td>
			{/loop}
			</tr>
		</tbody>
	{/if}
	</table>

	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">标题</th>
				<th class="l">调用代码</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.id}]" type="text" value="{$r.order}" style="width:24px" />
					<input name="_ids[{$r.id}]" type="hidden" value="{$r.order}"/>
				</td>
				<td>{$r.id}</td>
				<td class="l">{$r.title}</td>
				<td class="l">{ad&nbsp;{$r.id}},&#123;ad:{$r.name}}</td>
				<td>
					<a href="{link ctrl=ad method=show id=$r.id}" target="_blank" title="预览广告">预览</a>
					<span class="split">|</span>
					<a href="{link ctrl=ad method=edit id=$r.id}" class="edit" title="编辑广告">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=ad method=drop id=$r.id}" class="drop" title="删除广告">删除</a>
				</td>
			</tr>
			{/loop}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加广告！</td>
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
