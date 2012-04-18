{template header}

<div id="wp" class="wp">
{var $sqlcorp='uid in ('.DB()->select(array('table'=>'user','field'=>'uid','where'=>'iscorp=1'),SQL_SELECT_STRING).')';}
{loop M('user.datum')->get_list_by_where($sqlcorp,10) $r}
	<p><a href="{link ctrl=space uid=$r.uid}">{$r.corpname}</a></p>
{/loop}
</div>
{template footer}