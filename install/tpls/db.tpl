{template header}

<div class="step">
	<div class="stepnum step3">
		<h2>安装数据库</h2>
		<p>正在执行数据库安装</p>
	</div>

	<div class="stepstat">
		<ul>
			<li>1</li>
			<li>2 </li>
			<li class="current">3 </li>
			<li class="unactivated last">4 </li>
		</ul>
		<div class="stepstatbg stepstat3"></div>
	</div>
</div>
<iframe class="license" width="100%" height="300" scrolling="yes" frameborder="0" style="padding:0px;overflow:auto;" src="{link method=db adm[email]=$adm.email adm[password]=$adm.password begin=1}"></iframe>
<center><button onclick="location.href='{link method=cfg}'">上一步</button><button onclick="location.href='{link method=finish}'" id="nextStep" disabled>下一步</button></center>
{template footer}