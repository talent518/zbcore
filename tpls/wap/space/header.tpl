{template header}
<div id="hd">
	<h2>{$DATUM.corpname}</h2>
	<p>{var $actives=array(IN_METHOD=>' class="active"');}
		<a{$actives.index} href="{link method=index uid=$UID}">首页</a><span class="split">|</span>
		<a{$actives.picture} href="{link method=picture uid=$UID}">图片</a><span class="split">|</span>
		<a{$actives.product} href="{link method=product uid=$UID}">产品</a><span class="split">|</span>
		<a{$actives.article} href="{link method=article uid=$UID}">文章</a><span class="split">|</span>
		<a{$actives.about} href="{link method=about uid=$UID}">关于我们</a><span class="split">|</span>
		<a{$actives.contact} href="{link method=contact uid=$UID}">联系我们</a>
	</p>
</div>
<div id="wp">