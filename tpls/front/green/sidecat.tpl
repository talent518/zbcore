{var $tree=M('category')->get_tree(null,$category.ctype);}

{function:side_cate_tree $tree,$sid,$r,$n}
	{if $sid==$r.cat_id}
		<h4 style="padding-left:{$n}em;"><a href="{link method=category id=$r.cat_id}">{$r.cat_name}</a></h4>
	{else}
		<li style="padding-left:{$n}em;"><a href="{link method=category id=$r.cat_id}">{$r.cat_name}</a></li>
	{/if}
	{loop $tree[$r['cat_id']] $_r}
		{callback:side_cate_tree $tree,$sid,$_r,$n+1}
	{/loop}
{/function}
{loop $tree[0] $r}
	{if $tree[$r['cat_id']]}
		<ol class="cat">
			<h2><a href="{link method=category id=$r.cat_id}">{$r.cat_name}</a></h2>
			{loop $tree[$r['cat_id']] $_r}
				{callback:side_cate_tree $tree,$catid,$_r,1}
			{/loop}
		</ol>
	{/if}
{/loop}
<ol>
	<h2>案例类别</h2>
	<li>电话：<font face="Arial">13641406697</font></li>
	<li>邮箱：<font face="Arial">chie365@163.com</font></li>
</ol>
