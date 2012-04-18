{template space/header}

<dl class="cat product">
	<dd>
	{loop M('user.picture')->get_list_by_where($UID,4,true) $r}
		<p>
			<a href="{RES_UPLOAD_URL}{$r.url}" title="{$r.title}" target="blank"><img src="{thumb RES_UPLOAD_DIR.$r.url,200,140,false}" alt="{$r.title}"/></a>
			<b>{$r.remark}</b>
		</p>
	{/loop}
		{pages method=picture uid=$UID}
	</dd>
</dl>

{template space/footer}