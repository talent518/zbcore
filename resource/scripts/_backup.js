	$.Script=function(url,options){
		var defaults={id:$.md5('script_'+$.md5(url)),type:'text/javascript'};
		options=$.extend(defaults,options,{src:url});
		if($.dom(options.id)) return false;
		var attrs=$.mapArray(options).join(' ');
		return $.ajax({url:options.src,async:false,cache:true,dataType:'script',success:function(data){
			try{
				window.eval(data);
			}catch(e){
				var script = document.createElement("script");
				$.extend(script,options,/[a-f0-9]+/.test(options.id)?{}:{id:($.isFunction($.md5)?$.md5('script_'+$.md5(url)):Math.random())});
				$('html>head').get(0).appendChild(script);
			}
		}});
	};
	$.Style=function(url,options){
		var defaults={id:$.md5('style_'+$.md5(url)),rel:'stylesheet',type:'text/css'};
		options=$.extend(defaults,options,{href:url});
		if($.dom(options.id)) return false;
		var attrs=$.mapArray(options).join(' ');
		var link = document.createElement("link");
		$.extend(link,options);
		$('html>head').get(0).appendChild(link);
		return link;
	};
