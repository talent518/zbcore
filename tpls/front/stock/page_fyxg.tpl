{if PAGES>1}
<div class="fyxg mt8">
{if APAGE>1}
	{if APAGE-1>1}
		<div class="fl mt5 b nbrT0"><a href="{page|1}">首页</a></div>
	{/if}
	{var $page=APAGE-1;}
	<div class="fl mt5 b nbrT0"><a href="{page|$page}">上一页</a></div>
{/if}
	<div class="fl mt2">
		<ul>
		{var $b=APAGE-5>0?APAGE-5:1;}
		{var $e=$b+9>PAGES?PAGES:$b+9;}
		{var $b=$e-9>0?$e-9:1;}
		{loops $i $b $e}
			<li{if $i==APAGE} class="dqztDiv"{/if}><a href="{page|$i}">$i</a></li>
		{/loops}
		</ul>
	</div>
{if APAGE<PAGES}
	{var $page=APAGE+1;}
	<div class="fl mt5 b nbrB1"><a href="{page|$page}">下一页</a></div>
	{if APAGE+1<PAGES}
		<div class="fl mt5 b nbrB1"><a href="{page|PAGES}">尾页</a></div>
	{/if}
{/if}
	<div class="clear"></div>
</div>
{/if}