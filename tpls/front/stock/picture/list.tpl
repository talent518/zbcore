{template header}
<div class="contDiv">
	{template sidecat}
	<div class="fr wh740">
		<div class="titleRt">
			<div class="fl ttLtDiv"><img src="{SKIN_URL}images/tgtd.jpg" alt="{$category.cat_name}"/></div>
			<div class="fr ttRtDiv gray">您的位置：<a href="{SITE_URL}">网站首页</a>{loop M('category')->catpos($catid) $id $r} &gt;&gt; <a href="{link method=category id=$id}">{$r.cat_name}</a>{/loop}</div>
			<div class="clear"></div>
		</div>
		<div class="contRtDiv">
		{loop M('picture')->get_list_by_where(4,10,true) $pic_id $r}
			<dl class="fl">
				<dt><img src="{RES_UPLOAD_URL}{$r.url}" alt="{$r.title}"/></dt>
				<dd>
					<h4>{$r.title}</h4>
					<div>{$r.remark}</div>
				</dd>
				<div class="clear"></div>
			</dl>
		{/loop}
			<div class="clear"></div>
			{pages method=category id=$catid}
		</div>
	</div>
	<div class="clear"></div>
</div>
{template footer}