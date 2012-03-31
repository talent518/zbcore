<h1 class="head">
	<a href="{link ctrl=position method=add}" class="add button" title="添加推荐位">添　加</a>
	推荐位管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=position method=list}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">推荐位名称</th>
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
					<input name="ids[{$r.posid}]" type="text" value="{$r.porder}" style="width:24px" />
					<input name="_ids[{$r.posid}]" type="hidden" value="{$r.porder}"/>
				</td>
				<td>{$r.posid}</td>
				<td class="l">{$r.pname}</td>
				<td class="l">{$r.counts}</td>
				<td>
					<a href="{link ctrl=position method=edit id=$r.posid}" class="edit" title="编辑栏目">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=position method=drop id=$r.posid}" class="drop" title="删除栏目">删除</a>
				</td>
			</tr>
			{/loop}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加推荐位！</td>
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
$('h1.head a.add,#listform a.edit').window();
$('#listform a.drop').window({width:320});
$('#listform').validate();
</script>
