{template header}
<div class="contDiv">
	{template sidecat}
	<div class="fr wh740">
		<div class="titleRt">
			<div class="fl ttLtDiv"><img src="{SKIN_URL}images/rt_{$category.cat_path}.jpg" alt="{$category.cat_name}"/></div>
			<div class="fr ttRtDiv gray">您的位置：<a href="{SITE_URL}">网站首页</a>{loop M('category')->catpos($catid) $id $r} &gt;&gt; <a href="{link method=category id=$id}">{$r.cat_name}</a>{/loop}</div>
			<div class="clear"></div>
		</div>
		<div class="contRtDiv">
			<ul class="rtcontUl">
			{loop M('article')->get_list_by_where(M('category')->get_child($catid),10,true) $id $r}
				<li>
					<span class="fr">{date 'Y-m-d H:i',$r.addtime}</span>
					<a target="_blank" href="{link method=article id=$id}" title="{$r.title}">{$r.title}</a>
				</li>
			{/loop}
			</ul>
			<div class="clear"></div>
			{pages method=category id=$catid}
		</div>
	</div>
	<div class="clear"></div>
</div>
{template footer}