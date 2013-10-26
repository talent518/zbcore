{if !IN_AJAX}
	{template global_header}
	<div id="tp" class="wp"><a href="{ROOT_URL}#home"><img src="{SKIN_URL}images/home.gif"/></a><a href="{ROOT_URL}#index"><img src="{SKIN_URL}images/index.gif"/></a></div>
	<div id="hd" class="wp">
		<h2><a href="{ROOT_URL}"><img src="{SKIN_URL}images/logo.png"/></a></h2>
		<ol>
		{var $navs=M('category')->get_list_by_where('pid=0');}
		{loop $navs $i $r}
			<li class="{$r.cat_path}{if GET('method')=='category' && in_array($catid,M('category')->get_child($i))} active{/if}"><a href="{link method=category id=$i}" title="{$r.cat_name}">{$r.cat_name}</a></li>
		{/loop}
		</ol>
	</div>
	{var $adcode=M('ad')->show("main-nav-banner-$catid");}
	{if $adcode}
		<div id="ad" class="wp">$adcode</div>
	{else}
		<php>{if $MEMBER.ismanage}<div id="ad" class="wp" style="text-align:center;font-size:30px;font-weight:bold;">&#123;ad:main-nav-banner-$catid}</div>{/if}</php>
	{/if}
	<div id="wp" class="wp">
{/if}
