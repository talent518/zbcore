{template header}
<div id="message" class="{if $success}success{else}error{/if}">
	<h1>{if $success}成功{else}错误{/if}提示</h1>
	<div class="body">
		<em class="icon"></em>
	{if $backurl}
		<h2><a href="$backurl">$message</a></h2>
		<script type="text/javascript">setTimeout(function(){window.location.href='$backurl';},$timeout);</script>
		<p>如长时间不自动跳转，请<a href="$backurl">单击此处</a>。</p>
	{else}
		<h2>$message</h2>
	{/if}
	</div>
</div>
{template footer}