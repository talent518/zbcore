<form id="editform" class="formtable" action="{link ctrl=category method=edit id=$id}" method="post">
	<ul class="tab_title">
		<li class="active">基本选项</li>
		<li>SEO设置</li>
		<li class="tpl">模板设置</li>
	</ul>
	<table class="tab_body" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>栏目类型：</th>
				<td>{if $edit.pid || $edit.counts}<b>{php echo $ctypes[$edit[ctype]];}</b><input name="ctype" type="hidden" value="{$edit.ctype}"/>{else}<select name="ctype">
					<option value="">请选择</option>
				{loop $ctypes $k $v}
					<option value="$k"{if $k==$edit.ctype} selected{/if}>$v</option>
				{/loop}
				</select>{/if}</td>
			</tr>
			<tr>
				<th>上级栏目：</th>
				<td><select name="pid" names="pids"><option value="0">一级栏目</option></select></td>
			</tr>
			<tr>
				<th>栏目名称：</th>
				<td><input name="cat_name" type="text" value="{$edit.cat_name|html}" size="20" /></td>
			</tr>
			<tr>
				<th>目录名称：</th>
				<td><input name="cat_path" type="text" value="{$edit.cat_path|html}" size="20" /></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="corder" type="text" value="{$edit.corder}" size="4" /></td>
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
		{if $edit.pid || $edit.counts}
			{loop $ctpls[$edit.ctype] $k $v}
			<tr class="{$edit.ctype}">
				<th>$v：</th>
				<td><input name="{$edit.ctype}_tpl[{$k}]" type="text" value="{php echo $edit[ctpl][$k];}"></td>
			</tr>
			{/loop}
		{else}
			{loop $ctpls $t $r}
				{loop $r $k $v}
				<tr class="$t">
					<th>$v：</th>
					<td><input name="{$t}_tpl[{$k}]" type="text" value="{if $t==$edit.ctype}{php echo $edit[ctpl][$k];}{else}{$k}{/if}"/></td>
				</tr>
				{/loop}
			{/loop}
		{/if}
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
(function($){
	$('#editform').validate({
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

	var list=$('#editform .tab_title li');
	list.click(function(){
		var i=list.removeClass('active').index(this);
		$(this).addClass('active');
		$('#editform .tab_body tbody').hide().eq(i).show();
		$('#editform').getWindow().resize();
	}).eq(0).click();

{if $edit.pid || $edit.counts}
	$('#editform select[name=pid]').staged('{link ctrl=category method=json type=$edit.ctype}',{val:{$edit.pid},not:{$edit.cat_id}});
{else}
	$('#editform select[name=pid]').staged('{link ctrl=category method=json type=$edit.ctype}',{val:{$edit.pid},not:{$edit.cat_id}});
	$('#editform select[name=ctype]').change(function(){
		var val=$(this).val();
		if(val.length){
			$('#editform .tab_title .tpl').show();
			$('#editform .tab_body .tpl tr').hide().filter('.'+val).show();
			$('#editform select[name=pid]').staged('{link ctrl=category method=json type=TYPE}'.replace('TYPE',val),{val:{$edit.pid},not:{$edit.cat_id}});
		}else{
			$('#editform .tab_title .tpl').hide();
		}
		$('#editform').getWindow().resize();
	}).change();
{/if}
})(jQuery);
</script>