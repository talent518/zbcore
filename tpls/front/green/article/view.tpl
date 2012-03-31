{template header}
<div class="scat">{template sidecat}</div>
<div class="art_view mcat">
	<h1 class="title">{$article.title}</h1>
	<h2 class="stitle">发布时间：<span>{date 'Y年m月d日',$article.addtime}</span>&nbsp-&nbsp;浏览次数：<span id="viewArticle">{$article.views}</span>&nbsp-&nbsp;来源：<span>{if $article.source}{$article.source|html}{else}{$_SERVER.HTTP_HOST}{/if}</span></h2>
	<div class="content">{$article.content}</div>
	{php $next=M('article')->get_next($article['art_id']);$prev=M('article')->get_prev($article['art_id']);}

	<hr/>

	<span class="next{if !$next} disabled{/if}">
		{if $next}<a href="{link method=article id=$next.art_id}" title="{$next.title}">{$next.title}</a><b>{else}无<b>{/if}【下一篇】</b>
	</span>
	<span class="prev{if !$prev} disabled{/if}">
		<b>【上一篇】{if $prev}</b>
		<a href="{link method=article id=$prev.art_id}" title="{$prev.title}">{$prev.title}</a>{else}</b>无{/if}
	</span>
</div>
<script type="text/javascript" src="{link method=article.view id=$article.art_id vid=viewArticle}"></script>
{template footer}