var isIE=$.browser.msie,
	isIE6=isIE && $.browser.version=='6.0',
	isIE7=isIE && $.browser.version=='7.0',
	isIE8=isIE && $.browser.version=='8.0';
var isOpera=$.browser.opera;
var isMoz=$.browser.mozilla;
var isSafari=$.browser.safari;

String.prototype.left=function(len){
	if(len<=0) return this;
	return this.substr(0,len);
};
String.prototype.right=function(len){
	return this.substr(this.length-len);
};
String.prototype.repeat=function(len){
	var result='';
	for(var i=0;i<len;i++){
		result+=this;
	}
	return result;
};

//%2s,%2d,%2.2f
function sprintf(){
	var s=arguments[0] || '',r=[],c=0;
	for(var i=1;i<arguments.length;i++){
		r[i]=arguments[i];
	}

	return s.replace(/%([0-9.]+)?(s|d|f)/ig,function(a){
		c++;
		a=a.match(/([0-9.]+)|(s|d|f)/ig);
		if(a.length!=2){
			a[1]=a[0];
			a[0]=0;
		}
		a[1]=a[1].toLowerCase();
		if(a[1]=='f'){
			a=String(parseFloat(a[0])).split('.');
			a[0]=parseInt(a[0]);
			a[1]=parseInt(a[1]);
			var _r=String(parseFloat(r[c])).split('.'),f=_r[0].indexOf('-')!=-1;r[0]=(f?r[0].substr(1):r[0]);
			return((f?'-':'')+'0'.repeat(a[0]-_r[0].length)+_r[0]+(_r[1]?'.'+_r[1]+'0'.repeat(a[1]-_r[1].length):''));
		}else if(a[1]=='d'){
			a[0]=parseInt(a[0]);
			r[c]=parseInt(r[c]);
			return(r[c]<0?'-':'')+('0'.repeat(a[0]-String(r[c]).length)+(r[c]>0?r[c]:-r[c]));
		}else{
			a[0]=parseInt(a[0]);
			return(' '.repeat(a[0]-r[c].length)+r[c]);
		}
	});
};

