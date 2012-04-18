<form class="formtable" id="dropform" action="{link ctrl=ad method=drop}" method="post">
<table cellspacing="0" cellpadding="0" border="0">
{if $catpos}
	<caption><b>当前位置：</b><a href="{link ctrl=ad}" class="list">广告管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=ad method=list id=$r.pid}" class="list">{$r.cname}</a>{/loop}</caption>
{/if}
	<thead><tr><th>你确定要删除该广告吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">广告标题：</th>
					<td class="l">{$ad.title|html}</td>
				</tr>
				<tr>
					<th class="r">广告名称：</th>
					<td class="l">{$ad.name|html}</td>
				</tr>
			{if $adp.type=='html'}
				<tr>
					<th class="r">广告代码：</th>
					<td class="l">{$edit.code.html|html}</td>
				</tr>
			{elseif $adp.type=='flash'}
				<tr>
					<th class="r">Flash地址：</th>
					<td class="l">{RES_UPLOAD_URL}{$ad.code.flash|html}</td>
				</tr>
			{elseif $adp.type=='image'}
				<tr>
					<th class="r">图片地址：</th>
					<td class="l">{RES_UPLOAD_URL}{$ad.code.src|html}</td>
				</tr>
				<tr>
					<th class="r">图片链接：</th>
					<td class="l">{$ad.code.url|html}</td>
				</tr>
				<tr>
					<th class="r">图片替换文字：</th>
					<td class="l">{$ad.code.alt|html}</td>
				</tr>
			{elseif $adp.type=='text'}
				<tr>
					<th class="r">文字内容：</th>
					<td class="l">{$ad.code.text|html}</td>
				</tr>
				<tr>
					<th class="r">文字链接：</th>
					<td class="l">{$ad.code.url|html}</td>
				</tr>
				<tr>
					<th class="r">文字大小：</th>
					<td class="l">{if $ad.code.size}{$ad.code.size}{else}默认{/if}</td>
				</tr>
			{/if}
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
				<input name="pid" type="hidden" value="{$ad.pid}"/>
				<input name="dropsubmit" type="submit" value="提交"/>
				<input name="drophash" type="hidden" value="$drophash"/>
			</td>
		</tr>
	</tfoot>
</table>
</form>
<script type="text/javascript">
$('#dropform').validate();
</script>
