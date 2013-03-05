<php>{if $config.isshowdebug || DEBUG}
<div id="pd">{php echodebug();}</div>
<script type="text/javascript">$('#pd p.base').click(function(){
	$('#pd div.detail').toggle();
});</script>
{/if}</php>
{$config.statistics}
</body>
</html>