<form id="editform" class="formtable" action="{link ctrl=ad method=edit id=$id}" method="post" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属广告位：</th>
				<td><select name="pid"></select><input name="type" type="hidden" value=""/></td>
			</tr>
			<tr>
				<th>所属地区：</th>
				<td><select name="aid"></select></td>
			</tr>
			<tr>
				<th>广告名称：</th>
				<td><input name="name" type="text" value="{$edit.name|html}" size="20" /></td>
			</tr>
		</body>
		<tbody class="type html">
			<tr>
				<th>广告代码：</th>
				<td><textarea rows="10" style="width:98%;" name="code[html]">{$edit.code.html|html}</textarea></td>
			</tr>
		</tbody>
		<tbody class="type flash">
			<tr>
				<th>Flash地址：</th>
				<td><input name="flash" type="file" value="" /><input name="code[flash]" type="hidden" value="{$edit.code.flash|html}"><label for="flash"/><br/><label class="size"></label><a href="{RES_UPLOAD_URL}{$edit.code.flash}" target="_blank">查看</a></td>
			</tr>
		</tbody>
		<tbody class="type image">
			<tr>
				<th>图片地址：</th>
				<td><input name="image" type="file" value="" /><input name="code[image][src]" type="hidden" value="{$edit.code.image.src|html}" size="50" /><label for="image"/><br/><label class="size"></label>&nbsp;<a href="{RES_UPLOAD_URL}{$edit.code.image.src}" target="_blank">查看</a></td>
			</tr>
			<tr>
				<th>图片链接：</th>
				<td><input name="code[image][url]" type="text" value="{$edit.code.image.url|html}" size="50" /></td>
			</tr>
			<tr>
				<th>图片替换文字：</th>
				<td><input name="code[image][alt]" type="text" value="{$edit.code.image.alt|html}" size="50" /></td>
			</tr>
		</tbody>
		<tbody class="type text">
			<tr>
				<th>文字内容：</th>
				<td><input name="code[text][text]" type="text" value="{$edit.code.text.text|html}" size="50" ></td>
			</tr>
			<tr>
				<th>文字链接：</th>
				<td><input name="code[text][url]" type="text" value="{$edit.code.text.url|html}" size="50" ></td>
			</tr>
			<tr>
				<th>文字样式：</th>
				<td>
					大小<input name="code[text][size]" type="text" value="{$edit.code.text.size}" size="5"><br/>
					颜色<input name="code[text][color]" type="text" value="{$edit.code.text.color}" size="6"><br/>
					<input name="code[text][bold]" type="checkbox" value="1"{if $edit.code.text.bold} checked{/if}>加粗<br/>
					<input name="code[text][italic]" type="checkbox" value="1"{if $edit.code.text.italic} checked{/if}>倾斜
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<th>状态：</th>
				<td>
					<input name="enable" type="radio" value="1"{if $edit.enable} checked{/if}/>启用
					<input name="enable" type="radio" value="0"{if !$edit.enable} checked{/if}/>关闭
				</td>
			</tr>
			<tr>
				<th>过期时间：</th>
				<td><input name="expired" type="text" value="{date 'Y-m-d',$edit.expired}" size="10" /></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$edit.order}" size="4" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input type="submit" value="提交"/><input name="editsubmit" type="hidden" value="1"/><input name="edithash" type="hidden" value="$edithash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#editform input[name=expired]').datepick();
$('#editform').validate({
	rules:{
		pid:{
			required:true,
			integer:true,
			min:1
		},
		aid:{
			required:true,
			integer:true
		},
		name:{
			required:true,
			maxlength:50,
			chinese:true
		},
		'code[html]':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='html';
			}
		},
		'flash':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='flash' && $('#editform input[name="code[flash]"]').val()=='';
			},
			accept:'swf,flv'
		},
		'code[flash]':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='flash' && $('#editform input[name=flash]').val()=='';
			}
		},
		'image':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='image' && $('#editform input[name="code[image][src]"]').val()=='';
			},
			accept:true
		},
		'code[image][src]':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='image' && $('#editform input[name=image]').val()=='';
			}
		},
		'code[image][url]':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='image';
			}
		},
		'code[image][alt]':{
			maxlength:50
		},
		'code[text][text]':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='text';
			}
		},
		'code[text][url]':{
			required:function(elem){
				return $('#editform input[name=type]').val()=='text';
			}
		},
		'code[text][size]':{
			custom:/^\d+(\.\d+)?(px|pt|em|cm|mm|in|pc|ex)$/
		},
		'code[text][color]':{
			custom:/^((#[a-fA-F0-9]{3,6})|([a-zA-Z]+))$/
		},
		expired:{
			required:true,
			dateISO:true
		},
		order:{
			integer:true
		}
	},messages:{
		pid:{
			min:'请选择'
		},
		'code[text][size]':{
			custom:'格式不正确，如：12px,12em,12pt,2em,2cm,10mm,10in,10pc,2ex'
		},
		'code[text][color]':{
			custom:'颜色格式不正确，如：red,#0f0,#ff0000'
		}
	}
});
$('#editform tbody.type').hide();
$('#editform select[name=pid]').staged('{link ctrl=ad method=json}',{val:{$edit.pid},isStaged:false,keyText:'pname',change:function(pos){
	$('#editform tbody.type').hide();
	if(pos){
		if(pos.size)
			$('#editform tbody.'+pos.type).show().find('.size').html(sprintf('%s大小:<b class="numeric">%s</b>*<b class="numeric">%s</b>px',pos.type=='image'?'图片':'Flash',pos.size.width,pos.size.height));
		$('#editform input[name=type]').val(pos.type);
	}else{
		$('#editform input[name=type]').val('');
	}
	$('#editform').required().getWindow().resize();
}});
$('#editform select[name=aid]').staged('{link ctrl=area method=json}',{val:{$edit.aid}});
</script>