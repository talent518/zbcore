/*
功能:窗口
作者:BaoCai Zhang
邮箱:talent518@live.cn
更新时间:2009年12月7日 星期一 17:30:01

实例:
	$('#product img').imagePreview();
	$('#product a').imagePreview();
*/

;(function($){
//初始化窗口为Object对象
$.window={isOpened:false,isClosed:true};
//初始化窗口
$.window.init=function(){
	if($('#window').size()>0) return false;
	$(document.body).append('<div id="window-mask"></div><div id="window"><div id="window-header"><div id="window-close">关闭</div><div id="window-title"></div></div><div id="window-content"></div></div>');
	$('#window').hide().draggable({handle:'#window-header'});
	$('#window-close').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	}).click($.window.close);
	$('#window-mask').hide().fadeTo(0,0.5).mousedown(function(){
		$('#window').hide();
	}).mouseup(function(){
		$('#window').show();
	});
	$(window).resize(function(event){
		if($('#window-mask').is(':visible')){
			$('#window').float('center');
			$('#window-mask').css({width:$(window).width(),height:$(window).height()});
		}
	});
	if(isIE6){
		$(window).scroll(function(){
			$('#window-mask,#window').float('center');
		});
	}
};
//调整窗口
$.window.resize=function(){
	$('#window').height('auto');
	$('#window-content').height('auto');
	if($('#window').height()>$(window).height()-40){
		$('#window-content').css({height:$(window).height()-40-$('#window-header').outerHeight(),overflow:'hidden',overflowX:'auto',overflowY:'scroll'});
		$('#window').height('auto');
	}else
		$('#window-content').css({overflow:'hidden',overflowX:'auto',overflowY:'hidden'});
	$('#window').float('center');
};
//打开窗口
$.window.open = function(options){
	$.window.init();
	if(this.isOpened) return;
	this.isClosed=false;
	this.isOpened=true;
	$.scroll(false);
	$.get(options.url,function(xml){
		if(xml){
			$('#window').width(options.width);
			$('#window-title').html(options.title);
			$('#window-mask').width($(window).width()).height($(window).height()).show().css('cursor','wait');
			$('#window-content').html(xml);
			setTimeout(function(){
				$('#window').show();
				$.window.resize();
				$('#window-mask').css('cursor','not-allowed');
			},0);
		}else{
			$.scroll(true);
			$.window.isClosed=true;
			$.window.isOpened=false;
		}
	});
};
//关闭窗口
$.window.close = function(){
	if(this.isClosed) return;
	$.scroll(true);
	$('#window').animate({left:$(window).width()/2,top:$(window).height()/2,width:0,height:0},200,'',function(){
		$('#window-mask').fadeOut(200);
		$('#window').css({left:'auto',top:'auto',width:'auto',height:'auto'}).hide();
		$('#window-content').html('');
		$('#window-title').html('');
		$.window.isClosed=true;
		$.window.isOpened=false;
	});
};
//向元素添加单击事件[元素必须包括title和href属性]
$.fn.window=function(options){
	if(typeof options=='undefined'){var options={width:600};}
	$(this).each(function(){
		var data={};
		if(this.width) data.width=this.width;
		$(this).data('options',$.extend({},options,data));
	}).unbind('click').bind('click',function(){
		if(this.href==window.location.href+'#') return false;
		var settings=$.extend({},$(this).data('options'),{title:this.title,url:this.href,data:{inajax:1}});
		$.window.open(settings);
		return false;
	});
	return this;
};

})(jQuery);