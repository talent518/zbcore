{template header}
<div id="indexBannerSlider" class="adDiv">{adp:index-main-nav-slide}</div>
<script type="text/javascript">
(function($){
	var i=0,timer=0,as=$('#indexBannerSlider a'),img=$('#indexBannerSlider img'),w=img.width(),h=img.height();
	if(as.size()>1){
		$('#indexBannerSlider').hover(function(){
			clearInterval(timer);
			timer=0;
		},function(){
			if(timer){
				return;
			}
			timer=setInterval(function(){
				i=(i+1)%as.size();
				as.css({zIndex:0}).fadeOut('slow').eq(i).css({zIndex:1}).fadeIn('slow');
			},3000);
		}).css({position:'relative',width:w,height:h}).mouseout();
		as.css({position:'absolute',left:0,top:0,width:'100%',height:'100%'}).hide().eq(0).show();
	}
})(jQuery);
</script>
<div class="contDiv">
	<div class="fl wh350">
		<div>
			<div class="tt01">
				<a href="{link ctrl=join}" class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
				<h2>申请加入</h2>
				<div class="clear"></div>
			</div>
			<div class="cont01">
				<form id="frmJoin" class="dlk" action="{link ctrl=join method=submit}" method="post">
					<img src="{SKIN_URL}images/dlzc.jpg" alt=""/>
					<div class="red ml5 mt5">提示：请直接输入以下联系方式即可加入</div>
					<p><label>姓　名：</label><input name="realname" type="text"/></p>
					<p><label>手机号：</label><input name="mobile" type="text"/></p>
					<p><label>Q　Q：</label><input name="qq" type="text"/></p>
					<div class="clear"></div>
					<p><button type="submit">点击加入</button><php>{if $MEMBER.ismanage}<button type="button" onclick="location.href='{link ctrl=join}';">查看列表</button>{else}<button type="reset">点击重置</button>{/if}</php></p>
					<div class="clear"><input name="joinsubmit" type="hidden" value="1"/><input name="joinhash" type="hidden" value="$joinhash"/></div>
				</form>
				<script type="text/javascript">
				$('#frmJoin').submit(function(){
					if($('input[name=realname]',this).val().length==0){
						alert('姓名不能为空！');
						$('input[name=realname]',this).focus();
						return false;
					}
					var mobile=$('input[name=mobile]',this).val();
					if(mobile.length==0){
						alert('电话不能为空！');
						$('input[name=mobile]',this).focus();
						return false;
					}
					if(!/^\d+$/.test(mobile)){
						alert('手机号不合法！');
						$('input[name=mobile]',this).focus();
						return false;
					}
					var qq=$('input[name=qq]',this).val();
					if(qq.length==0){
						alert('QQ不能为空！');
						$('input[name=qq]',this).focus();
						return false;
					}
					if(!/^\d+$/.test(qq)){
						alert('QQ号格式不正确！');
						$('input[name=qq]',this).focus();
						return false;
					}
				});
				</script>
			</div>
		</div>
		<div class="mt8">
			<div class="tt03">
				<a href=""class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
				<h2>历史战绩</h2>
				<div class="clear"></div>
			</div>
			<div class="cont03">{adp:lszj}</div>
		</div>
		<div class="mt8">
			<div class="tt01">
				<a href=""class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
				<h2>股民课堂</h2>
				<div class="clear"></div>
			</div>
			<div class="cont04">
			{loop M('article')->get_list_by_where(3,3) $art_id $r}
				<li>

					<a href="" title="{$r.title}"></a>
				</li>
				<dl>
					<dt><a href="{link method=article id=$art_id}" title="{$r.title}"><img src="{RES_UPLOAD_URL}{$r.thumb}" alt="{$r.title}"/></a></dt>
					<dd>
						<h4><a href="{link method=article id=$art_id}" title="{$r.title}">{$r.title}</a></h4>
						<div class="gray">{php $seo=unserialize($r['seo']);}{$seo.description}[{date 'Y-m-d',$r.addtime}]</div>
					</dd>
					<div class="clear"></div>
				</dl>
			{/loop}
			</div>
		</div>
	</div>
	<div class="fr wh610">
		<div>
			<div class="tt02">
				<a href="{link method=category id=2}" class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
				<h2>投资参考</h2>
				<div class="clear"></div>
			</div>
			<div class="cont02">
			{var $n=-1;}
			{loop M('category')->get_list_by_where(2,4) $id $r}
				<div class="fl wh290">
					<h3><a href="{link method=category id=$id}">{$r.cat_name}</a></h3>
					<ul>
					{loop M('article')->get_list_by_where($id,3) $art_id $r}
						<li><a href="{link method=article id=$art_id}" title="{$r.title}">{$r.title}{if $r.addtime+86400>time()}<img src="{SKIN_URL}images/tb04.gif" alt=""/>{/if}</a></li>
					{/loop}
					</ul>
				</div>
			{if (++$n)%2==1}
				<div class="clear"></div>
			{/if}
			{/loop}
			</div>
			<div class="clear"></div>
		</div>
		<div class="mt8">
			<div class="fl wh300">
				<div class="tt05">
					<a href=""class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
					<h2>股市聚焦</h2>
					<div class="clear"></div>
				</div>
				<div class="cont05 mt8">
					<ul>
					{loop M('article')->get_list_by_where(12,8) $art_id $r}
						<li>
							<span class="fr gray">[{date 'Y-m-d',$r.addtime}]</span>
							<a href="{link method=article id=$art_id}" title="{$r.title}">{$r.title}</a>
						</li>
					{/loop}
					</ul>
				</div>
			</div>
			<div class="fr wh300">
				<div class="tt05">
					<a href=""class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
					<h2>股票新闻</h2>
					<div class="clear"></div>
				</div>
				<div class="cont05 mt8">
					<ul>
					{loop M('article')->get_list_by_where(13,8) $art_id $r}
						<li>
							<span class="fr gray">[{date 'Y-m-d',$r.addtime}]</span>
							<a href="{link method=article id=$art_id}" title="{$r.title}">{$r.title}</a>
						</li>
					{/loop}
					</ul>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mt8">
			<div class="tt02">
				<a href=""class="fr mr5 mt5"><img src="{SKIN_URL}images/more.jpg" alt=""/></a>
				<h2>投顾团队</h2>
				<div class="clear"></div>
			</div>
			<div class="cont02">
			{loop M('picture')->get_list_by_where(4,4) $pic_id $r}
				<dl>
					<dt><img src="{RES_UPLOAD_URL}{$r.url}" alt="{$r.title}"/></dt>
					<dd>
						<h4>{$r.title}</h4>
						<div>{$r.remark}</div>
					</dd>
					<div class="clear"></div>
				</dl>
			{/loop}
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="ad2Div">{adp:index_bottom_pic_4}</div>
{template footer}