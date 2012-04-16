<div class="head">
	<a href="{link ctrl=user method=edit}" title="修改帐户" class="edit button fr" style="margin:-4px;">修改帐户</a>
	您好！<b>{$MEMBER.username}</b><b class="split">|</b><b>{$MEMBER.email}</b>，上次登录时间：{date 'Y-m-d H:i:s',$MEMBER['lastlogintime']}，上次登录IP：{$MEMBER.lastloginip}
</div>
<dl class="base">
	<dt><a id="editdatum" href="{link ctrl=user method=datum}" title="编辑个人资料" class="edit">编辑</a>{if $MEMBER.iscorp}公司资料{else}个人资料{/if}</dt>
	<dd>
	{if $MEMBER.iscorp}
		<p>公司名：{$datum.corpname|html}</p>
	{/if}
		<p>{if $MEMBER.iscorp}联系人{else}姓名{/if}：{$datum.linkman|html}</p>
	{if !$MEMBER.iscorp}
		<p>性别：{if $datum.sex}男{else}女{/if}</p>
	{/if}
		<p>通信地址：{$datum.address|html}</p>
		<p>QQ号：{$datum.qq}</p>
		<p>MSN：{$datum.msn}</p>
		<p>手机号：{$datum.mobile}</p>
		<p>固定电话：{$datum.phone}</p>
	{if $MEMBER.iscorp}
		<p>传真：{$datum.fax}</p>
	{/if}
	</dd>
</dl>
{if $MEMBER.iscorp}
<dl class="base">
	<dt>公司介绍</dt>
	<dd>{if $datum.introduce}{$datum.introduce|html}{else}暂无{/if}</dd>
</dl>
{/if}
<script type="text/javascript">
$('#bd .edit').window({width:600});
{if !$MEMBER.hasdatum}
	$('#editdatum').click();
{/if}
</script>