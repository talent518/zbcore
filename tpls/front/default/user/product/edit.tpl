<form id="editform" class="formtable" action="{link ctrl=user.product method=edit id=$id}" method="post" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>产品标题：</th>
				<td><input name="title" type="text" value="{$edit.title|html}" size="30" /></td>
			</tr>
			<tr>
				<th>上传产品：</th>
				<td>
					<input id="jqFileUpload" name="url" type="text" value="{$edit.url|html}"/>
					<div id="jqFileUploadQueue"></div>
					<div id="jqFileUploadResp">
					{loop M('user.product.image')->get_list_by_where($id) $k $r}
						<p>
							<img src="{RES_UPLOAD_URL}{$r.url}" onload="$(this).data('url','{$r.url}')" height="30" class="thumb" style="cursor:pointer;border:2px {if $r.url==$edit.url}red{else}white{/if} solid"/>
							<input name="remarkes[{$k}]" type="text" value="{$r.remark}" size="40" style="margin:0px 5px;"/>
							<input name="orderes[{$k}]" type="text" value="{$r.order}" size="4" style="margin-right:5px;"/>
							<a href="{link ctrl=user.product method=drop.upload id=$k}"><img src="{SKIN_URL}images/wrong.gif"/></a>
						</p>
					{/loop}
					</div>
				</td>
			</tr>
			<tr>
				<th>价格：</th>
				<td><input name="price" type="text" value="{$edit.price|html}" size="14" /></td>
			</tr>
			<tr>
				<th>备注：</th>
				<td><textarea name="remark" cols="65" rows="5">{$edit.remark|text}</textarea></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$edit.order}" size="4" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input type="submit" value="提交"/><input name="editsubmit" type="hidden" value="1"/><input name="edithash" type="hidden" value="$edithash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#editform').validate({
	rules:{
		title:{
			required:true,
			maxlength:50
		},
		url:{
			required:true
		},
		price:{
			required:true,
			ufloat:true
		},
		order:{
			integer:true
		}
	},messages:{
		url:{required:'请上传并选择默认产品'}
	}
});
$("#jqFileUpload").uploadify({
	'uploader': '{SKIN_URL}images/uploadify.swf',
	'cancelImg': '{SKIN_URL}images/wrong.gif',
	'script': '{ROOT_URL}',
	'scriptData': {proj:'{IN_PROJ}',ctrl:'user.product',method:'upload',auth:'{$auth}'},
	'method':'get',
	'queueID':'jqFileUploadQueue',
	'auto': true,
	'multi': true,
	'displayData': 'speed',
	'fileDesc': 'Image(*.jpg;*.gif;*.png)',
	'fileExt': '*.jpg;*.jpeg;*.gif;*.png',
	onComplete: function (evt, queueID, fileObj, response, data) {
		var msg;
		try{
			msg=window["eval"]("("+response+")");
		}catch(e){
			alert(response);
		}
		if(typeof(msg)=='string'){
			alert('“'+fileObj.name+'”'+msg);
		}else{
			var p=$('<p/>').appendTo('#jqFileUploadResp');
			$('<img class="thumb" height="30" style="cursor:pointer;border:2px white solid"/>').data('url',msg.url).click(function(){
				$('#jqFileUploadResp img.thumb').css('borderColor','white');
				$(this).css('borderColor','red');
				$('#jqFileUpload').val($(this).data('url'));
			}).attr('src',msg.src).appendTo(p);
			var ipt=$('<input type="text" style="margin:0px 5px;" size="40"/>').appendTo(p);
			ipt.attr('name','remarkes['+msg.img_id+']');
			ipt.val(msg.remark);
			ipt=$('<input type="text" size="4" style="margin-right:5px;"/>').appendTo(p);
			ipt.attr('name','orderes['+msg.img_id+']');
			ipt.val(0);
			$('<a href="{link ctrl=user.product method=drop.upload id=IMGID}"><img src="{SKIN_URL}images/wrong.gif"/></a>'.replace('IMGID',msg.img_id)).click(function(){
				var jp=$(this).parent('p');
				$.getJson(this.href,function(status){
					if(status==true){
						if($('#jqFileUpload').val()==$('img.thumb',jp).data('url')){
							$('#jqFileUpload').val('');
						}
						jp.remove();
					}
				});
				return false;
			}).appendTo(p);
		}
		$('#'+queueID).remove();
	},
	onAllComplete:function(){
		setTimeout(function(){$('#editform').getWindow().resize();},100);
	}
});
$('#jqFileUploadResp img.thumb').click(function(){
	$('#jqFileUploadResp img.thumb').css('borderColor','white');
	$(this).css('borderColor','red');
	$('#jqFileUpload').val($(this).data('url'));
}).load();
$('#jqFileUploadResp a').click(function(){
	var jp=$(this).parent('p');
	$.getJson(this.href,function(status){
		if(status==true){
			if($('#jqFileUpload').val()==$('img.thumb',jp).data('url')){
				$('#jqFileUpload').val('');
			}
			jp.remove();
		}
	});
	return false;
});
</script>