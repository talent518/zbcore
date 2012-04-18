<form class="formtable" id="dropform" action="{link ctrl=adp method=drop}" method="post">
<table cellspacing="0" cellpadding="0" border="0">
	<thead><tr><th>你确定要删除该广告位及广告位下的网址吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">标题：</th><td class="l">{$pos.ptitle}</td>
				</tr>
				<tr>
					<th class="r">名称：</th><td class="l">{$pos.pname}</td>
				</tr>
				<tr>
					<th class="r">类型：</th><td class="l">{if $pos.type=='html'}HTML{elseif $pos.type=='flash'}Flash{elseif $pos.type=='image'}图片{else}文本{/if}</td>
				</tr>
				<tr>
					<th class="r">大小：</th><td class="l"><b class="numeric">{$pos.size.width}</b>*<b class="numeric">{$pos.size.height}</b>px</td>
				</tr>
				<tr>
					<th class="r">广告数：</th><td class="l"><b class="numeric">{$pos.adnum}/{$pos.pcount}</b></td>
				</tr>
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
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
