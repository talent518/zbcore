{template space/header}

<dl class="cat content">
	<dd>
		<p>联系人：{$DATUM.linkman|html}</p>
		<p>通信地址：{$DATUM.address|html}</p>
		<p>QQ号：<b class="numeric">{$DATUM.qq}</b></p>
		<p>MSN：{$DATUM.msn}</p>
		{if $DATUM.mobile}<p>手机号：<b class="numeric">{$DATUM.mobile}</b></p>{/if}
		{if $DATUM.phone}<p>固定电话：<b class="numeric">{$DATUM.phone}</b></p>{/if}
		{if $DATUM.fax}<p>传真：<b class="numeric">{$DATUM.fax}</b></p>{/if}
	</dd>
</dl>

{template space/footer}