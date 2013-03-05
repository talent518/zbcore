<h1 class="head">
	<a href="{link ctrl=adp method=add}" class="add button" title="添加广告位">添　加</a>
	广告位管理
</h1>
<form id="listform" class="formtable" action="{link ctrl=adp method=list}" method="post">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>排序</th>
				<th>ID</th>
				<th class="l" width="100%">标题</th>
				<th class="l">调用代码</th>
				<th>类型</th>
				<th>大小（宽/高）</th>
				<th>广告数（现有/限制）</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		{if $list}
			{$i=0;}
			{loop $list $r}
			<tr class="{if $i%2==1}odd{else}even{/if}">{$i++;}
				<td>
					<input name="ids[{$r.pid}]" type="text" value="{$r.porder}" style="width:24px" />
					<input name="_ids[{$r.pid}]" type="hidden" value="{$r.porder}"/>
				</td>
				<td>{$r.pid}</td>
				<td class="l">{$r.ptitle}</td>
				<td class="l">{adp&nbsp;{$r.pid}},&#123;adp:{$r.pname}}</td>
				<td class="l">{if $r.type=='html'}HTML{elseif $r.type=='flash'}Flash{elseif $r.type=='image'}图片{else}文本{/if}</td>
				<td class="l">{$r.size.width}/{$r.size.height}</td>
				<td class="l">{$r.adnum}/{$r.pcount}</td>
				<td>
					<a href="{link ctrl=adp method=show id=$r.pid}" target="_blank" title="预览广告位">预览</a>
					<span class="split">|</span>
					<a href="{link ctrl=adp method=edit id=$r.pid}" class="edit" title="编辑广告位">编辑</a>
					<span class="split">|</span>
					<a href="{link ctrl=adp method=drop id=$r.pid}" class="drop" title="删除广告位">删除</a>
				</td>
			</tr>
			{/loop}
		{else}
			<tr>
				<td colspan="8" class="c">您还没有添加广告位！</td>
			</tr>
		{/if}
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8" class="l">
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
