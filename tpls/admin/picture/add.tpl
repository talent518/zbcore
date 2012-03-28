<form id="addform" class="formtable" action="{link ctrl=picture method=add}" method="post" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属栏目：</th>
				<td><select name="cat_id"></select></td>
			</tr>
			<tr>
				<th>图片标题：</th>
				<td><input name="title" type="text" value="{$add.title|html}" size="20" /></td>
			</tr>
			<tr>
				<th>上传图片：</th>
				<td><input name="url" type="file" value=""/></td>
			</tr>
		{if $posList}
			<tr>
				<th>推荐：</th>
				<td>{loop $posList $r}<input name="posids[]" type="checkbox" value="{$r.posid}"/>{$r.pname}&nbsp;&nbsp;{/loop}</td>
			</tr>
		{/if}
			<tr>
				<th>备注：</th>
				<td><textarea name="remark" cols="40" rows="3">{$add.remark|html}</textarea></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$add.order}" size="4" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input type="submit" value="提交"/><input name="addsubmit" type="hidden" value="1"/><input name="addhash" type="hidden" value="$addhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#addform').validate({
	rules:{
		cat_id:{
			required:true,
			min:1
		},
		name:{
			required:true,
			maxlength:50,
			chinese:true
		},
		url:{
			required:true
		},
		order:{
			integer:true
		}
	},messages:{
		cat_id:{min:'请选择'}
	}
});
$('#addform select[name=cat_id]').staged('{link ctrl=category method=json type=picture}',{val:{$add.cat_id}});
</script>