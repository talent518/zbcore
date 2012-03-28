<form class="formtable" id="dropform" action="{link ctrl=picture method=drop}" method="post">
<table class="list">
{if $catpos}
	<caption><b>当前位置：</b><a href="{link ctrl=picture}" class="list">图片管理</a>{loop $catpos $r}<b class="gray">&gt;</b><a href="{link ctrl=picture method=list id=$r.cat_id}" class="list">{$r.cname}</a>{/loop}</caption>
{/if}
	<thead><tr><th>你确定要删除该图片吗？</th></tr></thead>
	<tbody>
		<tr>
			<td><table>
				<tr>
					<th class="r">图片标题：</th><td class="l">{$picture.title|html}</td>
				</tr>
				<tr>
					<th class="r">图片：</th><td class="l"><img src="{thumb RES_UPLOAD_DIR.$picture.url,100,100}"/></td>
				</tr>
				<tr>
					<th class="r">备注：</th><td class="l">{$picture.remark|html}</td>
				</tr>
				<tr>
					<th class="r">发布时间：</th><td class="l">{date 'Y年m月d日',$picture.addtime,1}</td>
				</tr>
				<tr>
					<th class="r">更新时间：</th><td class="l">{date 'Y年m月d日',$picture.edittime,1}</td>
				</tr>
			</table></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<input name="id" type="hidden" value="$id"/>
				<input name="cat_id" type="hidden" value="{$picture.cat_id}"/>
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
