<form id="addform" class="formtable" action="{link ctrl=article method=add}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>文章名称：</th>
				<td><input name="name" type="text" value="{$add.name|html}" size="40" /></td>
			</tr>
			<tr>
				<th>SEO标题：</th>
				<td><input name="seo[title]" type="text" value="{$add.seo.title|html}" size="40" /></td>
			</tr>
			<tr>
				<th>SEO关键词：</th>
				<td><input name="seo[keywords]" type="text" value="{$add.seo.keywords|html}" size="30" /></td>
			</tr>
			<tr>
				<th>SEO描述：</th>
				<td><input name="seo[description]" type="text" value="{$add.seo.description|html}" size="50" /></td>
			</tr>
			<tr>
				<th>文章内容：</th>
				<td><textarea id="ckeditor" name="content">{$add.content|text}</textarea><label for="ckeditor"/></td>
			</tr>
			<tr>
				<th>推荐：</th>
				<td><input name="recommended" type="radio" value="1"/>是&nbsp;<input name="recommended" type="radio" value="0" checked/>否</td>
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
			name:{
				required:true,
				maxlength:20,
				chinese:true
			},
			content:{
				required:true,
				minlength:20
			},
			order:{
				integer:true
			}
		},
		messages:{
			content:{
				required:'至少得写的什么吧',
				minlength:'至少得写的什么吧'
			}
		}
	});
	$.window.resize();
}).ckeditorGet();
</script>