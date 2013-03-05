{template header}
<div class="contDiv">
	{template sidecat}
	<div class="fr wh740">
		<div class="titleRt">
			<div class="fl ttLtDiv"><img src="{SKIN_URL}images/rt_yhpl.jpg" alt=""/></div>
			<div class="fr ttRtDiv gray">您的位置：<a href="{SITE_URL}">网站首页</a> &gt;&gt; <a href="{link ctrl=comment}">用户评论</a></div>
			<div class="clear"></div>
		</div>
		<form id="frmComment" class="contRtDiv" action="{link ctrl=comment method=submit}" method="post">
			<table border="0" cellpadding="0" cellspacing="5" class="lxwmTb">
				<tr>
					<td class="wh135">姓 名：</td>
					<td class="wh510"><input name="nickname" type="text" class="input01"/> <span class="red">*</span></td>
				</tr>
				<tr>
					<td class="wh135">E-mail：</td>
					<td class="wh510"><input name="email" type="text" class="input02"/> <span class="red">*</span></td>
				</tr>
				<tr>
					<td class="wh135">电 话：</td>
					<td class="wh510"><input name="phone" type="text" class="input02"/> <span class="red">* 请填写电话，方便我们与您联系！</span></td>
				</tr>
				<tr>
					<td class="wh135">留言主题：</td>
					<td class="wh510"><input name="subject" type="text" class="input03"/> <span class="red">*</span></td>
				</tr>
				<tr>
					<td class="wh135">头 像：</td>
					<td class="wh510">
						<ul>
							<li>
								<div><img src="{SKIN_URL}images/tx01.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx01.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx02.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx02.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx03.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx03.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx04.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx04.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx05.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx05.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx06.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx06.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx07.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx07.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx08.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx08.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx09.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx09.jpg" /></div>
							</li>
							<li>
								<div><img src="{SKIN_URL}images/tx10.jpg" alt=""/></div>
								<div class="taC"><input name="avatar" type="radio" value="{SKIN_URL}images/tx10.jpg" /></div>
							</li>
						</ul>
						<div class="clear"></div>
					</td>
				</tr>
				<tr>
					<td class="wh135">留言内容：</td>
					<td class="wh510">
						<textarea name="content" class="textarea01"></textarea>
						<div class="mt8 an01Div">
							<button type="submit" class="fl">提 交</button>
							<button type="reset" class="fl">重 置</button>
							<div class="clear"></div>
						</div>
						<input name="commentsubmit" type="hidden" value="1"/>
						<input name="commenthash" type="hidden" value="$commenthash"/>
					</td>
				</tr>
			</table>
		</form>
		<script type="text/javascript">
		function isEmail(value){
			return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
		}
		$('#frmComment').submit(function(){
			if($('input[name=nickname]',this).val().length==0){
				alert('姓名不能为空！');
				$('input[name=nickname]',this).focus();
				return false;
			}
			var email=$('input[name=email]',this).val();
			if(email.length==0){
				alert('E-mail不能为空！');
				$('input[name=email]',this).focus();
				return false;
			}
			if(!isEmail(email)){
				alert('E-mail格式不合法！');
				$('input[name=email]',this).focus();
				return false;
			}
			var phone=$('input[name=phone]',this).val();
			if(phone.length==0){
				alert('电话不能为空！');
				$('input[name=phone]',this).focus();
				return false;
			}
			if(!/^(\d+|[0-9\-]+)$/.test(phone)){
				alert('电话只能包括0到9数字和字符“-”！');
				$('input[name=phone]',this).focus();
				return false;
			}
			if($('input[name=subject]',this).val().length==0){
				alert('留言主题不能为空！');
				$('input[name=subject]',this).focus();
				return false;
			}
			if($('input:radio:checked',this).size()==0){
				alert('请选择头像！');
				$('input:radio:first',this).focus();
				return false;
			}
			if($('textarea[name=content]',this).val().length==0){
				alert('留言内容不能为空！');
				$('textarea[name=content]',this).focus();
				return false;
			}
		});
		</script>
	</div>
	<div class="clear"></div>
</div>
{template footer}