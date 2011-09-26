{template user/header}
<div class="bwp">
	<div id="nv">
		<h3><span>快捷功能</span></h3>
		<p><a href="{link ctrl=user method=welcome}">欢迎页面</a></p>
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
{template user/footer}
