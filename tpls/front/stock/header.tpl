{if !IN_AJAX}
	{template global_header}
<div class="bigDiv">
    <div class="headDiv">
	    <div class="fl ml10"><img src="{SKIN_URL}images/logo.jpg" alt=""/></div>
		<div class="fr"><img src="{SKIN_URL}images/topRt.jpg" alt=""/></div>
		<div class="clear"></div>    
	</div>
	<div class="menuDiv">
	    <a href="{SITE_URL}"{if IN_CTRL=='index' && IN_METHOD=='index'} class="active"{/if}>网站首页</a>
	{loop M('category')->get_list_by_where(0) $i $r}
		<a href="{link ctrl=index method=category id=$i}"{if GET('method')=='category' && $page.page_id!=5 && in_array($catid,M('category')->get_child($i))} class="active"{/if} title="{$r.cat_name}">{$r.cat_name}</a>
	{/loop}
		<a href="{link ctrl=comment}"{if GET('ctrl')=='comment'} class="active"{/if} title="用户评论">用户评论</a>
		<a href="{link ctrl=index method=page id=5}"{if $page && $page.page_id==5} class="active"{/if} title="联系我们">联系我们</a>
	</div>
	<div class="tyDiv"></div>
	{if $catid}
		{if $adcode=M('ad')->show("main-nav-banner-$catid")}
			<div class="adDiv">$adcode</div>
		{else}
			<php>{if $MEMBER.ismanage}<div id="ad" class="wp" style="text-align:center;font-size:30px;font-weight:bold;">&#123;ad:main-nav-banner-$catid}</div>{/if}</php>
		{/if}
	{/if}
{/if}
