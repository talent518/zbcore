{template header}
<div class="contDiv">
	<div class="fl wh210">
		<div><img src="{SKIN_URL}images/about.jpg" alt="{php echo $page['path_title']?$page['path_title']:$page['title']}"/></div>
		<div class="ltbkDiv">
			<div class="anLt">
			{loop M('page')->get_list_by_where('cat_id='.$catid) $r}
				<a href="{link method=page id=$r.page_id}" title="{$r.page_title}"{if $r.page_id==$page.page_id} style="width:188px;height:30px;color:#fff;font-weight:bold;background:url({SKIN_URL}images/ltnav11.jpg) no-repeat"{/if}>{$r.title}</a>
			{/loop}
			</div>
		</div>
	{if 0}
		<div class="mt8">
			<div class="tt06">
				<a href=""class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
				<h2>股市聚焦</h2>
				<div class="clear"></div>
			</div>
			<div class="cont06">
				<ul>
					<li><a href="">厦门钨业澄清3000亿钨矿没关系</a></li>
					<li><a href="">厦门钨业回应最大钨矿传闻实涉及“</a></li>
					<li><a href="">国务院：商业银行资本管理</a></li>
					<li><a href="">A股交易费用下调 券商增利20亿元</a></li>
					<li><a href="">广东公布重点污染环保信用榜</a></li>
					<li><a href="">首批中小企业私募债启航 拟发行率9.5%</a></li>
					<li><a href="">楼市回暖难解资金饥渴 首开股</a></li>
					<li><a href="">首批中小企业私募债启航 拟发行率</a></li>
				</ul>
			</div>
		</div>
	{/if}
	</div>
	<div class="fr wh740">
		<div class="titleRt">
			<div class="fl ttLtDiv"><img src="{SKIN_URL}images/{$page.page_name}.jpg" alt=""/></div>
			<div class="fr ttRtDiv gray">您的位置：<a href="{SITE_URL}">网站首页</a> &gt;&gt; <a href="{link method=category id=$category.cat_id}">{$category.cat_name}</a> &gt;&gt; {$page.title}</div>
			<div class="clear"></div>
		</div>
		<div class="contRtDiv html_view">{$page.page_content}</div>
	</div>
	<div class="clear"></div>
</div>
{template footer}