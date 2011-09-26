<div class="head">您好！<b>{$MEMBER.username}</b><b class="split">|</b><b>{$MEMBER.email}</b>，上次登录时间：{date 'Y-m-d H:i:s',$MEMBER['lastlogintime']}，上次登录IP：{$MEMBER.lastloginip}</div>
<dl class="base">
	<dt>一周动态</dt>
	<dd>
		<p>注册用户数：<b class="numeric">{$week.regUser}</b></p>
		<p>活跃用户数：<b class="numeric">{$week.activeUser}</b></p>
	</dd>
	<dt>统计信息</dt>
	<dd>
		<p>用户总数：<b class="numeric">{$count.user}</b></p>
	</dd>
	<dt>程序数据库/版本</dt>
	<dd>
		<p>程序版本：{$version.name} V{$version.number} ( {$version.release} )</p>
		<p>操作系统：{PHP_OS}</p>
		<p>WEB服务器：{$_SERVER.SERVER_SOFTWARE}</p>
		<p>PHP版本：{PHP_VERSION}{if @ini_get('safe_mode')} Safe Mode{/if}</p>
		<p>数据库版本：{$runtime.mysql}</p>
		<p>上传许可：{$runtime.fileupload}</p>
		<p>数据库大小：{$runtime.dbsize}</p>
	</dd>
</dl>
