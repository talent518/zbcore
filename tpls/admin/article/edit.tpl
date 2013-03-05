<form id="editform" class="formtable" action="{link ctrl=article method=edit id=$id}" method="post" enctype="multipart/form-data">
	<ul class="tab_title">
		<li class="active">基本选项</li>
		<li>SEO设置</li>
		<!--li>特殊字段</li-->
	</ul>
	<table class="tab_body" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属栏目：</th>
				<td><select name="cat_id"><option value="0">所属栏目</option></select></td>
			</tr>
			<tr>
				<th>文章名称：</th>
				<td><input name="title" type="text" value="{$edit.title|html}" size="40" /></td>
			</tr>
			<tr>
				<th>缩略图：</th>
				<td><input name="thumb" type="file" value="" size="28" /><input name="_thumb" type="hidden" value="{$edit.thumb|html}"/>{if $edit.thumb}<br/><img src="{RES_UPLOAD_URL}{$edit.thumb}"/>{/if}</td>
			</tr>
			<tr>
				<th>文章内容：</th>
				<td><textarea id="ckeditor" name="content">{$edit.content|text}</textarea><label for="ckeditor"/></td>
			</tr>
			<tr>
				<th>来源：</th>
				<td><input name="source" type="text" value="{$edit.source|html}" size="4" /></td>
			</tr>
			<tr>
				<th>推荐：</th>
				<td><input name="recommended" type="radio" value="1"{if $edit.recommended} checked{/if}/>是&nbsp;<input name="recommended" type="radio" value="0"{if !$edit.recommended} checked{/if}/>否</td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$edit.order}" size="4" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<th>SEO标题：</th>
				<td><input name="seo[title]" type="text" value="{$edit.seo.title|html}" size="40" /></td>
			</tr>
			<tr>
				<th>SEO关键词：</th>
				<td><input name="seo[keywords]" type="text" value="{$edit.seo.keywords|html}" size="30" /></td>
			</tr>
			<tr>
				<th>SEO描述：</th>
				<td><input name="seo[description]" type="text" value="{$edit.seo.description|html}" size="50" /></td>
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
CKEDITOR.instances={};
$('#ckeditor').ckeditor(function(){
	$('#editform').validate({
		rules:{
			cat_id:{
				required:true,
				min:1
			},
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
			cat_id:{min:'请选择'},
			content:{
				required:'至少得写点什么吧',
				minlength:'写的太少啦，再加20字如何！'
			}
		}
	});
	var list=$('#editform .tab_title li');
	list.click(function(){
		var i=list.removeClass('active').index(this);
		$(this).addClass('active');
		$('#editform .tab_body>tbody').hide().eq(i).show();
		$('#editform').getWindow().resize();
	}).eq(0).click();
}).ckeditorGet();
$('#editform select[name=cat_id]').staged('{link ctrl=category method=json type=article}',{val:{$edit.cat_id}});
</script>