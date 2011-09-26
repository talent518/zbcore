/*
功能:图片预览器
作者:BaoCai Zhang
邮箱:talent518@live.cn
更新时间:2009年12月7日 星期一 17:30:01

实例:
	$('#product img').imagePreview();
	$('#product a').imagePreview();
*/

;(function($){

$.dialog ={};

$.dialog.Titles={error:'错误提示',warning:'警告提示',success:'成功提示',prohibit:'禁止提示',prompt:'确认信息'};

$.dialog.defaults={width:480,OK:null,Cancel:null};
$.dialog.settings={};

//初始化对话框
$.dialog.init=function(){
	if($('#dialog').size()>0){return;}

	$(document.body).append('<div id="dialog-mask"></div><div id="dialog"><div id="dialog-title"></div><div id="dialog-content"><div id="dialog-content-message"></div><div id="dialog-button"><input type="button" id="dialog-button-ok" value="确定"/><input type="button" id="dialog-button-cancel" value="取消"/></div></div>');

	$('#dialog').hide().draggable({handle:'#dialog-title'});

	$('#dialog-mask').fadeTo(0,0).hide().mousedown(function(){
		$('#dialog').hide();
	}).mouseup(function(){
		$('#dialog').show();
	});
	$('#dialog-button-ok').click(function(){
		$.dialog.hide($.dialog.settings.OK);
	});
	$('#dialog-button-cancel').click(function(){
		$.dialog.hide($.dialog.settings.Cancel);
	});
	$(document).keydown(function(event){
		if(event.keyCode==27 && $('#dialog').is(':visible')){
			$.dialog.hide();
		}
	});
	$(window).resize(function(event){
		if($('#dialog').is(':visible')){
			$('#dialog').float('center');
		}
	});
};
//显示对话框
$.dialog.show=function(message,options){
	$.dialog.init();
	var settings=$.extend({title:$.dialog.Titles[options.type]},$.dialog.defaults,options);
	$('#dialog-title').html(settings.title);
	$('#dialog-content-message').html(message).removeClass().addClass('dialog-'+settings.type).height($('#dialog-content-message').height()<80?80:($('#dialog-content-message').height()>128?128:'auto'));

	$('#dialog-button').show();

	$('#dialog-mask').width($(window).width()).height($(window).height()).stop(true).show().fadeTo('normal',0.6).css('cursor','not-allowed');
	$('#dialog').stop(true).css('width',settings.width).float('center').fadeTo(0,1).fadeIn(200);
	settings.scrollEnabled=$.scrollEnabled;
	$.dialog.settings=settings;
	$.scroll(false);
};
//隐藏对话框
$.dialog.hide=function(callback){
	$('#dialog').stop(true).fadeOut('slow',function(){
		$(this).hide();
	});
	$('#dialog-mask').stop(true).fadeOut('normal',function(){
		$(this).hide();$.scroll($.dialog.settings.scrollEnabled);
		if($.isFunction(callback)){
			callback();
		}
	});
};
//错误对话框
$.dialog.error=function(message,options){
	if(typeof(options)=='undefined'){
		options={};
	}else if($.isFunction(options)){
		options={OK:options};
	}
	options.type='error';
	$.dialog.show(message,options);
	$('#dialog-button-ok').focus().show();
	$('#dialog-button-cancel').hide();
};
//警告对话框
$.dialog.warning=function(message,options){
	if(typeof(options)=='undefined'){
		options={};
	}else if($.isFunction(options)){
		options={OK:options};
	}
	options.type = 'warning';
	$.dialog.show(message,options);
	$('#dialog-button-ok').focus().show();
	$('#dialog-button-cancel').hide();
};
//成功对话框
$.dialog.success=function(message,options){
	if(typeof(options)=='undefined'){
		options={};
	}else if($.isFunction(options)){
		options={OK:options};
	}
	options.type='success';
	$.dialog.show(message,options);
	$('#dialog-button-ok').focus().show();
	$('#dialog-button-cancel').hide();
};
//禁止对话框
$.dialog.prohibit=function(message,options){
	if(typeof(options)=='undefined'){
		options={};
	}else if($.isFunction(options)){
		options={OK:options};
	}
	options.type='prohibit';
	$.dialog.show(message,options);
	$('#dialog-button-ok').focus().show();
	$('#dialog-button-cancel').hide();
};
//应答对话框
$.dialog.prompt=function(message,options){
	if(typeof(options)=='undefined'){
		options={};
	}else if($.isFunction(options)){
		options={OK:options};
	}
	options.type='prompt';
	$.dialog.show(message,options);
	$('#dialog-button-ok').focus().show();
	$('#dialog-button-cancel').show();
};
//ajax获取消息对话框
$.fn.dialog=function(url,callback){
	var $this=this;
	callback=$.isFunction(callback)?callback:function(){};
	$.get(url,function(xml){
		var data=typeof(xml)=='object'?$(xml).find('root').text():xml;
		if(typeof(xml)=='object'){
			$.dialog.success('操作成功',function(){
				setTimeout(function(){
					callback.call($this,data);
				},300);
			});
		}else{
			$.dialog.warning(data);
		}
	});
	return this;
};

})(jQuery);