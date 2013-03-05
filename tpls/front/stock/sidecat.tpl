<div class="fl wh210">
{var $pcate=($category.pid?M('category')->get($category.pid):$category);}
{if $pcate && ($cates=M('category')->get_list_by_where($pcate.cat_id))}
	<div><img src="{SKIN_URL}images/lt_{$pcate.cat_path}.jpg" alt="{$pcate.cat_name}"/></div>
	<div class="ltbkDiv">
		<div class="anLt">
		{loop $cates $id $r}
			<a href="{link ctrl=index method=category id=$id}"{if $id==$catid} style="width:188px;height:30px;color:#fff;font-weight:bold;background:url({SKIN_URL}images/ltnav11.jpg) no-repeat"{/if}>{$r.cat_name}</a>
		{/loop}
		</div>
	</div>
	<div class="mt8">
{else}
	<div class="mt8" style="margin-top:0px;">
{/if}
		<div class="tt06">
			<a href=""class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
			<h2>股市聚焦</h2>
			<div class="clear"></div>
		</div>
		<div class="cont06">
			<ul>
			{loop M('article')->get_list_by_where(12,8) $art_id $r}
				<li><a href="{link ctrl=index method=article id=$art_id}" title="{$r.title}">{$r.title}{if $r.addtime+86400>time()}<img src="{SKIN_URL}images/tb04.gif" alt=""/>{/if}</a></li>
			{/loop}
			</ul>
		</div>
	</div>
</div>
