<form id="addform" class="formtable" action="{link ctrl=picture method=add}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>所属栏目：</th>
				<td><select name="cat_id"></select></td>
			</tr>
			<tr>
				<th>图片标题：</th>
				<td><input name="title" type="text" value="{$add.title|html}" size="20" /></td>
			</tr>
			<tr>
				<th>上传图片：</th>
				<td>
					<input id="jqFileUpload" name="url" type="text" value=""/>
					<div id="jqFileUploadQueue"></div>
					<div id="jqFileUploadResp"></div>
				</td>
			</tr>
		{if $posList}
			<tr>
				<th>推荐：</th>
				<td>{loop $posList $r}<input name="posids[]" type="checkbox" value="{$r.posid}"/>{$r.pname}&nbsp;&nbsp;{/loop}</td>
			</tr>
		{/if}
			<tr>
				<th>备注：</th>
				<td><textarea name="remark" cols="40" rows="3">{$add.remark|text}</textarea></td>
			</tr>
			<tr>
				<th>排序：</th>
				<td><input name="order" type="text" value="{$add.order}" size="4" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input type="submit" value="提交"/><input name="addsubmit" type="hidden" value="1"/><input name="addhash" type="hidden" value="$addhash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#addform').validate({
	rules:{
		cat_id:{
			required:true,
			min:1
		},
		title:{
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
$('#addform select[name=cat_id]').staged('{link ctrl=category method=json type=picture}',{val:{$add.cat_id}});
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

		var p=$('<p/>').appendTo('#jqFileUploadResp');
		$('<img height="30" style="cursor:pointer;border:2px white solid"/>').data('url',msg.url).click(function(){
			$('#jqFileUploadResp img').css('borderColor','white');
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
					jp.remove();
				}
			});
			return false;
		}).appendTo(p);

		file.queueItem.remove();
	},
	onQueueComplete: function() {
		setTimeout(function(){$('#addform').getWindow().resize();},100);
	}
});

</script>