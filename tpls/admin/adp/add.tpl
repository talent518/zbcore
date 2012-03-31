<form id="addform" class="formtable" action="{link ctrl=adp method=add}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>标题：</th>
				<td><input name="ptitle" type="text" value="{$add.ptitle|html}" size="20" /></td>
			</tr>
			<tr>
				<th>名称：</th>
				<td><input name="pname" type="text" value="{$add.pname|html}" size="20" /></td>
			</tr>
			<tr>
				<th>类型：</th>
				<td>
					<input name="type" type="radio" value="html"{if $add.type=='html'} checked{/if}/>HTML
					<input name="type" type="radio" value="flash"{if $add.type=='flash'} checked{/if}/>Flash
					<input name="type" type="radio" value="image"{if $add.type=='image'} checked{/if}/>图片
					<input name="type" type="radio" value="text"{if $add.type=='text'} checked{/if}/>文本<label for="type"/>
				</td>
			</tr>
			<tr id="adp_size">
				<th>大小：</th>
				<td>
					宽：<input name="size[width]" type="text" value="{$add.size.width}" size="4" /><br/>
					高：<input name="size[height]" type="text" value="{$add.size.height}" size="4" />
				</td>
			</tr>
			<tr>
				<th>数量：</th>
				<td><input name="pcount" type="text" value="{$add.pcount}" size="20" /></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="porder" type="text" value="{$add.porder}" size="4" /></td>
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
		ptitle:{
			required:true,
			maxlength:20,
			chinese:true
		},
		pname:{
			required:true,
			maxlength:20,
			english:true
		},
		type:{
			required:true
		},
		'size[width]':{
			required:function(elem){
				if($.inArray($('[name=type]:checked',elem.form).val(),['flash','image'])!=-1){
					return true;
				}else{
					elem.value='';
					return false;
				}
			},
			integer:true,
			min:1
		},
		'size[height]':{
			required:function(elem){
				if($.inArray($('[name=type]:checked',elem.form).val(),['flash','image'])!=-1){
					return true;
				}else{
					elem.value='';
					return false;
				}
			},
			integer:true,
			min:1
		},
		pcount:{
			required:true,
			integer:true,
			min:1
		},
		porder:{
			integer:true
		}
	}
});
$('#addform input[name=type]').click(function(){
	$(this.form).required();
	if($.inArray($(this).val(),['flash','image'])!=-1){
		$('#adp_size').show();
	}else{
		$('#adp_size').hide();
	}
});
</script>