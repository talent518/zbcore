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

$(window).resize(function(event){
	$('.window-wrap:visible').each(function(){
		var win=$(this).data('window');
		if(win){
			win.resize();
		}
});
});
if(isIE6){
	$(window).scroll(function(){
		$('.window-mask:visible,.window-wrap:visible').float('center');
	});
}

//初始化窗口为Object对象
$.window=function(options){
	if($.isFunction(this)){
		return new $.window(options);
	}
	this.options=$.extend({},$.window.defaultSetting,options);
	if(this.options.title && (this.options.content || this.options.url)){
	this.init();
	this.open();
		if(!this.options.content){
			this.refresh();
		}
	}else{
		alert('打开的窗口错误，窗口标题，（窗口内容或URL）不能为空！');
	}
	return this;
};

$.window.prototype={
	windowMask:null,//半透明背景
	windowWrap:null,//窗口容器
	windowHeader:null,//窗口头部
	windowTitle:null,//窗口标题
	windowClose:null,//窗口关闭按钮
	windowRefresh:null,//窗口刷新按钮
	windowContent:null,//窗口内容
	options:{},
	init:function(){//初始化窗口
		this.windowMask=$('<div class="window-mask"></div>').appendTo(document.body);
		this.windowWrap=$('<div class="window-wrap"></div>').appendTo(document.body);
		this.windowHeader=$('<div class="window-header"></div>').appendTo(this.windowWrap);
		this.windowTitle=$('<div class="window-title"></div>').appendTo(this.windowHeader);
		this.windowClose=$('<div class="window-close" title="关闭">关闭</div>').insertBefore(this.windowTitle);
		this.windowRefresh=$('<div class="window-refresh" title="刷新">刷新</div>').insertBefore(this.windowTitle);
		this.windowContent=$('<div class="window-content"></div>').appendTo(this.windowWrap);

		var $this=this;

		this.windowWrap.data('window',this).attr('windowIndex',$.window.count++).hide().draggable({handle:this.windowHeader});
		this.windowRefresh.hover(function(){
			$(this).addClass('hover');
		},function(){
			$(this).removeClass('hover');
		}).click(function(){
			$this.refresh();
		});

		this.windowClose.hover(function(){
			$(this).addClass('hover');
		},function(){
			$(this).removeClass('hover');
		}).click(function(){
			$this.close();
		});

		this.windowMask.hide().fadeTo(0,0.5).mousedown(function(){
			$this.windowWrap.hide();
		}).mouseup(function(){
			$this.windowWrap.show();
		});
		return this;
	},open:function(){//打开窗口
		$.scroll(false);

		this.windowWrap.width(this.options.width);
		this.windowTitle.html(this.options.title);
		this.windowContent.html(this.options.content);

		var $this=this;

		setTimeout(function(){
			$this.windowWrap.show();
			$this.resize();
			$this.windowMask.css('cursor','not-allowed');
		},100);
		this.options.callback.call(this);
		return this;
	},refresh:function(){
		if(!this.options.url)
			return this;
		var $this=this;
		$.get($this.options.url,function(content){
			if($this.options.content==content)
				return;
			$this.options.content=content;
			$this.open();
		});
		return this;
	},resize:function(){//调整窗口
		this.windowMask.width($(window).width()).height($(window).height()).show().css('cursor','wait');
		this.windowWrap.height('auto');
		this.windowContent.height('auto');
		if(this.windowWrap.height()>$(window).height()-40){
			this.windowContent.css({height:$(window).height()-40-this.windowHeader.outerHeight(),overflow:'hidden',overflowX:'auto',overflowY:'scroll'});
			this.windowWrap.height('auto');
		}else{
			this.windowContent.css({overflow:'hidden',overflowX:'auto',overflowY:'hidden'});
		}
		this.windowWrap.float('center');
		return this;
	},close:function(){//关闭窗口
		$.scroll(true);

		var $this=this;

		this.windowMask.fadeOut(200);
		this.windowWrap.animate({left:$(window).width()/2,top:$(window).height()/2,width:0,height:0},200,'',function(){
			$this.windowMask.remove();
			$this.windowWrap.remove();
			$this=null;
		});
	},setOpt:function(name,value){
		var options={};
		if(typeof(name)=='object'){
			options=name;
		}else{
			options[name]=value;
		}
		$.extend(true,this.options,options);
		this.open();
		return this;
	}
};

$.window.defaultSetting={width:600,callback:function(){}};
$.window.count=0;

//向元素添加单击事件[元素必须包括title和href属性]
$.fn.window=function(options){
	$(this).each(function(){
		var data={};
		if($(this).attr('width'))
			data.width=parseInt($(this).attr('width'));
		$(this).data('options',$.extend({},data,options));
	}).unbind('click').bind('click',function(){
		if(this.href==window.location.href+'#') return false;
		var settings=$.extend({},$(this).data('options'),{title:this.title,url:this.href});
		var $this=$(this);
		$this.addClass('windowUsed');
		$.get(settings.url,function(content){
			settings.content=content;
			$.window(settings);
			$this.removeClass('windowUsed').addClass('windowOpened');
		});
		return false;
	});
	return this;
};

$.fn.getWindow=function(){
	var winWrap=$(this).parents('.window-wrap');
	var winChild=winWrap.children('.window-header,.window-content');
	return(winChild.size()==2?winWrap.data('window'):false);
};

})(jQuery);
