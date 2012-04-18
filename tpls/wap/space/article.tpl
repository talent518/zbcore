{template space/header}

<dl class="cat article">
	<dd>
	{loop M('user.article')->get_list_by_where($UID,8,true) $r}
		<p><em>{date 'Y-m-d',$r.addtime}</em><a href="{link method=article uid=$UID id=$r.art_id}">{$r.title}</a></p>
	{/loop}
		{pages method=article uid=$UID}
	</dd>
</dl>

{template space/footer}