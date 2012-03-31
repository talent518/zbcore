{if !IN_AJAX}
	{template global_header}
	<div class="wp"><div id="tp">
		<span class="fr"><b>联系智绘：</b><font face="Arial">13641406697</font></span>
		<font face="Arial"><b>智绘365</b> 的个人空间</font>
	</div></div>
	<div id="hd" class="wp">
		<h2><a href="{link method=default}"><img src="{SKIN_URL}images/inlogo.jpg"/></a></h2>
		<ol>
			<li{if GET('method')!='category'} class="active"{/if}><a href="{link method=default}" title="网站首页">网站首页</a></li>
		{loop M('category')->get_list_by_where('pid=0') $i $r}
			<li{if GET('method')=='category' && in_array($catid,M('category')->get_child($i))} class="active"{/if}><a href="{link method=category id=$i}" title="{$r.cat_name}">{$r.cat_name}</a></li>
		{/loop}
		</ol>
		<p class="cf">ZHui365 Wisdom draw every day</p>
	</div>
	<div id="ad" class="wp"><a href="#"><img src="{SKIN_URL}images/ad.jpg"/></a></div>
	<div id="wp" class="wp">
{/if}
