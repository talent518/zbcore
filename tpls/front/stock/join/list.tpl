{template header}
<div class="contDiv">
	{template sidecat}
	<div class="fr wh740">
		<div class="titleRt">
			<div class="fl ttLtDiv"><img src="{SKIN_URL}images/rt_joinus.jpg" alt=""/></div>
			<div class="fr ttRtDiv gray">您的位置：<a href="{SITE_URL}">网站首页</a> &gt;&gt; <a href="{link ctrl=join}">用户评论</a></div>
			<div class="clear"></div>
		</div>
		<div class="contRtDiv joinList">
		{loop M('join')->get_list_by_where(null,10,true) $jid $r}
			<p><span class="gray">姓名:</span>{$r.realname}&nbsp;&nbsp;<span class="gray">手机号:</span>{$r.mobile|text}&nbsp;&nbsp;<span class="gray">QQ号:</span>{$r.qq|text}&nbsp;&nbsp;<span class="gray">评论时间:</span>{date 'Y-m-d H:i',$r.dateline}&nbsp;&nbsp;<a href="{link ctrl=join method=drop id=$jid}" style="color:blue" onclick="return confirm('你确定要删除吗？');">删除</a></p>
		{/loop}
			<div class="clear"></div>
			{pages ctrl=join}
		</div>
	</div>
	<div class="clear"></div>
</div>
{template footer}