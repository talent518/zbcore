<form id="addform" class="formtable" action="{link ctrl=area method=add}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>上级地区：</th>
				<td><select name="pid" names="pids"><option value="0">一级地区</option></select></td>
			</tr>
			<tr>
				<th>地区编号：</th>
				<td><input name="id" type="text" value="{$add.id}"/></td>
			</tr>
			<tr>
				<th>地区名称：</th>
				<td><input name="name" type="text" value="{$add.name|html}" size="20" /></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$add.order}" size="4" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="addsubmit" type="submit" value="提交"/><input name="addhash" type="hidden" value="$addhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#addform').validate({
	rules:{
		id:{
			required:true,
			integer:true,
			min:1
		},
		pid:{
			required:true,
			integer:true
		},
		name:{
			required:true,
			maxlength:20,
			chinese:true
		},
		order:{
			integer:true
		}
	}
});
$('#addform select[name=pid]').staged('{link ctrl=area method=json}',{val:{$add.pid}});
</script>