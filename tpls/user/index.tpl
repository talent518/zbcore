{template header}
<script type="text/javascript" src="{RES_SCRIPT_URL}jquery-uploadifive.js"></script>
<script type="text/javascript" src="{RES_URL}ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="{RES_URL}ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="{RES_URL}ckfinder/ckfinder.js"></script>
<div class="bwp">
	<div id="nv">
		<h3><span>快捷功能</span></h3>
		<p><a href="{link method=welcome}">欢迎页面</a></p>
	{if $MEMBER.iscorp}
		<p><a href="{link ctrl=picture}">图片管理</a></p>
		<p>
			<em><a href="{link ctrl=product method=add}" class="add" title="添加产品">添加</a></em>
			<a href="{link ctrl=product}">产品管理</a>
		</p>
		<p>
			<em><a href="{link ctrl=article method=add}" class="add" title="添加文章">添加</a></em>
			<a href="{link ctrl=article}">文章管理</a>
		</p>
	{/if}
	</div>
	<div id="bd">
	</div>
	<script type="text/javascript">
	$('#nv a:not(.add)').click(function(){
		$('#bd').load(this.href);
		$('#nv p').removeClass('active');
		$(this).parent('p').addClass('active');
		return false;
	}).eq(0).click();
	$('#nv a.add').window();
	</script>
</div>
{template footer}
