{template header}
{template sidecat}
<div class="pic_view mcat">
	<h1 class="title">{$picture.title}</h1>
	<h2 class="stitle">发布时间：<span>{date 'Y年m月d日',$picture.addtime}</span>&nbsp-&nbsp;浏览次数：<span id="viewPicture">{$picture.views}</span></h2>
	<div class="remark">{$picture.remark}</div>
	<dl class="pictures">
		{loop $pictures $pic}
		<dd>
			<a href="{RES_UPLOAD_URL}{$pic.url}"><img src="{RES_UPLOAD_URL}{$pic.url}"/></a>
			<p><b>{$pic.remark}</b>&nbsp;<span>文件大小：{php echo formatsize($pic['size']);}</span>&nbsp;</p>
		</dd>
		{/loop}
	</dl>
	{php $next=M('picture')->get_next($picture['pic_id']);$prev=M('picture')->get_prev($picture['pic_id']);}

	<hr/>

	<span class="next{if !$next} disabled{/if}">
		{if $next}<a href="{link method=picture id=$next.pic_id}" title="{$next.title}">{$next.title}</a><b>{else}无<b>{/if}【下一篇】</b>
	</span>
	<span class="prev{if !$prev} disabled{/if}">
		<b>【上一篇】{if $prev}</b>
		<a href="{link method=picture id=$prev.pic_id}" title="{$prev.title}">{$prev.title}</a>{else}</b>无{/if}
	</span>
</div>
<script type="text/javascript" src="{link method=picture.view id=$picture.pic_id vid=viewPicture}"></script>
<script type="text/javascript">
	$('.pictures img').load(function(){
		var W=$('.pic_view').width(),w=$(this).width(),h=$(this).height();
		if(w>W){
			$(this).width(W).height(Math.floor(W/w*h));
		}
		$('<span/>').text('尺寸：'+w+'x'+h+'px').appendTo($(this).parent().next());
	}).each(function(){
		if($(this).attr('complete'))
			$(this).load();
	});
</script>
{template footer}
