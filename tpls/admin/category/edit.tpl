<form id="editform" class="formtable" action="{link ctrl=category method=edit id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>上级栏目：</th>
				<td><select name="pid" names="pids"><option value="0">一级栏目</option></select></td>
			</tr>
			<tr>
				<th>栏目名称：</th>
				<td><input name="cname" type="text" value="{$edit.cname|html}" size="20" /></td>
			</tr>
			<tr>
				<th>SEO标题：</th>
				<td><input name="cseo[title]" type="text" value="{$edit.cseo.title|html}" size="30" /></td>
			</tr>
			<tr>
				<th>SEO关键词：</th>
				<td><input name="cseo[keywords]" type="text" value="{$edit.cseo.keywords|html}" size="30" /></td>
			</tr>
			<tr>
				<th>SEO描述：</th>
				<td><textarea name="cseo[description]" cols="40" rows="3">{$edit.cseo.description|html}</textarea></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="corder" type="text" value="{$edit.corder}" size="4" /></td>
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
		pid:{
			required:true,
			integer:true
		},
		cname:{
			required:true,
			maxlength:20,
			chinese:true
		},
		corder:{
			integer:true
		}
	}
});
$('#editform select[name=pid]').staged('{link ctrl=category method=json}',{val:{$edit.pid},not:{$edit.cid}});
</script>