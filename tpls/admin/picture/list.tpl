<h1 class="head">
	<a href="{link ctrl=picture method=add id=$id}" class="add button" title="添加图片">添加图片</a>
	图片管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=picture method=list id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
	{if $catpos}
		<caption><b>当前位置：</b><a href="{link ctrl=picture}" class="list">图片管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=picture method=list id=$r.cat_id}" class="list">{$r.cat_name}</a>{/loop}</caption>
	{/if}
	{if $catlist}
		<thead>
			<tr><th colspan="6" class="l">栏目列表</th></tr>
		</thead>
		<tbody>
			<tr>
			{$i=0;}
			{loop $catlist $r}
				<td width="12.5%"><a href="{link ctrl=picture method=list id=$r.cat_id}" class="list">{$r.cat_name}(<b class="numeric">{$r.counts}</b>)</a></td>
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
				<th class="l" width="100%">图片标题</th>
				<th>缩略图</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.pic_id}]" type="text" value="{$r.order}" style="width:24px"/>
					<input name="_ids[{$r.pic_id}]" type="hidden" value="{$r.order}"/>
				</td>
				<td>{$r.pic_id}</td>
				<td class="l">{$r.title}</td>
				<td class="l"><img src="{thumb RES_UPLOAD_DIR.$r.url,50,50}"/></td>
				<td>
					<a href="{link proj=index method=picture id=$r.pic_id}" target="_blank" title="查看图片">查看</a>
					<span class="split">|</span>
					<a href="{link ctrl=picture method=edit id=$r.pic_id}" class="edit" title="编辑图片">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=picture method=drop id=$r.pic_id}" class="drop" title="删除图片">删除</a>
				</td>
			</tr>
			{/loop}
			{if PAGES>1}
			<tr><td colspan="5">{pages ctrl=picture method=list id=$id}</td></tr>
			{/if}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加图片！</td>
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
$('#listform a.drop').window({width:600});
$('#listform a.list').click(function(){
	$('#bd').load(this.href);
	return false;
});
$('#listform').validate();
</script>
