<form class="formtable" id="dropform" action="{link ctrl=product method=drop}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<thead><tr><th>你确定要删除该产品吗？</th></tr></thead>
		<tbody>
			<tr>
				<td><table>
					<tr>
						<th class="r">产品标题：</th><td class="l">{$product.title|html}</td>
					</tr>
					<tr>
						<th class="r">产品：</th><td class="l">
						{loop M('user.product.image')->get_list_by_where('prod_id='.$id) $k $r}
							<p>
								<img src="{RES_UPLOAD_URL}{$r.url}" width="100" style="margin-right:5px;border:2px {if $r.url==$product.url}red{else}white{/if} solid"/>
								<span>{$r.remark}</span>
							</p>
						{/loop}
						</td>
					</tr>
					<tr>
						<th class="r">备注：</th><td class="l" style="white-space:normal;">{$product.remark|html}</td>
					</tr>
					<tr>
						<th class="r">发布时间：</th><td class="l">{date 'Y年m月d日',$product.addtime,1}</td>
					</tr>
					<tr>
						<th class="r">更新时间：</th><td class="l">{date 'Y年m月d日',$product.edittime,1}</td>
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
