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
			<div class="viewDiv">
				<h2 class="viewSubject">{$article.title}</h2>
				<div class="viewTitle taR">发布时间：{date 'Y年m月d日',$article.addtime}&nbsp-&nbsp;阅读次数：<span id="viewArticle">{$article.views}</span>&nbsp-&nbsp;来源：<span>{if $article.source}{$article.source|html}{else}{$_SERVER.HTTP_HOST}{/if}</span></div>
				<div class="viewCont html_view">{$article.content}</div>
				{php $next=M('article')->get_next($article['art_id']);$prev=M('article')->get_prev($article['art_id']);}

				<hr/>

				<span class="next{if !$next} disabled{/if}">
					{if $next}<a href="{link method=article id=$next.art_id}" title="{$next.title}">{$next.title}</a><b>{else}无<b>{/if}【下一篇】</b>
				</span>
				<span class="prev{if !$prev} disabled{/if}">
					<b>【上一篇】{if $prev}</b>
					<a href="{link method=article id=$prev.art_id}" title="{$prev.title}">{$prev.title}</a>{else}</b>无{/if}
				</span>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript" src="{link method=article.view id=$article.art_id vid=viewArticle}"></script>
{template footer}