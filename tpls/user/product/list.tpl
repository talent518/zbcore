<h1 class="head">
	<a href="{link ctrl=product method=add id=$id}" class="add button" title="添加产品">添加产品</a>
	产品管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=product method=list id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">产品标题</th>
				<th>缩略图</th>
				<th>价格</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.prod_id}]" type="text" value="{$r.order}" style="width:24px"/>
					<input name="_ids[{$r.prod_id}]" type="hidden" value="{$r.order}"/>
				</td>
				<td>{$r.prod_id}</td>
				<td class="l">{$r.title}</td>
				<td class="l"><img src="{thumb RES_UPLOAD_DIR.$r.url,50,50}"/></td>
				<td class="l">{$r.price|money}</td>
				<td>
					<a href="{link ctrl=space method=product id=$r.prod_id}" target="_blank" title="查看产品">查看</a>
					<span class="split">|</span>
					<a href="{link ctrl=product method=edit id=$r.prod_id}" class="edit" title="编辑产品">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=product method=drop id=$r.prod_id}" class="drop" title="删除产品">删除</a>
				</td>
			</tr>
			{/loop}
			{if PAGES>1}
			<tr><td colspan="6">{pages ctrl=product method=list id=$id}</td></tr>
			{/if}
		{else}
			<tr>
				<td colspan="6" class="c">您还没有添加产品！</td>
			</tr>
		{/if}
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6" class="l">
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
