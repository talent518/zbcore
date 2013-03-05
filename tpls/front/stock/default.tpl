{template header}
<div class="pic_list indpic">
	{loop M('picture')->get_list_by_position(1,4) $r}
		<p>
			<a href="{link method=picture id=$r.pic_id}" title="{$r.title}"><img src="{thumb RES_UPLOAD_DIR.$r.url,200,140,true}" alt="{$r.title}"/></a>
			<b><a href="{link method=picture id=$r.pic_id}" title="{$r.title}">{$r.title}</a></b>
			<span>{$r.remark}</span>
		</p>
	{/loop}
	</dd>
</div>
{template footer}