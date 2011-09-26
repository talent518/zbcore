{if PAGES>1}<div class="pages">
{if APAGE>1}
	{var $page=APAGE-1}
	<a href="{page|$page}">上一页</a>&nbsp;
{/if}
	{loops $i PAGES}
		{if $i!=APAGE}[<a href="{page|$i}">$i</a>]{else}<b>$i</b>{/if}&nbsp;
	{/loops}
{if APAGE<PAGES}
	{var $page=APAGE+1}
	<a href="{page|$page}">下一页</a>
{/if}
</div>
<script type="text/javascript">
$('.pages a').click(function(){
	$('#bd').load(this.href);
	return false;
});
</script>
{/if}