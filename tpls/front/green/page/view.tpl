{template header}
<div id="pv" class="wp">
	<div id="nv">
	{loop M('page')->get_list_by_where('cat_id='.$catid) $r}
		<p{if $r.page_id==$page.page_id} class="active"{/if}><a href="{link method=page id=$r.page_id}" title="{$r.page_title}">{$r.title}</a></p>
	{/loop}
	</div>
	<div id="bd">
		<h1 class="title">{$page.page_title}</h1>
		<div class="content">{$page.page_content}</div>
	</div>
</div>
{template footer}