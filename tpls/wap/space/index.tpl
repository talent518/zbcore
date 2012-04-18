{template space/header}

<dl class="cat product">
	<dt>最新产品</dt>
	<dd>
	{loop M('user.product')->get_list_by_where($UID,4) $r}
		<p>
			<a href="{link method=product uid=$UID id=$r.prod_id}" title="{$r.title}"><img src="{thumb RES_UPLOAD_DIR.$r.url,200,140,false}" alt="{$r.title}"/></a>
			<b><a href="{link method=product uid=$UID id=$r.prod_id}" title="{$r.title}">{$r.title}</a></b>
			{$r.price|money}
		</p>
	{/loop}
	</dd>
</dl>
<p class="ws">&nbsp;</p>
<dl class="cat article">
	<dt>最新文章</dt>
	<dd>
	{loop M('user.article')->get_list_by_where($UID,4) $r}
		<p><em>{date 'Y-m-d',$r.addtime}</em><a href="{link method=article uid=$UID id=$r.art_id}">{$r.title}</a></p>
	{/loop}
	</dd>
</dl>

{template space/footer}