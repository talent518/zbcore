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
<iframe id="license" name="license" class="license" width="100%" height="300" scrolling="yes" frameborder="0" style="padding:0px;overflow:auto;" src="install.php?method=db&adm[email]={$adm.email}&adm[password]={$adm.password}&begin=1"></iframe>
<center><button onclick="location.href='install.php?method=cfg'">上一步</button><button onclick="location.href='install.php?method=finish'" id="nextStep" disabled>下一步</button></center>
<script type="text/javascript">
window.down_move_func=function(){
	try {
		license.document.body.scrollTop = license.document.body.scrollHeight - $('iframe.license').height();
	} catch(e) {
	}
	setTimeout(window.down_move_func,100);
};
down_move_func();
</script>
{template footer}