{if !IN_AJAX}
			<div class="cf"></div>
		</div>
		<div id="ft" class="wp">
			<p><b>案例速览：</b>{adp:foot-casus|<span class=\"split\">|</span>}</p>
			<p>
				<b>介绍智绘：</b>
			{$i=0;}
			{loop M('page')->get_list_by_where() $r}
				{if $i++}<span class="split">|</span>{/if}<a href="{link method=page id=$r.page_id}" title="{$r.page_title}">{$r.title}</a>
			{/loop}
			</p>
			<p>
				<b>友情链接：</b>
			{$i=0;}
			{loop M('partner')->get_list_by_where('haslogo=1',12) $r}
				{if $i++}<span class="split">|</span>{/if}<a href="{$r.url}" title="{if $r.description}{$r.description}{else}{$r.name}{/if}">{$r.name}</a>
			{/loop}
			</p>
			<p class="fr"><font face="Arial">&copy;2010 <b>Zhui365.com</b>.All right Reserved.</font></p>
			<p><b>联系方式：</b><font face="Arial">TEL:<b style="color:#5d8628">13641406697</b><span class="split">|</span>E-Mail:<a href="mailto:chie365@163.com">chie365@163.com</a></font></p>
			<center><font face="Arial">豫ICP备12003256号</font></center>
			<div class="cf"></div>
		</div>
	</div>
</div>
{template global_footer}
{/if}