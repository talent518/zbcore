<form id="editform" class="formtable" action="{link ctrl=picture method=edit id=$id}" method="post" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属栏目：</th>
				<td><select name="cat_id"></select></td>
			</tr>
			<tr>
				<th>图片标题：</th>
				<td><input name="title" type="text" value="{$edit.title|html}" size="20" /></td>
			</tr>
			<tr>
				<th>上传图片：</th>
				<td><input name="url" type="file" value="" /><input name="_url" type="hidden" value="{$edit.url|html}" size="50" /><label for="url"/><br/><label class="size"></label>&nbsp;<a href="{RES_UPLOAD_URL}{$edit.url}" target="_blank"><img src="{thumb RES_UPLOAD_DIR.$edit.url,100,100}"/></a></td>
			</tr>
		{if $posList}
			<tr>
				<th>推荐：</th>
				<td>{loop $posList $r}<input name="posids[]" type="checkbox" value="{$r.posid}"{if in_array($r.posid,explode(',',$edit.posids))} checked{/if}/>{$r.pname}&nbsp;&nbsp;{/loop}</td>
			</tr>
		{/if}
			<tr>
				<th>备注：</th>
				<td><textarea name="remark" cols="40" rows="3">{$edit.remark|html}</textarea></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$edit.order}" size="4" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input type="submit" value="提交"/><input name="editsubmit" type="hidden" value="1"/><input name="edithash" type="hidden" value="$edithash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#editform').validate({
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
		order:{
			integer:true
		}
	},messages:{
		cat_id:{min:'请选择'}
	}
});
$('#editform select[name=cat_id]').staged('{link ctrl=category method=json type=picture}',{val:{$edit.cat_id}});
</script>