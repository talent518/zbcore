<h1 class="head">
	<a href="{link ctrl=partner method=add}" class="add button" title="添加友情链接">添　加</a>
	友情链接管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=partner method=list}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">网站名称</th>
				<th>Logo</th>
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
				<td class="l"><a href="{$r.url}" target="_blank">{$r.name}</a></td>
				<td class="l">{if $r.logo}<img src="{RES_UPLOAD_URL}{$r.logo}" height="31"/>{/if}</td>
				<td>
					<a href="{link ctrl=partner method=edit id=$r.id}" class="edit" title="编辑友情链接">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=partner method=drop id=$r.id}" class="drop" title="删除友情链接">删除</a>
				</td>
			</tr>
			{/loop}
			{if PAGES>1}
			<tr><td colspan="9">{pages ctrl=partner method=list id=$id}</td></tr>
			{/if}
		{else}
			<tr>
				<td colspan="5" class="c">您还没有添加链接！</td>
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
