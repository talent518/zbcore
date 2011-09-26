<h1 class="head">
	<a href="{link ctrl=db method=backup}" class="add">备份</a>
	数据库备份与还原
</h1>
<form id="cacheform" class="formtable">
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<tbody>
			<tr>
				<th class="l">sql文件</th>
				<th>操作</th>
			</tr>
			{loop $sqls $file}
			<tr>
				<td class="l"><a href="/cache/db/{$file}.sql" target="_blank">{$file}</a></td>
				<td>
					<a href="{link ctrl=db method=restore fn=$file}" class="add">还原</a>
					<span class="split">|</span>
					<a href="{link ctrl=db method=delete fn=$file}" class="add">删除</a>
				</td>
			</tr>
			{/loop}
		</tbody>
	</table>
</form>
<script type="text/javascript">
$('#bd a.add').click(function(){
	$.ajax({url:this.href,data:{},type:'GET',success:function(xml){
		$.sXML(xml);
	}});
	return false;
});
</script>