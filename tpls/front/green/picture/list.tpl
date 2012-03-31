{template header}
<div class="wp">
	<div class="scat">
		{template sidecat}
	</div>
	<div class="pic_list mcat">
		{loop M('picture')->get_list($catid,15) $r}
			<p>
				<a href="{link method=picture id=$r.pic_id}" title="{$r.title}"><img src="{thumb RES_UPLOAD_DIR.$r.url,200,140,true}" alt="{$r.title}"/></a>
				<b><a href="{link method=picture id=$r.pic_id}" title="{$r.title}">{$r.title}</a></b>
			</p>
		{/loop}
		<div class="cf"></div>
		{pages method=category id=$catid}
	</div>
</div>
{template footer}