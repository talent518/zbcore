<form id="editform" class="formtable" action="{link ctrl=group method=edit id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>用户组名称：</th>
				<td><input name="gname" type="text" value="{$edit.gname|html}" size="20" /></td>
			</tr>
			<tr>
				<th>备注：</th>
				<td><textarea name="remark" cols="40" rows="3">{$edit.remark|text}</textarea></td>
			</tr>
			<tr>
				<th>受保护：</th>
				<td><input name="protected" type="radio" value="1"{if $edit.protected} checked{/if}/>是&nbsp;<input name="protected" type="radio" value="0"{if !$edit.protected} checked{/if}/>否</td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$edit.order}" size="4" /></td>
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
		gname:{
			required:true,
			maxlength:50,
			chinese:true
		},
		order:{
			integer:true
		}
	}
});
</script>