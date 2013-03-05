{template header}
<div class="contDiv">
	{template sidecat}
	<div class="fr wh740">
		<div class="titleRt">
			<div class="fl ttLtDiv"><img src="{SKIN_URL}images/rt_joinus.jpg" alt=""/></div>
			<div class="fr ttRtDiv gray">您的位置：<a href="{SITE_URL}">网站首页</a> &gt;&gt; <a href="{link ctrl=join}">加入我们</a></div>
			<div class="clear"></div>
		</div>
		<form id="frmjoin" class="contRtDiv" action="{link ctrl=join method=submit}" method="post">
			<table border="0" cellpadding="0" cellspacing="5" class="lxwmTb">
				<tr>
					<td class="wh135">姓　名：</td>
					<td class="wh510"><input name="realname" type="text" class="input01"/> <span class="red">*</span></td>
				</tr>
				<tr>
					<td class="wh135">手机：</td>
					<td class="wh510"><input name="mobile" type="text" class="input02"/> <span class="red">*</span></td>
				</tr>
				<tr>
					<td class="wh135">Q　Q：</td>
					<td class="wh510"><input name="qq" type="text" class="input02"/> <span class="red">*</span></td>
				</tr>
				<tr>
					<td class="wh135">&nbsp;</td>
					<td class="wh510">
						<div class="mt8 an01Div" style="margin-left:0px;">
							<button type="submit" class="fl">提 交</button>
							<button type="reset" class="fl">重 置</button>
							<div class="clear"></div>
						</div>
						<input name="joinsubmit" type="hidden" value="1"/>
						<input name="joinhash" type="hidden" value="$joinhash"/>
					</td>
				</tr>
			</table>
		</form>
		<script type="text/javascript">
		$('#frmJoin').submit(function(){
			if($('input[name=realname]',this).val().length==0){
				alert('姓名不能为空！');
				$('input[name=realname]',this).focus();
				return false;
			}
			var mobile=$('input[name=mobile]',this).val();
			if(mobile.length==0){
				alert('电话不能为空！');
				$('input[name=mobile]',this).focus();
				return false;
			}
			if(!/^\d+$/.test(mobile)){
				alert('手机号不合法！');
				$('input[name=mobile]',this).focus();
				return false;
			}
			var qq=$('input[name=qq]',this).val();
			if(qq.length==0){
				alert('QQ不能为空！');
				$('input[name=qq]',this).focus();
				return false;
			}
			if(!/^\d+$/.test(qq)){
				alert('QQ号格式不正确！');
				$('input[name=qq]',this).focus();
				return false;
			}
		});
		</script>
	</div>
	<div class="clear"></div>
</div>
{template footer}