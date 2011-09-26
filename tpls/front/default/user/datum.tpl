<form id="datumform" class="formtable" action="{link ctrl=user method=datum}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>公司名：</th>
				<td><input name="corpname" type="text" value="{$datum.corpname|html}" size="30" /></td>
			</tr>
			<tr>
				<th>联系人：</th>
				<td><input name="linkman" type="text" value="{$datum.linkman|html}" size="30" /></td>
			</tr>
			<tr>
				<th>通信地址：</th>
				<td><input name="address" type="text" value="{$datum.address|html}" size="30" /></td>
			</tr>
			<tr>
				<th>QQ号：</th>
				<td><input name="qq" type="text" value="{$datum.qq}" size="30" /></td>
			</tr>
			<tr>
				<th>MSN：</th>
				<td><input name="msn" type="text" value="{$datum.msn}" size="30" /></td>
			</tr>
			<tr>
				<th>手机号：</th>
				<td><input name="mobile" type="text" value="{$datum.mobile}" size="30" /></td>
			</tr>
			<tr>
				<th>固定电话：</th>
				<td><input name="phone" type="text" value="{$datum.phone}" size="30" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="datumsubmit" type="submit" value="提交"/><input name="datumhash" type="hidden" value="$datumhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#datumform').validate({
	rules:{
		corpname:{
			chinese:true
		},
		linkman:{
			required:true,
			chinese:true
		},
		address:{
			required:true,
			chinese:true
		},
		qq:{
			required:true,
			integer:true
		},
		msn:{
			email:true
		},
		mobile:{
			mobile:true
		},
		phone:{
			phone:true
		}
	}
});
</script>