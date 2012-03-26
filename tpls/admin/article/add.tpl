<form id="addform" class="formtable" action="{link ctrl=article method=add}" method="post">
	<ul class="tab_title">
		<li class="active">基本选项</li>
		<li>SEO设置</li>
		<li>特殊字段</li>
	</ul>
	<table class="tab_body" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属栏目：</th>
				<td><select name="pid" names="pids"><option value="0">所属栏目</option></select></td>
			</tr>
			<tr>
				<th>文章名称：</th>
				<td><input name="title" type="text" value="{$add.title|html}" size="40" /></td>
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
		<tbody>
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
		</tbody>
		<tbody>
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
	$('#addform').getWindow().resize();
}).ckeditorGet();
$('#addform select[name=cat_id]').staged('{link ctrl=category method=json}',{val:{$add.cat_id}});
</script>
