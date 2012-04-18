{template space/header}

<dl class="cat">
	<dt>{$product.title}</dt>
	<dd>
		<p class="stitle">
			价格：<span>{$product.price|money}</span>&nbsp-&nbsp;
			发布时间：<span>{date 'Y年m月d日',$product.addtime}</span>&nbsp-&nbsp;
			浏览次数：<b class="numeric">{$product.views}</b>
		</p>
		<div class="remark">{$product.remark}</div>
		<ul class="pictures">
			{loop M('user.product.image')->get_list_by_where($product.prod_id,4,true) $pic}
			<li>
				<a href="{RES_UPLOAD_URL}{$pic.url}" target="_blank"><img src="{thumb RES_UPLOAD_DIR.$pic.url,200,140,false}"/></a>
				<p><b>{$pic.remark}</b>&nbsp;<span>文件大小：{php echo formatsize($pic['size']);}</span>&nbsp;</p>
			</li>
			{/loop}
		</ul>
	</dd>
</dl>

{template space/footer}