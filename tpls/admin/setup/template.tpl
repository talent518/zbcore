<h1 class="head">网站配置</h1>
<form id="templateform" class="formtable" action="{link ctrl=setup method=template}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>后台皮肤：</th>
				<td>
					<select name="template[adminskin]">
					{loop $adminskins $skin}
						<option value="$skin"{if $template.adminskin==$skin} selected{/if}>$skin</option>
					{/loop}
					</select>
				</td>
			</tr>
			<tr>
				<th>前台模版：</th>
				<td>
					<select id="fronttpl" name="template[fronttpl]">
					{loop $fronttpls $tpl}
						<option value="$tpl"{if $template.fronttpl==$tpl} selected{/if}>$tpl</option>
					{/loop}
					</select>
				</td>
			</tr>
			<tr>
				<th>前台皮肤：</th>
				<td>
					<select id="frontskin" name="template[frontskin]">
					{loop $frontskins[$template.fronttpl] $skin}
						<option value="$skin"{if $template.frontskin==$skin} selected{/if}>$skin</option>
					{/loop}
					</select>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="templatesubmit" type="submit" value="提交"/><input name="templatehash" type="hidden" value="$templatehash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#templateform').validate();
var frontskins={};
{loop $frontskins $tpl $skin}
frontskins['$tpl']={$skin|js};
{/loop}
$('#fronttpl').change(function(){
	$('#frontskin').empty();
	var skins=frontskins[$(this).val()],skin;
	for(var i in skins){
		skin=skins[i];
		$('#frontskin').append(sprintf('<option value="%s">%s</option>',skin,skin));
	}
});
</script>