;(function($){
	$.mapArray=function(arrays,callback){
		var newArray=new Array(),key,value;
		for(key in arrays){
			value=arrays[key];
			if($.isFunction(callback)){
				newArray.push(callback.call(this,key,value));
			}else if(typeof(callback)=='string'){
				newArray.push(callback.replace('{key}',new String(key)).replace('{value}',new String(value)));
			}else{
				newArray.push(key+'="'+value+'"');
			}
		}
		return newArray;
	};
	$.URL2URI=function(url,looseMode){
		//example 1: parse_url('http://username:password@hostname/path?arg=value#anchor');
		//returns 1: {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'}
		var o={
			key:['source','protocol','authority','userInfo','user','pass','host','port','relative','path','dir','file','query','anchor'],
			q:{
				name:'querys',
				parser:/(?:^|&)([^&=]*)=?([^&]*)/g
			},
			parser:{
				strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
				loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
			}
		},mode=(looseMode?'loose':'strict'),m=o.parser[mode].exec(url),uri={},i=14;

		while(i--){
			uri[o.key[i]]=m[i]||'';
		}

		uri[o.q.name]={};
		uri[o.key[12]].replace(o.q.parser,function($0,$1,$2){
			if($1) uri[o.q.name][$1]=$2;
		});

		return uri;
	};
	$.URI2URL=function(uri,isPath){
		var url=(uri.protocol?uri.protocol+'://':'')+uri.host;
		url+=(uri.port?':'+uri.port:'');
		uri.path=(isPath?uri.path:uri.dir+uri.file);
		url+=((uri.path.indexOf('/')!=0?'/':'')+uri.path);
		for(var key in uri.querys){
			url+=(url.indexOf('?')!=-1?'&':'?');
			url+=(key+'='+uri.querys[key]);
		}
		url+=(uri.anchor?'#'+uri.anchor:'');
		return(url);
	};
	$.xURL=function(url){
		var uri=$.URL2URI(url),_uri=$.URL2URI(window.location.href);
		uri.protocol=_uri.protocol;
		uri.port=_uri.port;
		uri.host=_uri.host;
		uri.querys.inAjax=1;
		url=$.URI2URL(uri);
		return url;
	};
	$.fn.message=function($message,$css){
		var $msg=$('#message');
		if($msg.size()==0){
			$msg=$('<div id="message"></div>').appendTo(document.body);
			$msg.hide().css({position:'absolute',zIndex:9999,border:'1px #e7e7e7 solid',backgroundColor:'#fcfcfc',padding:'5px'}).css($css);
		};
		$(document).mousemove(function(e){
			var $css={left:e.pageX+16,top:e.pageY+20},both=0;
			if($css.top+$msg.outerHeight()>$(document).scrollTop()+$(window).height()){
				$css.top-=$msg.outerHeight()+20;
				$css.left-=16;
				both++;
			}
			if($css.left+$msg.outerWidth()>$(document).scrollLeft()+$(window).width()){
				$css.left-=$msg.outerWidth()+16;
				$css.top-=20;
				both++;
			}
			if(both==2){
				$css.left+=16;
				$css.top+=20;
			}
			$msg.css($css);
		});
		$(this).hover(function(e){
			$msg.html(typeof($message)=='function'?$message.call(this):$message);
			$msg.stop(true);
			$msg.css({width:'auto',height:'auto'}).fadeTo(0,1).show('slow');
		},function(){
			$msg.hide('slow');
		});
		return this;
	};
	$.debug=function($message){
		var $msg=$('#debug');
		if($msg.size()==0){
			$msg=$('<div id="debug"><a id="debug-close" href="#close" class="submit">关闭</a><div id="debug-content"></div></div>').appendTo(document.body);
			$msg.draggable().hide().css({position:'absolute',zIndex:9999,cursor:'move',border:'2px #d8ecff solid',backgroundColor:'#f4f9ff',width:$(window).width()/1.5,height:$(window).height()/1.5}).float('center');
			$('#debug-close').css({position:'absolute',bottom:-25,right:-2,overflow:'hidden'}).click(function(){
				$msg.hide('slow');
				$.scroll(true);
				return false;
			});
			$('#debug-content').css({position:'absolute',width:$(window).width()/1.5-20,height:$(window).height()/1.5-20,padding:'10px',overflow:'hidden',overflowX:'hidden',overflowY:'scroll'});
		};
		$('#debug-content').html($message);
		$msg.show('slow');
		$.scroll();
		return this;
	};
	$.fn.fblur=function(warn){
		$(this).data('warn',warn).blur(function(){
			var val=$(this).val();
			if(val=='' || val==undefined){
				$(this).val($(this).data('warn'));
			}
			$(this).removeClass('focusb fbtrue fbfalse').addClass('fblur');
		}).focus(function(){
			if($(this).val()==$(this).data('warn')){
				$(this).val('');
			}
			$(this).removeClass('fblur fbtrue fbfalse').addClass('focusb');
		}).blur();
		$(this.form).unbind('submit.fblur').bind('submit.fblur',function(){
			var $return=true;
			$('.focusb,.fblur,.fbtrue,.fbfalse',this).each(function(){
				if($(this).val()==$(this).data('warn')){
					$(this).removeClass('focusb fblur fbtrue').addClass('fbfalse');
					$return=false;
				}else{
					$(this).removeClass('focusb fblur fbfalse').addClass('fbtrue');
				}
			});
			return $return;
		});
		return this;
	};
})(jQuery);

