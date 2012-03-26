{template header}
<div id="hd">
	<h1><a href="{link proj=admin}"><img src="{SKIN_URL}images/logo.gif"/></a></h1>
	<p>您好，<b>{$MEMBER.username}</b><span class="split">|</span><a href="{link method=logout}">退出</a><a class="button" href="{ROOT_URL}" target="_blank">网站首页</a></p>
	<ul>
		<li><a href="#control"><b>控制台</b></a></li>
		<li><a href="#setting"><b>设置</b></a></li>
		<li><a href="#user"><b>用户</b></a></li>
		<li><a href="#content"><b>内容</b></a></li>
		<li><a href="#ads"><b>广告</b></a></li>
	</ul>
</div>
<div id="wp">
	<div id="nv">
		<ul>
			<h3><span>控制台</span></h3>
			<ol>
				<li><a href="{link method=welcome}">欢迎页面</a></li>
				<li><a href="{link method=aboutus}">关于我们</a></li>
				<li><a href="{link ctrl=setup method=config}">网站设置</a></li>
				<li>
					<em><a href="{link ctrl=user method=add}" class="add" title="添加用户">添加</a></em>
					<a href="{link ctrl=user}">用户管理</a>
				</li>
				<li>
					<em><a href="{link ctrl=ad method=add}" class="add" title="添加广告">添加</a></em>
					<a href="{link ctrl=ad}">广告管理</a>
				</li>
				<li>
					<em><a href="{link ctrl=db method=backup}" title="备份数据库">备份</a></em>
					<a href="{link ctrl=db method=list}">数据库备份与还原</a>
				</li>
				<li><a href="{link method=cache}">更新缓存</a></li>
			</ol>
		</ul>
		<ul>
			<h3><span>设置</span></h3>
			<ol>
				<li><a href="{link ctrl=setup method=config}">网站设置</a></li>
				<li><a href="{link ctrl=setup method=email}">邮件设置</a></li>
				<li><a href="{link ctrl=setup method=verify}">验证码设置</a></li>
				<li><a href="{link ctrl=setup method=user}">用户设置</a></li>
				<li><a href="{link ctrl=setup method=template}">模板/主题设置</a></li>
				<li>
					<em><a href="{link ctrl=area method=add}" class="add" title="添加地区">添加</a></em>
					<a href="{link ctrl=area}">地区管理</a>
				</li>
			</ol>
		</ul>
		<ul>
			<h3><span>用户</span></h3>
			<ol>
				<li>
					<em><a href="{link ctrl=user method=add}" class="add" title="添加用户">添加</a></em>
					<a href="{link ctrl=user}">用户管理</a>
				</li>
				<li>
					<em><a href="{link ctrl=group method=add}" class="add" title="添加用户组">添加</a></em>
					<a href="{link ctrl=group}">用户组管理</a>
				</li>
			</ol>
		</ul>
		<ul>
			<h3><span>内容</span></h3>
			<ol>
				<li>
					<em><a href="{link ctrl=position method=add}" class="add" title="添加推荐位">添加</a></em>
					<a href="{link ctrl=position}">推荐位管理</a>
				</li>
				<li>
					<em><a href="{link ctrl=category method=add}" class="add" title="添加栏目">添加</a></em>
					<a href="{link ctrl=category}">栏目管理</a>
				</li>
				<li>
					<em><a href="{link ctrl=article method=add}" class="add" title="添加文章">添加</a></em>
					<a href="{link ctrl=article}">文章管理</a>
				</li>
				<li>
					<em><a href="{link ctrl=partner method=add}" class="add" title="添加友情连接">添加</a></em>
					<a href="{link ctrl=partner}">友情连接</a>
				</li>
			</ol>
		</ul>
		<ul>
			<h3><span>广告</span></h3>
			<ol>
				<li>
					<em><a href="{link ctrl=ad method=add}" class="add" title="添加广告">添加</a></em>
					<a href="{link ctrl=ad}">广告管理</a>
				</li>
				<li>
					<em><a href="{link ctrl=adp method=add}" class="add" title="添加广告位">添加</a></em>
					<a href="{link ctrl=adp}">广告位管理</a>
				</li>
			</ol>
		</ul>
	</div>
	<div id="bd">
	</div>
</div>
<script type="text/javascript" src="{RES_URL}ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="{RES_URL}ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="{RES_URL}ckfinder/ckfinder.js"></script>
<script type="text/javascript">
$('#nv a:not(.add)').click(function(){
	$('#bd').load(this.href);
	$('#nv li').removeClass('active');
	$(this).parent('li').addClass('active');
	return false;
}).eq(0).click();
$('#nv a.add').window();

var hda=$('#hd ul a').each(function(i){
	$(this).click(function(){
		$('#hd li').removeClass('active').eq(i).addClass('active');
		$('#nv ul').hide().eq(i).show();
	});
});
if(hda.is('[href="'+location.hash+'"]')){
	hda.filter('[href="'+location.hash+'"]').click();
}else{
	hda.eq(0).click();
}

$(window).resize(function(){
	var size={width:$(window).width(),height:$(window).height()};
	$('#wp,#nv,#bd').each(function(){
		$(this).height(size.height-100);
	});
	$('#nv').width(170);
	$('#bd').width(size.width-172);
}).resize();
</script>
{template footer}