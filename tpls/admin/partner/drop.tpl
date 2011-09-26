<form class="formtable" id="dropform" action="{link ctrl=partner method=drop}" method="post">
<table class="list">
	<thead><tr><th>你确定要删除该栏目及栏目下的网址吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">网站名称：</th><td class="l">{$partner.name|html}</td>
				</tr>
				<tr>
					<th class="r">网址：</th><td class="l">{$partner.url|html}</td>
				</tr>
				{if $partner.logo}
				<tr>
					<th class="r">LOGO：</th><td class="l"><img src="{RES_UPLOAD_URL}{$partner.logo}" height="31"/></td>
				</tr>
				{/if}
				{if $partner.description}
				<tr>
					<th class="r">描述：</th><td class="l">{$partner.description|html}</td>
				</tr>
				{/if}
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
