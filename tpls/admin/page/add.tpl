<form id="addform" class="formtable" action="{link ctrl=page method=add}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属栏目：</th>
				<td><select name="cat_id"><option value="0">所属栏目</option></select></td>
			</tr>
			<tr>
				<th>短标题：</th>
				<td><input name="title" type="text" value="{$add.title|html}" size="40" /></td>
			</tr>
			<tr>
				<th>页标题：</th>
				<td><input name="page_title" type="text" value="{$add.page_title|html}" size="40" /></td>
			</tr>
			<tr>
				<th>页名称：</th>
				<td><input name="page_name" type="text" value="{$add.page_name|html}" size="40" /></td>
			</tr>
			<tr>
				<th>页内容：</th>
				<td><textarea id="ckeditor" name="page_content">{$add.page_content|text}</textarea><label for="ckeditor"/></td>
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
CKEDITOR.instances={};
$('#ckeditor').ckeditor(function(){
	$('#addform').validate({
		rules:{
			cat_id:{
				required:true,
				min:1
			},
			title:{
				required:true,
				maxlength:20,
				chinese:true
			},
			page_title:{
				required:true,
				maxlength:40,
				chinese:true
			},
			page_name:{
				required:true,
				maxlength:40,
				english:true
			},
			page_content:{
				required:true,
				minlength:20
			},
			order:{
				integer:true
			}
		},
		messages:{
			cat_id:{min:'请选择'},
			content:{
				required:'至少得写点什么吧',
				minlength:'写的太少啦，再加20字如何！'
			}
		}
	});
	$('#addform').getWindow().resize();
}).ckeditorGet();
$('#addform select[name=cat_id]').staged('{link ctrl=category method=json type=page}',{val:{$add.cat_id}});
</script>