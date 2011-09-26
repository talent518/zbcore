<form id="editform" class="formtable" action="{link ctrl=adp method=edit id=$id}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>广告位名称：</th>
				<td><input name="pname" type="text" value="{$edit.pname|html}" size="20" /></td>
			</tr>
			<tr>
				<th>类型：</th>
				<td>
					<input name="type" type="radio" value="html"{if $edit.type=='html'} checked{/if}/>HTML
					<input name="type" type="radio" value="flash"{if $edit.type=='flash'} checked{/if}/>Flash
					<input name="type" type="radio" value="image"{if $edit.type=='image'} checked{/if}/>图片
					<input name="type" type="radio" value="text"{if $edit.type=='text'} checked{/if}/>文本<label for="type"/>
				</td>
			</tr>
			<tr>
				<th>大小：</th>
				<td>
					宽：<input name="size[width]" type="text" value="{$edit.size.width}" size="4" /><br/>
					高：<input name="size[height]" type="text" value="{$edit.size.height}" size="4" />
				</td>
			</tr>
			<tr>
				<th>数量：</th>
				<td><input name="pcount" type="text" value="{$edit.pcount}" size="20" /></td>
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
		type:{
			required:true
		},
		'size[width]':{
			required:function(elem){return($.inArray($('[name=type]:checked',elem.form).val(),['flash','image'])!=-1);},
			integer:true,
			min:1
		},
		'size[height]':{
			required:function(elem){return($.inArray($('[name=type]:checked',elem.form).val(),['flash','image'])!=-1);},
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
$('#editform input[name=type]').click(function(){
	$(this.form).required();
});
</script>