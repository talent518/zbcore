{template header}
<div class="message {if $status}success{else}error{/if}">
	<h1>{if $status}<img src="{SKIN_URL}images/right.gif"/>成功{else}<img src="{SKIN_URL}images/wrong.gif"/>错误{/if}提醒</h1>
	<div class="body">
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