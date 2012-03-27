<form id="addform" class="formtable" action="{link ctrl=category method=add}" method="post">
	<ul class="tab_title">
		<li class="active">基本选项</li>
		<li>SEO设置</li>
		<li class="tpl">模板设置</li>
	</ul>
	<table class="tab_body" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>栏目类型：</th>
				<td>{if $add.ctype}<input name="ctype" type="hidden" value="{$add.ctype}"/>{else}<select name="ctype">
					<option value="">请选择</option>
				{loop M('category')->ctypes $k $v}
					<option value="$k">$v</option>
				{/loop}
				</select>{/if}</td>
			</tr>
			<tr>
				<th>上级栏目：</th>
				<td><select name="pid" names="pids"><option value="0">一级栏目</option></select></td>
			</tr>
			<tr>
				<th>栏目名称：</th>
				<td><input name="cat_name" type="text" value="{$add.cat_name|html}" size="20" /></td>
			</tr>
			<tr>
				<th>目录名称：</th>
				<td><input name="cat_path" type="text" value="{$add.cat_path|html}" size="20" /></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="corder" type="text" value="{$add.corder}" size="4" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<th>SEO标题：</th>
				<td><input name="cseo[title]" type="text" value="{$edit.cseo.title|html}" size="30" /></td>
			</tr>
			<tr>
				<th>SEO关键词：</th>
				<td><input name="cseo[keywords]" type="text" value="{$edit.cseo.keywords|html}" size="30" /></td>
			</tr>
			<tr>
				<th>SEO描述：</th>
				<td><textarea name="cseo[description]" cols="40" rows="3">{$edit.cseo.description|html}</textarea></td>
			</tr>
		</tbody>
		<tbody class="tpl">
		{if $add.ctype}
			{loop M('category')->ctpls[$add.ctype] $k $v}
			<tr class="$t">
				<th>$v：</th>
				<td><select name="{$t}_tpl[{$k}]">
					<option value="{$k}">默认模板</option>
				</select></td>
			</tr>
			{/loop}
		{else}
			{loop M('category')->ctpls $t $r}
				{loop $r $k $v}
				<tr class="$t">
					<th>$v：</th>
					<td><select name="{$t}_tpl[{$k}]">
						<option value="{$k}">默认模板</option>
					</select></td>
				</tr>
				{/loop}
			{/loop}
		{/if}
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
(function($){
	$('#addform').validate({
		rules:{
			ctype:{
				required:true
			},
			pid:{
				required:true,
				integer:true
			},
			cat_name:{
				required:true,
				maxlength:20,
				chinese:true
			},
			corder:{
				integer:true
			}
		}
	});

	var list=$('#addform .tab_title li');
	list.click(function(){
		var i=list.removeClass('active').index(this);
		$(this).addClass('active');
		$('#addform .tab_body tbody').hide().eq(i).show();
		$('#addform').getWindow().resize();
	}).eq(0).click();

{if $add.pid}
	$('#addform select[name=pid]').staged('{link ctrl=category method=json type=$add.ctype}',{val:{$add.pid}});
{else}
	$('#addform select[name=ctype]').change(function(){
		var val=$(this).val();
		if(val.length){
			$('#addform .tab_title .tpl').show();
			$('#addform .tab_body .tpl tr').hide().filter('.'+val).show();
			$('#addform select[name=pid]').staged('{link ctrl=category method=json type=TYPE}'.replace('TYPE',val),{val:{$add.pid}});
		}else{
			$('#addform .tab_title .tpl').hide();
		}
		$('#addform').getWindow().resize();
	}).change();
{/if}
})(jQuery);
</script>
