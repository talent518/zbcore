{template space/header}

<dl class="cat product">
	<dd>
	{loop M('user.product')->get_list_by_where($UID,4,true) $r}
		<p>
			<a href="{link method=product uid=$UID id=$r.prod_id}" title="{$r.title}"><img src="{thumb RES_UPLOAD_DIR.$r.url,200,140,false}" alt="{$r.title}"/></a>
			<b><a href="{link method=product uid=$UID id=$r.prod_id}" title="{$r.title}">{$r.title}</a></b>
			{$r.price|money}
		</p>
	{/loop}
		{pages method=product uid=$UID}
	</dd>
</dl>

{template space/footer}