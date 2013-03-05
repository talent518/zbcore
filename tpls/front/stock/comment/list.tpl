{template header}
<div class="contDiv">
	{template sidecat}
	<div class="fr wh740">
		<div class="titleRt">
			<div class="fl ttLtDiv"><img src="{SKIN_URL}images/rt_yhpl.jpg" alt=""/></div>
			<div class="fr ttRtDiv gray">您的位置：<a href="{SITE_URL}">网站首页</a> &gt;&gt; <a href="{link ctrl=comment}">用户评论</a></div>
			<div class="clear"></div>
		</div>
		<div class="contRtDiv commentList">
		{loop M('comment')->get_list_by_where(null,10,true) $cid $r}
			<dl>
				<dt>
					<img src="{$r.avatar}"/>
					<b>{$r.nickname}</b>
				</dt>
				<dd>
					<h4>{$r.subject|text}</h4>
					<p><span class="gray">E-Mail:</span>{$r.email}&nbsp;&nbsp;<span class="gray">电话:</span>{$r.phone|text}&nbsp;&nbsp;<span class="gray">评论时间:</span>{date 'Y-m-d H:i',$r.dateline}&nbsp;&nbsp;<a href="{link ctrl=comment method=drop id=$cid}" onclick="return confirm('你确定要删除吗？');">删除</a></p>
					<div>{$r.content|html}</div>
				</dd>
				<dd class="clear"></dd>
			</dl>
		{/loop}
			<div class="clear"></div>
			{pages ctrl=comment}
		</div>
	</div>
	<div class="clear"></div>
</div>
{template footer}