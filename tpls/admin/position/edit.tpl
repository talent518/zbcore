<form id="editform" class="formtable" action="{link ctrl=position method=edit id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>推荐位名称：</th>
				<td><input name="pname" type="text" value="{$edit.pname|html}" size="20" /></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="porder" type="text" value="{$edit.porder}" size="4" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="editsubmit" type="submit" value="提交"/><input name="edithash" type="hidden" value="$edithash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#editform').validate({
	rules:{
		pname:{
			required:true,
			maxlength:20,
			chinese:true
		},
		porder:{
			integer:true
		}
	}
});
</script>