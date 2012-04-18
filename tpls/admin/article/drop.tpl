<form class="formtable" id="dropform" action="{link ctrl=article method=drop}" method="post">
<table cellspacing="0" cellpadding="0" border="0">
	<thead><tr><th>你确定要删除该文章吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">文章名称：</th><td class="l">{$article.name}</td>
				</tr>
				<tr>
					<th class="r">文章内容：</th><td class="l" style="position:relative;width:360px;height:220px;overflow:hidden;"><div style="position:absolute;border:1px #d8ecff solid;width:100%;height:100%;overflow:auto;left:0px;top:0px;">{$article.content}</div></td>
				</tr>
				<tr>
					<th>来源：</th>
					<td>{$edit.source|html}</td>
				</tr>
				<tr>
					<th class="r">发布时间：</th><td class="l">{date 'Y年m月d日',$article.addtime,1}</td>
				</tr>
				<tr>
					<th class="r">更新时间：</th><td class="l">{date 'Y年m月d日',$article.edittime,1}</td>
				</tr>
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
				<input name="pid" type="hidden" value="{$article.pid}"/>
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
