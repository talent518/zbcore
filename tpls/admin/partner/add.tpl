<form id="addform" class="formtable" action="{link ctrl=partner method=add}" method="post" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>网站名称：</th>
				<td><input name="name" type="text" value="{$add.name|html}" size="20" /></td>
			</tr>
			<tr>
				<th>网址：</th>
				<td><input name="url" type="text" value="{$add.url}" size="40" /></td>
			</tr>
			<tr>
				<th>LOGO：</th>
				<td><input name="logo" type="file" value="" size="28" /></td>
			</tr>
			<tr>
				<th>描述：</th>
				<td><textarea name="description" cols="40" rows="3">{$add.description|text}</textarea></td>
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
		name:{
			required:true,
			maxlength:20,
			chinese:true
		},
		url:{
			required:true
		},
		order:{
			integer:true
		}
	}
});
</script>