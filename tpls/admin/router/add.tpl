<form id="addform" class="formtable" action="{link ctrl=router method=add}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>源规则：</th>
				<td><input name="src" type="text" value="{$add.src|html}" size="20" /></td>
			</tr>
			<tr>
				<th>目标规则：</th>
				<td><input name="dest" type="text" value="{$add.dest|html}" size="50" /></td>
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
		src:{
			required:true,
			minlength:2
		},
		dest:{
			required:true,
			minlength:2
		},
		order:{
			integer:true
		}
	},
	messages:{
		src:{minlength:'规则太短'},
		dest:{minlength:'规则太短'}
	}
});
</script>