{template space/header}

<dl class="cat">
	<dt>{$article.title}</dt>
	<dd>
		<p class="stitle">
			发布时间：<span>{date 'Y年m月d日',$article.addtime}</span>&nbsp-&nbsp;
			浏览次数：<b class="numeric">{$article.views}</b>
		</p>
		<div class="content">{$article.content}</div>
	</dd>
</dl>

{template space/footer}