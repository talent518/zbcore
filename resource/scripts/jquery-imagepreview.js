/*
功能:图片预览器
作者:BaoCai Zhang
邮箱:talent518@live.cn
更新时间:2009年12月7日 星期一 17:30:01

实例:
	$('#product img').imagePreview();
	$('#product a').imagePreview();
*/

(function($){
//初始化图片预览器为Object对象
$.imagePreview={};
//图片基本数据
$.imagePreview.data={load:false,width:0,height:0,zoom:1.0,X:0,Y:0,bTime:0,eTime:0,display:'suit',step:'next',list:0,index:0,slide:null,delay:5};
//图片列表
$.imagePreview.list=new Array();
//初始化图片预览器
$.imagePreview.init=function(){
	if($('#imagePreview').size()>0) return false;
	$(document.body).append('<div id="imagePreview-mask"></div><div id="imagePreview"><div id="imagePreview-header"><span id="imagePreview-close">关闭</span><a id="imagePreview-title">标题</a></div><div id="imagePreview-image"><img/></div><div id="imagePreview-toolBar"></div><div id="imagePreview-statusBar"></div></div>');
	$('#imagePreview-toolBar').html('<a id="imagePreview-tool-previous" title="前一个图片"></a><a id="imagePreview-tool-next" class="down" title="下一个图片"></a><span></span><a id="imagePreview-tool-suit" title="适合图片"></a><a id="imagePreview-tool-fact" title="实际大小"></a><a id="imagePreview-tool-slide" title="播放幻灯片"></a><span></span><a id="imagePreview-tool-zoomin" title="放大"></a><a id="imagePreview-tool-shrink" title="缩小"></a>');
	$('#imagePreview-statusBar').html('<label>总计：</label><span id="imagePreview-status-count"></span><label>实际大小：</label><span id="imagePreview-status-size"></span><label>缩放比例：</label><span id="imagePreview-status-zoom"></span><label>下载速度：</label><span id="imagePreview-status-bps"></span>');
	if(isIE6){
		$(window).scroll(function(){
			$('#imagePreview-mask,#imagePreview').float('center');
		});
	}
	$('#imagePreview').hide().draggable({handle:'#imagePreview-header'});
	$('#imagePreview-mask').hide();
	$('#imagePreview').hide();
	$('#imagePreview-close').click($.imagePreview.close);
	$('#imagePreview-mask').click($.imagePreview.close);
	$(document).keydown(function(event){
		if(event.keyCode==27 && $('#imagePreview').is(':visible')){
			$.imagePreview.close();
		}
	});
	$(window).resize(function(){
		if($('#imagePreview-mask').is(':visible')){
			$('#imagePreview').float('center');
			$('#imagePreview-mask').css({width:$(window).width(),height:$(window).height()});
		}
	});
	$('#imagePreview-image').unbind('mousemove').bind('mousemove',function(e){
		var p=$(this).offset();
		$.imagePreview.data.X=isIE?event.x:event.x-p.left;
		$.imagePreview.data.Y=isIE?event.y:event.y-p.top;
	}).unbind('mousewheel').bind('mousewheel',function(e,delta){
		var img=$(this).children('img');
		if(img.is(':hidden')) return;
		var width=img.width(),height=img.height(),p=img.position();
		delta=(delta>0?1:(delta<0?-1:0));
		if($.imagePreview.data.zoom+0.1*delta<=0.1){
			$.imagePreview.data.zoom=0.1;
			return;
		}
		if($.imagePreview.data.zoom+0.1*delta>=16.1){
			$.imagePreview.data.zoom=16;
			return;
		}
		$.imagePreview.data.zoom=$.imagePreview.data.zoom+0.1*delta;
		$('#imagePreview-status-zoom').text(Math.round($.imagePreview.data.zoom*10)*10+'%');
		var newWidth=$.imagePreview.data.width*$.imagePreview.data.zoom,newHeight=$.imagePreview.data.height*$.imagePreview.data.zoom;
		img.css({width:Math.round(newWidth),height:Math.round(newHeight)});
		if(img.width()>$('#imagePreview-image').width() || img.height()>$('#imagePreview-image').height()){
			var oldX=$.imagePreview.data.X-p.left,oldY=$.imagePreview.data.Y-p.top;//鼠标相对图片位置[缩放前]
			var newX=$.imagePreview.data.X-img.width()*oldX/width,newY=$.imagePreview.data.Y-img.height()*oldY/height;//鼠标相对图片位置[缩放后]
			//var newX=p.left-(img.width()-width)/2,newY=p.top-(img.height()-height)/2;//鼠标相对图片位置[缩放后]
			img.css({left:Math.round(newX),top:Math.round(newY)});//图片相对于父元素的位置
		}else{
			img.float('center',$('#imagePreview-image'));
		}
		$('#imagePreview-tool-suit').addClass('enable');
		$('#imagePreview-tool-fact').addClass('enable');
	}).children('img').hide().css({width:'auto',height:'auto'}).unbind('load').bind('load',function(){
		if($(this).is(':hidden')){return false};
		$.imagePreview.data.zoom=1.0;
		$.imagePreview.data.width=$(this).width();
		$.imagePreview.data.height=$(this).height();
		$('#imagePreview-status-size').text($.imagePreview.data.width+'×'+$.imagePreview.data.height);
		$('#imagePreview-status-zoom').text('100%');
		$('#imagePreview-image').css({backgroundPosition:'-1000px -1000px'});
		$.imagePreview.data.eTime=new Date().getTime();
		$('#imagePreview-status-bps').text(($.imagePreview.data.eTime-$.imagePreview.data.bTime)/1000);
		$.imagePreview.tool($.imagePreview.data.display);
		if(isIE){
			this.style.visibility='hidden';
			this.filters[0].Apply();
			this.style.visibility='visible';
			this.filters[0].play();
			$.imagePreview.data.load=true;
		}else{
			$(this).hide().css('visibility','visible').fadeTo(0,1).fadeIn(200,function(){
				$.imagePreview.data.load=true;
				$('#imagePreview-image>img').css('visibility','visible');
			});
		}
	}).unbind('error').bind('error',function(){
		if($(this).is(':hidden')) return false;
		$('#imagePreview-status-bps').text('加载错误！');
	}).draggable().mousedown(function(){
		$.imagePreview.data.slide=clearInterval($.imagePreview.data.slide);
		$('#imagePreview-tool-slide').removeClass('down');
	});
	$('#imagePreview-toolBar a').click(function(){
		$.imagePreview.tool(this.id.split('-')[2]);
	}).mouseover(function(){
		$(this).addClass('hover');
	}).mouseout(function(){
		$(this).removeClass('hover');
	});
};
//加载图片
$.imagePreview.load=function(){
	const list = $.imagePreview.list[$.imagePreview.data.list];
	const options = list[$.imagePreview.data.index];

	if($.imagePreview.data.index == 0) $('#imagePreview-tool-previous').addClass('down');
	else $('#imagePreview-tool-previous').removeClass('down');

	if(list.length == $.imagePreview.data.index + 1) $('#imagePreview-tool-next').addClass('down');
	else $('#imagePreview-tool-next').removeClass('down');

	$('#imagePreview-status-bps').text('加载…');
	$.imagePreview.data.load=false;
	$('#imagePreview-title').text(options.title).attr('target','_blank').attr('href',options.url);
	$('#imagePreview-image').css({backgroundPosition:'center center'});
	if(isIE){
		$.imagePreview.data.bTime=new Date().getTime();
		$('#imagePreview-image>img').css('filter','progid:DXImageTransform.Microsoft.RevealTrans(duration=1,transition='+parseInt(Math.random()*22)+')');
		$('#imagePreview-image>img').show().css('visibility','hidden').css({width:'auto',height:'auto'}).attr('src',options.url);
	}else{
		$('#imagePreview-image>img').stop().fadeOut(200,function(){
			$.imagePreview.data.bTime=new Date().getTime();
			$('#imagePreview-image>img').show().css('display','block').css('visibility','hidden').css({width:'auto',height:'auto'}).attr('src',options.url);
		});
	}
	$('#imagePreview-status-count').text(($.imagePreview.data.index+1)+'/'+$.imagePreview.list[$.imagePreview.data.list].length);
};
//工具栏按钮
$.imagePreview.tool=function(type){
	var width=$('#imagePreview-image').width()-2,height=$('#imagePreview-image').height()-2,img=$('#imagePreview-image>img');
	switch(type){
		case('previous'):
			$.imagePreview.data.index=($.imagePreview.list[$.imagePreview.data.list].length+$.imagePreview.data.index-1)%$.imagePreview.list[$.imagePreview.data.list].length;
			$.imagePreview.data.step=type;
			$.imagePreview.load();
			break;
		case('next'):
			$.imagePreview.data.index=($.imagePreview.data.index+1)%$.imagePreview.list[$.imagePreview.data.list].length;
			$.imagePreview.data.step=type;
			$.imagePreview.load();
			break;
		case('suit'):
			var w=$.imagePreview.data.width,h=$.imagePreview.data.height;
			img.width(w).height(h);
			if(w>width){
				w=width;
				h=img.width(width).height('auto').height();
			}
			if(h>height){
				w=img.height(height).width('auto').width();
				h=height;
			}
			img.css({left:(width-w)/2,top:(height-h)/2});
			$.imagePreview.data.display=type;
			$.imagePreview.data.zoom=w/$.imagePreview.data.width;
			$('#imagePreview-tool-suit').removeClass('enable');
			$('#imagePreview-tool-fact').addClass('enable');
			$('#imagePreview-status-zoom').text(parseInt($.imagePreview.data.zoom*100)+'%');
			break;
		case('fact'):
			img.css({left:(width-$.imagePreview.data.width)/2,top:(height-$.imagePreview.data.height)/2,width:$.imagePreview.data.width,height:$.imagePreview.data.height});
			$.imagePreview.data.display=type;
			$('#imagePreview-tool-fact').removeClass('enable');
			$('#imagePreview-tool-suit').addClass('enable');
			$('#imagePreview-status-zoom').text('100%');
			$.imagePreview.data.zoom=1;
			break;
		case('slide'):
			if(!$.imagePreview.data.slide){
				$.imagePreview.data.slide=setInterval(function(){if($.imagePreview.data.load){$.imagePreview.tool($.imagePreview.data.step);}},$.imagePreview.data.delay*1000);
				$('#imagePreview-tool-slide').addClass('down');
			}else{
				$.imagePreview.data.slide=clearInterval($.imagePreview.data.slide);
				$('#imagePreview-tool-slide').removeClass('down');
			}
			break;
		case('zoomin'):
			if($.imagePreview.data.zoom+0.25>=16.1){
				$.imagePreview.data.zoom=16;
				return;
			}
			$.imagePreview.data.zoom=$.imagePreview.data.zoom+0.25;
			$('#imagePreview-status-zoom').text(parseInt($.imagePreview.data.zoom*100)+'%');
			img.css({width:$.imagePreview.data.width*$.imagePreview.data.zoom,height:$.imagePreview.data.height*$.imagePreview.data.zoom});
			$('#imagePreview-tool-suit').addClass('enable');
			$('#imagePreview-tool-fact').addClass('enable');
			break;
		case('shrink'):
			if($.imagePreview.data.zoom-0.25<=0.1){
				$.imagePreview.data.zoom=0.1;
				return;
			}
			$.imagePreview.data.zoom=$.imagePreview.data.zoom-0.25;
			$('#imagePreview-status-zoom').text(parseInt($.imagePreview.data.zoom*100)+'%');
			img.css({width:$.imagePreview.data.width*$.imagePreview.data.zoom,height:$.imagePreview.data.height*$.imagePreview.data.zoom});
			$('#imagePreview-tool-suit').addClass('enable');
			$('#imagePreview-tool-fact').addClass('enable');
			break;
	}
};
//打开图片预览器
$.imagePreview.open=function(){
	$.scroll(false);
	$('#imagePreview-mask').width($(window).width()).height($(window).height()).show().fadeTo(0,0).fadeTo(200,0.5);
	$('#imagePreview').show().height($(window).height()-60).width($('#imagePreview').height()*4/3).float('center');
	$('#imagePreview-image').width($('#imagePreview').width()).height($('#imagePreview').height()-10-$('#imagePreview-header').height()-$('#imagePreview-toolBar').height()-$('#imagePreview-statusBar').height());
	var loc={};
	loc.left=$('#imagePreview').css('left');
	loc.top=$('#imagePreview').css('top');
	loc.width=$('#imagePreview').width();
	loc.height=$('#imagePreview').height();
	$('#imagePreview').css({left:$(window).width()/2,top:$(window).height()/2,width:0,height:0}).animate(loc,200);
};
//关闭图片预览器
$.imagePreview.close=function(){
	$.scroll(true);
	var loc={};
	loc.left=$('#imagePreview').css('left');
	loc.top=$('#imagePreview').css('top');
	loc.width=$('#imagePreview').width();
	loc.height=$('#imagePreview').height();
	$('#imagePreview').animate({left:$(window).width()/2,top:$(window).height()/2,width:0,height:0},200,'',function(){
		$('#imagePreview-mask').fadeOut(200);
		$('#imagePreview').css(loc).hide();
		$('#imagePreview-image>img').hide().attr('src','').width('auto').height('auto').mousedown();
		$('#imagePreview-title').html('');
	});
};
//给要预览的图片和链接添加单击事件
$.fn.imagePreview=function(){
	$.imagePreview.data.list=$.imagePreview.list.length;
	$.imagePreview.list.push(new Array);
	this.each(function(){
		var loc;
		if($(this).is('img')){
			loc={title:this.alt?this.alt:this.src,url:this.src};
		}else if($(this).is('a')){
			loc={title:this.title?this.title:this.href,url:this.href};
		}
		if(loc){
			$(this).data('list',$.imagePreview.data.list);
			$(this).data('index',$.imagePreview.list[$.imagePreview.data.list].length);
			$.imagePreview.list[$.imagePreview.data.list].push(loc);
		}
	}).unbind('click').bind('click',function(){
		$.imagePreview.open();
		$.imagePreview.data.list=$(this).data('list');
		$.imagePreview.data.index=$(this).data('index');
		$.imagePreview.data.step='next';
		$.imagePreview.load();
		if($.imagePreview.list[$.imagePreview.data.list].length>1)
			$('#imagePreview-tool-previous,#imagePreview-tool-next').show();
		else
			$('#imagePreview-tool-previous,#imagePreview-tool-next').hide();
		return false;
	});
	return this;
};
//执行图片预览器初始化
$($.imagePreview.init);

})(jQuery);
