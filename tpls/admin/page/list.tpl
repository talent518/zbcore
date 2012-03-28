<h1 class="head">
	<a href="{link ctrl=page method=add id=$id}" class="add button" title="添加单页">添加单页</a>
	文章管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=page method=list}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
	{if $catpos}
		<caption><b>当前位置：</b><a href="{link ctrl=page}" class="list">单页管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=page method=list id=$r.cat_id}" class="list">{$r.cat_name}</a>{/loop}</caption>
	{/if}
	{if $catlist}
		<thead>
			<tr><th colspan="6" class="l">栏目列表</th></tr>
		</thead>
		<tbody>
			<tr>
			{$i=0;}
			{loop $catlist $r}
				<td width="12.5%"><a href="{link ctrl=page method=list id=$r.cat_id}" class="list">{$r.cat_name}(<b class="numeric">{$r.counts}</b>)</a></td>
				{if ($i++)%6==5}
				</tr><tr>
				{/if}
			{/loop}
			{loop array_pad(array(),6-($i%6),0) $i}
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
				<th class="l" width="100%">文章名称</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.page_id}]" type="text" value="{$r.order}" style="width:24px" />
					<input name="_ids[{$r.page_id}]" type="hidden" value="{$r.order}"/>
				</td>
				<td>{$r.page_id}</td>
				<td class="l">{$r.title}</td>
				<td>
					<a href="{link proj=index method=page id=$r.page_id}" title="查看页" target="_blank">查看</a>
					<span class="split">|</span>
					<a href="{link ctrl=page method=edit id=$r.page_id}" class="edit" title="编辑页">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=page method=drop id=$r.page_id}" class="drop" title="删除页">删除</a>
				</td>
			</tr>
			{/loop}
			{if PAGES>1}
				<tr><td colspan="5">{pages ctrl=page method=list id=$id}</td></tr>
			{/if}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加{if !$id}子{/if}单页！</td>
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
$('h1.head a.add,#listform a.add,#listform a.edit').window({width:1000});
$('#listform a.drop').window({width:500});
$('#listform a.list').click(function(){
	$('#bd').load(this.href);
	return false;
});
$('#listform').validate();
</script>