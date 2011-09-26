<h1 class="head">
	<a href="{link ctrl=area method=add id=$id}" class="add button" title="添加地区">添加地区</a>
	地区管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=area method=list id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
	{if $areapos}
		<caption><b>当前位置：</b><a href="{link ctrl=area}" class="list">地区管理</a>{loop $areapos $r}<b class="gray">&gt;</b><a href="{link ctrl=area method=list id=$r.id}" class="list">{$r.name}</a>{/loop}</caption>
	{/if}
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">地区名称</th>
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
				<td class="l">{$r.name}</td>
				<td>
					<a href="{link ctrl=area method=list id=$r.id}" class="list" title="子级地区">子级地区</a>
					<span class="split">|</span>
					<a href="{link ctrl=area method=add id=$r.id}" class="add" title="添加地区">添加</a>
					<span class="split">|</span>
					<a href="{link ctrl=area method=edit id=$r.id}" class="edit" title="编辑地区">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=area method=drop id=$r.id}" class="drop" title="删除地区">删除</a>
				</td>
			</tr>
			{/loop}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加{if !$id}子{/if}地区！</td>
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
