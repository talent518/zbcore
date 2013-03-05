{if PAGES>1}
	<div class="pages">
	{if APAGE>1}
		<a href="{page|1}">首页</a>&nbsp;
		{if ($page=APAGE-1)>1}
			<a href="{page|$page}">上一页</a>&nbsp;
		{/if}
	{/if}
		{var $b=APAGE-5>0?APAGE-5:1;}
		{var $e=$b+9>PAGES?PAGES:$b+9;}
		{var $b=$e-9>0?$e-9:1;}
		{loops $i $b $e}
			{if $i!=APAGE}[<a href="{page|$i}">$i</a>]{else}<b>$i</b>{/if}&nbsp;
		{/loops}
	{if APAGE<PAGES}
		{if ($page=APAGE+1)<PAGES}
			<a href="{page|$page}">下一页</a>&nbsp;
		{/if}
		{var $lpage=PAGES;}
		<a href="{page|$lpage}">尾页</a>
	{/if}
	</div>
{/if}