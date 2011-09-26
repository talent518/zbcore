<div class="head">
	<a href="{link ctrl=user method=edit}" title="修改帐户" class="edit button fr" style="margin:-4px;">修改帐户</a>
	您好！<b>{$MEMBER.username}</b><b class="split">|</b><b>{$MEMBER.email}</b>，上次登录时间：{date 'Y-m-d H:i:s',$MEMBER['lastlogintime']}，上次登录IP：{$MEMBER.lastloginip}
</div>
<dl class="base">
	<dt><a id="editdatum" href="{link ctrl=user method=datum}" title="编辑个人资料" class="edit">编辑</a>个人资料</dt>
	<dd>
		<p>公司名：{$datum.corpname|html}</p>
		<p>联系人：{$datum.linkman|html}</p>
		<p>通信地址：{$datum.address|html}</p>
		<p>QQ号：{$datum.qq}</p>
		<p>MSN：{$datum.msn}</p>
		<p>手机号：{$datum.mobile}</p>
		<p>固定电话：{$datum.phone}</p>
	</dd>
</dl>
<script type="text/javascript">
$('#bd .edit').window({width:500});
{if !$MEMBER.hasdatum}
	$('#editdatum').click();
{/if}
</script>