<form id="editform" class="formtable" action="{link ctrl=picture method=edit id=$id}" method="post" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属栏目：</th>
				<td><select name="cat_id"></select></td>
			</tr>
			<tr>
				<th>图片标题：</th>
				<td><input name="title" type="text" value="{$edit.title|html}" size="20" /></td>
			</tr>
			<tr>
				<th>上传图片：</th>
				<td>
					<input id="jqFileUpload" name="url" type="text" value="{$edit.url|html}"/>
					<div id="jqFileUploadQueue"></div>
					<div id="jqFileUploadResp">
					{loop M('picture.image')->get_list_by_where('pic_id='.$id) $k $r}
						<p>
							<img src="{RES_UPLOAD_URL}{$r.url}" onload="$(this).data('url','{$r.url}')" height="30" class="thumb" style="cursor:pointer;border:2px {if $r.url==$edit.url}red{else}white{/if} solid"/>
							<input name="remarkes[{$k}]" type="text" value="{$r.remark}" size="40" style="margin:0px 5px;"/>
							<input name="orderes[{$k}]" type="text" value="{$r.order}" size="4" style="margin-right:5px;"/>
							<a href="{link ctrl=picture method=drop.upload id=$k}"><img src="{SKIN_URL}images/wrong.gif"/></a>
						</p>
					{/loop}
					</div>
				</td>
			</tr>
		{if $posList}
			<tr>
				<th>推荐：</th>
				<td>{loop $posList $r}<input name="posids[]" type="checkbox" value="{$r.posid}"{if in_array($r.posid,explode(',',$edit.posids))} checked{/if}/>{$r.pname}&nbsp;&nbsp;{/loop}</td>
			</tr>
		{/if}
			<tr>
				<th>备注：</th>
				<td><textarea name="remark" cols="40" rows="3">{$edit.remark|html}</textarea></td>
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
		cat_id:{
			required:true,
			min:1
		},
		name:{
			required:true,
			maxlength:50,
			chinese:true
		},
		url:{
			required:true
		},
		order:{
			integer:true
		}
	},messages:{
		cat_id:{min:'请选择'},url:{required:'请上传并选择默认图片'}
	}
});
$('#editform select[name=cat_id]').staged('{link ctrl=category method=json type=picture}',{val:{$edit.cat_id}});
$("#jqFileUpload").uploadify({
	'uploader': '{SKIN_URL}images/uploadify.swf',
	'cancelImg': '{SKIN_URL}images/wrong.gif',
	'script': '{ROOT_URL}index.php',
	'scriptData': {proj:'{IN_PROJ}',ctrl:'picture',method:'upload',auth:'{$auth}'},
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
			$('<a href="{link ctrl=picture method=drop.upload id=IMGID}"><img src="{SKIN_URL}images/wrong.gif"/></a>'.replace('IMGID',msg.img_id)).click(function(){
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