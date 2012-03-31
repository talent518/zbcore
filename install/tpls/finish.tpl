{template header}

<div class="step">
	<div class="stepnum step3">
		<h2>完成安装</h2>
		<p>已完成所有安装</p>
	</div>

	<div class="stepstat">
		<ul>
			<li>1</li>
			<li>2 </li>
			<li>3 </li>
			<li class="current last">4 </li>
		</ul>
		<div class="stepstatbg stepstat4"></div>
	</div>
</div>
<div class="finish">
	<h1>恭喜您，安装成功！</h1>
	<p>超级管理员默认用户名为<b>admin</b>。</p>
	<p>为了网站的安全期间，请手动删除以下目录：</p>
	<ul>
		<li><b>/install.php</b></li>
		<li><b>/install/</b></li>
	</ul>
</div>
<center><button onclick="location.href='install.php?method=db}'">上一步</button><button onclick="location.href='index.php'">完成</button></center>
{template footer}