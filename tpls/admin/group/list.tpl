<h1 class="head">
	<a href="{link ctrl=group method=add id=$id}" class="add button" title="添加用户组">添加用户组</a>
	用户组管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=group method=list id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">用户组名称</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.gid}]" type="text" value="{$r.order}" style="width:24px" />
					<input name="_ids[{$r.gid}]" type="hidden" value="{$r.order}"/>
				</td>
				<td>{$r.gid}</td>
				<td class="l">{$r.gname}</td>
				<td>
					<a href="{link ctrl=user method=list id=$r.gid}" class="load" title="查看用户">查看</a>
				{if $r.gid>1}
					<span class="split">|</span>
					<a href="{link ctrl=group method=edit id=$r.gid}" class="edit" title="编辑用户组">编辑</a>
					{if !$r.protected}
						<span class="split">|</span>
						<a href="{link ctrl=group method=drop id=$r.gid}" class="drop" title="删除用户组">删除</a>
					{/if}
				{/if}
				</td>
			</tr>
			{/loop}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加用户组！</td>
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
