{template header}
<div class="wp">
	<div class="scat">
		{template sidecat}
	</div>
	<div class="art_list mcat">
	{$i=0;}
	{loop M('article')->get_list_by_where('cat_id='.$catid,20,true) $r}
		<p><em>{date 'Y-m-d',$r.addtime}</em><a href="{link method=article id=$r.art_id}">{$r.title}</a></p>
	{/loop}
		{pages method=category id=$catid}
	</div>
</div>
{template footer}