(function($){
	var $ajax=$.ajax,$load=$.fn.load;
	$.ajax=function(s){
		s.url=$.xURL(s.url);
		s.callback=s.success;
		s.success=function(data,status){
			if($.isFunction(s.callback))
				s.callback(data,status);
			if(isIE6){
				$('input[type=button]').addClass('button');
				$('input[type=submit]').addClass('submit');
			}
		};
		return $ajax.call(this,s);
	};
	$.XML=function(xml,callback){
		if(typeof(xml)=='object'){
			var root=$(xml).find('root'),message=$(xml).find('message');
			if(root.size()){
				return root.text();
			}
			if(message.size()){
				try{
					msg=window["eval"]("("+message.text()+")");
				}catch(e){
					msg=false;
					$.debug(message.text(),{width:$(window).width()/2,height:$(window).height()/2});
					return;
				}
				if(msg['function']){
					try{
						window["eval"]("(function(){"+msg['function']+"})").call(msg);
						return;
					}catch(e){}
				}
				if(msg.callback){
					try{
						callback=window["eval"]("(function(){"+msg.callback+"})");
					}catch(e){}
				}
				if(msg.status){
					$.dialog.success(msg.message,function(){
						if($.isFunction(callback))
							callback.call(msg);
						else if(msg.backurl.length>0)
							location.href=msg.backurl;
					});
				}else{
					$.dialog.error(msg.message,function(){
						if($.isFunction(callback))
							callback.call(msg);
						else if(msg.backurl.length>0)
							location.href=msg.backurl;
					});
				}
			}
		}else{
			$.debug(xml,{width:$(window).width()/2,height:$(window).height()/2});
		}
		return false;
	};
	$.sXML=function(xml,callback){
		return $.XML(xml,function(){
			if(this.status){
				if(this.backurl)
					$('#bd').load(this.backurl);
			}else{
				if(this.backurl){
					if($.window){
						$.window({title:this.message,url:this.backurl});
					}else{
						window.open(this.backurl,'_blank');
					}
				}
			}
			if($.isFunction(callback))
				callback.call(this);
		});
	};
	$.get=function(url,data,callback){
		if($.isFunction(data)){
			callback=data;
			data={};
		}
		$.ajax({url:url,data:data,type:'GET',success:function(xml){
			xml=$.XML(xml);
			if(xml && $.isFunction(callback))
				callback(xml);
		}});
	};
	$.getJson=function(url,data,callback){
		if($.isFunction(data)){
			callback=data;
			data={};
		}
		$.ajax({url:url,data:data,type:'GET',dataType:'json',success:function(json){
			callback(json);
		}});
	};
	$.getScript=function(src,callback){
		var _script=document.createElement('script');
		_script.setAttribute('type','text/javascript');
		_script.setAttribute('src',src);
		document.getElementsByTagName('head')[0].appendChild(_script);

		if (isIE){
			_script.onreadystatechange=function(){
				if (this.readyState=='loaded' || this.readyState=='complete')
					callback();
			};
		}else if(isMoz)
			_script.onload =callback;
		else
			callback();
	};
	$.post=function(url,data,callback){
		$.ajax({url:url,data:data,type:'POST',success:function(xml){
			xml=$.sXML(xml);
			if(xml && $.isFunction(callback))
				callback(xml);
		}});
	};
	$.fn.load=function(url,data,callback){
		if(typeof(url)!='string')
			return $load.call(this,url);
		if($.isFunction(data)){
			callback=data;
			data={};
		}
		var $this=this;
		if($this.size())
			$.ajax({url:url,type:'GET',data:data,success:function(xml){
				xml=$.sXML(xml);
				if(xml){
					$($this).html(xml);
					if($.isFunction(callback))
						callback.call($this,xml);
				}
			}});
		return this;
	};
	$.ajaxSettings.success=function(xml){
		xml=$.sXML(xml);
		if(xml){
			if($.isFunction(callback))
				callback(xml);
			else
				$.debug(xml);
		}
	};
})(jQuery);

(function($){
	$(window).resize(function(){
		$('#wpl,#ie-select-bug').width($(window).width()).height($(window).height());
	});
	$.scrollTop=0;
	$.scrollEnabled=true;
	$.scrollDisabled=false;
	$.scroll=function(enable){
		$.scrollEnabled=(enable?true:false);
		$.scrollDisabled=!$.scrollEnabled;
		if($.scrollEnabled){
			$('#ie-select-bug').hide();
			window.scrollTo(0,this.scrollTop);
			$('html').css({overflow:'hidden',overflowX:'hidden',overflowY:'auto'});
		}else{
			$('#ie-select-bug').show();
			this.scrollTop=$(window).scrollTop();
			window.scrollTo(0,0);
			$('html').css({overflow:'hidden',overflowX:'hidden',overflowY:'hidden'});
		}
	};
})(jQuery);

(function($){
	$(function(){
		$(window).resize();
		if(isIE)
			$('<iframe id="ie-select-bug" scrolling="no" border="0"/>').appendTo(document.body).width($(window).width()).height($(window).height()).css({position:isIE6?'absolute':'fixed',left:0,top:0,zIndex:666}).fadeTo(0,0);
		$.scroll(true);
		$('<div id="loading">加载中…</div>').appendTo(document.body).ajaxStart(function(){
			$(this).float('center').show();
		}).ajaxSend(function(){
			$(this).float('center').show();
		}).ajaxStop(function(){
			$(this).hide();
		}).ajaxError(function(e,xhr,s){
			$.dialog.error('请求地址【'+s.url+'】错误！');
			$(this).hide();
		}).hide();
		if(isIE6){
			$(window).resize(function(){
				$('#loading').float('center');
			});
			$('input[type=button]').addClass('button');
			$('input[type=submit]').addClass('submit');
		}
		if($.validator && $.fn.ajaxSubmit){
			var $ajaxSubmit=$.fn.ajaxSubmit;
			$.fn.ajaxSubmit=function(options){
				if($.isFunction(options)){
					options={success:options};
				}
				return $ajaxSubmit.call(this,$.extend(true,{beforeSerialize:function(form,options){
					options.url=$.xURL(options.url);
				}},options));
			};
			$.validator.setDefaults({
				submitQuiet:true,
				submitHandler:function(form){
					var settings=this.settings;
					$(form).ajaxSubmit(function(xml){
						if(settings.submitQuiet){
							var win=$(form).getWindow();
							xml=$.sXML(xml,function(){
								if(this.status && win){
									win.close();
								}
							});
						}else{
							xml=$.XML(xml);
						}
						if(xml){
							if($.isFunction(callback))
								callback(xml);
							else
								$.debug(xml);
						}
					});
					return false;
				}
			});
		}
	});
})(jQuery);
