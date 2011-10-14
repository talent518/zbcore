<h1 class="head">
	<a href="{link ctrl=user method=add id=$id}" class="add button" title="添加用户">添加用户</a>
	用户管理
</h1>
<form id="listform" class="formtable">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>ID</th>
				<th class="l" width="50%">用户名</th>
				<th class="l" width="50%">email</th>
				<th>注册IP</th>
				<th>注册时间</th>
				<th>最后登录IP</th>
				<th>最后登录时间</th>
				<th>最后活动时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>{$r.uid}</td>
				<td class="l">{$r.username}</td>
				<td class="l">{$r.email}</td>
				<td>{$r.regip}</td>
				<td>{date 'Y-m-d H:i:s',$r.regtime}</td>
				<td>{$r.lastloginip}</td>
				<td>{date 'Y-m-d H:i:s',$r.lastlogintime}</td>
				<td>{date 'Y-m-d H:i:s',$r.lastactivetime}</td>
				<td>
					<a href="{link ctrl=user method=datum id=$r.uid}" class="edit" title="查看用户资料">查看</a>
					<span class="split">|</span>
					<a href="{link ctrl=user method=edit id=$r.uid}" class="edit" title="编辑用户">编辑</a>
				{if !$r.protected}
					<span class="split">|</span>
					<a href="{link ctrl=user method=drop id=$r.uid}" class="drop" title="删除用户">删除</a>
				{/if}
				</td>
			</tr>
			{/loop}
			{if PAGES>1}
			<tr><td colspan="9">{pages ctrl=user method=list id=$id}</td></tr>
			{/if}
		{else}
			<tr>
				<td colspan="9" class="c">您还没有添加用户！</td>
			</tr>
		{/if}
		</tbody>
	</table>
</form>
<script type="text/javascript">
$('h1.head a.add,#listform a.add,#listform a.edit').window();
$('#listform a.drop').window({width:320});
$('#listform a.list').click(function(){
	$('#bd').load(this.href);
	return false;
});
</script>
