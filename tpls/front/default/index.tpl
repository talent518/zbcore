{template header}
<h1 class="defTitle">{$config.sitetitle}</h1>
<dl class="wp partner">
	<dt>友情链接</dt>
	<dd>
	{var $where='logo != \'\'';}
	{loop M('partner')->get_list_by_where($where,10) $r}
		<p>
			<a href="{$r.url}" title="{$r.description}" target="_blank"><img src="{RES_UPLOAD_URL}{$r.logo}" alt="{$r.title}"/></a>
			<span><a href="{$r.url}" title="{$r.name}" target="_blank">{$r.name}</a></span>
		</p>
	{/loop}
	</dd>
</dl>
{template footer}