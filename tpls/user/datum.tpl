<form id="datumform" class="formtable" action="{link method=datum}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
		{if $MEMBER.iscorp}
			<tr>
				<th>公司名：</th>
				<td><input name="corpname" type="text" value="{$datum.corpname|html}" size="30" /></td>
			</tr>
			<tr>
				<th>公司介绍：</th>
				<td><textarea name="introduce" cols="60" rows="5" >{$datum.introduce|text}</textarea><br/><label for="introduce"></label></td>
			</tr>
		{/if}
			<tr>
				<th>{if $MEMBER.iscorp}联系人{else}姓名{/if}：</th>
				<td><input name="linkman" type="text" value="{$datum.linkman|html}" size="30" /></td>
			</tr>
		{if !$MEMBER.iscorp}
			<tr>
				<th>性别：</th>
				<td>
					<input name="sex" type="radio" value="1" size="30"{if $datum.sex} checked{/if}/>男&nbsp;
					<input name="sex" type="radio" value="0" size="30"{if $datum.sex===0} checked{/if}/>女<label for="sex"></label>
				</td>
			</tr>
		{/if}
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
			<tr>
				<th>传真：</th>
				<td><input name="fax" type="text" value="{$datum.fax}" size="30" /></td>
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
			required:true,
			chinese:true
		},
		linkman:{
			required:true,
			chinese:true
		},
		introduce:{
			required:true,
			minlength:30
		},
		sex:{
			required:true
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
		},
		fax:{
			phone:true
		}
	}
});
</script>