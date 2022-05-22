<h1 class="head" style="line-height:30px;">
	<a><span id="jqFileUpload"/></a>
	图片管理
</h1>
<form id="listform" class="formtable" action="{link method=index}" method="post">
	<div id="jqFileUploadQueue"></div>
	<table cellspacing="0" cellpadding="0" border="0" class="list">
		<thead>
			<tr>
				<th>图片</th>
				<th class="100%" class="l">备注</th>
				<th>排序</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody id="jqFileUploadResp">
		{loop $list $k $r}
			<tr>
				<td>
					<img src="{RES_UPLOAD_URL}{$r.url}" onload="$(this).data('url','{$r.url}')" height="30" class="thumb"/>
				</td>
				<td class="l">
					<input name="remarkes[{$k}]" type="text" value="{$r.remark}" size="40"/>
				</td>
				<td>
					<input name="orderes[{$k}]" type="text" value="{$r.order}" size="4"/>
				</td>
				<td>
					<a href="{link method=drop id=$k}"><img src="{SKIN_URL}images/wrong.gif"/></a>
				</td>
			</tr>
		{/loop}
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4"><input type="submit" value="提交"/><input name="listsubmit" type="hidden" value="1"/><input name="listhash" type="hidden" value="$listhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#listform').validate();
$("#jqFileUpload").uploadifive({
	uploadScript: '{link method=upload}',
	method: 'post',
	queueID: 'jqFileUploadQueue',
	auto: true,
	multi: true,
	buttonText: '上传图片',
	fileType: '.jpg,.jpeg,.gif,.png',
	onUploadComplete: function (file, msg) {
		msg = JSON.parse(msg);
		var tds=$('<tr><td></td><td class="l"></td><td></td><td></td></tr>').appendTo('#jqFileUploadResp').find('td');
		$('<img class="thumb" height="30"/>').data('url',msg.url).attr('src',msg.src).appendTo(tds.eq(0));
		var ipt=$('<input type="text" size="40"/>').appendTo(tds.eq(1));
		ipt.attr('name','remarkes['+msg.img_id+']');
		ipt.val(msg.remark);
		ipt=$('<input type="text" size="4"/>').appendTo(tds.eq(2));
		ipt.attr('name','orderes['+msg.img_id+']');
		ipt.val(0);
		$('<a href="{link method=drop id=IMGID}"><img src="{SKIN_URL}images/wrong.gif"/></a>'.replace('IMGID',msg.img_id)).click(function(){
			var jp=$(this).parent().parent();
			$.getJson(this.href,function(status){
				if(status==true){
					jp.remove();
				}
			});
			return false;
		}).appendTo(tds.eq(3));

		file.queueItem.remove();
	},
	onQueueComplete: function(){
		$('#jqFileUploadResp img.thumb').imagePreview();
	}
});
$('#jqFileUploadResp a').click(function(){
	var jp=$(this).parent().parent();
	$.getJson(this.href,function(status){
		if(status==true){
			jp.remove();
		}
	});
	return false;
});
$('#jqFileUploadResp img.thumb').imagePreview();
</script>
