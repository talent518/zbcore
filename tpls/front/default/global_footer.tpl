<php>{if $config.isshowdebug || DEBUG}
<div id="pd" class="wp">{php echodebug();}</div>
<script type="text/javascript">$('#pd p.base').click(function(){
	$('#pd div.detail').toggle();
});</script>
{/if}</php>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23671886-1']);
  _gaq.push(['_setDomainName', '.zhui365.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>